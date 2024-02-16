<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\BusinessInsight;
use App\Models\Mynetwork_request;
use App\Models\SellerProduct;
use App\User;
use App\Models\Message;
use DB;
use Illuminate\Support\Facades\Mail;
class InsightNotificationJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InsightNotificationJob:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron Job send insight Report';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //get all active subscribed users
        $user_list=DB::table('users')->leftJoin('subscriptions', function($join)
                                               {
                                                  $join->on('subscriptions.user_id', '=', 'users.id');
                                                  $join->orOn('subscriptions.user_id', '=', 'users.parent_id');
                                               })   
                                            ->leftJoin('order_details', 'subscriptions.order_id', '=', 'order_details.id')
                                            ->leftJoin('packages', 'packages.id', '=', 'order_details.package_id')
                                            ->where('users.status','<>','Deleted')
                                            ->where('subscriptions.status','Active')
            ->select('users.id',
            DB::raw("(CASE 
                WHEN users.seller_type='Co-Seller' and packages.subscription_type!='Extended' THEN 'false'  
                WHEN subscriptions.expairy_date > NOW() THEN 'true' 
                ELSE 'false' 
                END ) as expairy"))
            ->orderBy('subscriptions.id','DESC')->groupBy('users.id')->get(); 
               
        $active_users=$user_list->where('expairy','true')->pluck('id');//all active subscribed users array

        $from_date=Carbon::now()->subdays(6);
        $to_date=Carbon::now();
                
        //get all users list
        $users_list = User::where("status", "<>", "Deleted")
                    ->whereIn("id", $active_users)
                    ->get();
        //loop through users for sending emails            
        foreach ($users_list as $key => $value) {

                 if($value->seller_type=="Co-Seller")
                    $u_id=$value->parent_id;
                 else
                    $u_id=$value->id;
                //profile visit count
                $profile_visit_count = BusinessInsight::where("profile_id",$u_id)
                                                ->whereDate("visited_at", ">=", $from_date)
                                                ->whereDate("visited_at", "<=",$to_date)
                                                ->count();
                // mynetwork count                                
                $my_networks = Mynetwork_request::where("user_id", $value->id)
                                                ->whereDate("created_at", ">=", $from_date)
                                                ->whereDate("created_at", "<=",$to_date)
                                                ->count();
                // chat count                                 
                $chat_count = Message::where('to_user',$value->id)->whereDate("created_at", ">=", $from_date)
                                                ->whereDate("created_at", "<=",$to_date)
                                                ->count();
                //product view count                                
                $user_products = SellerProduct::where("status", "active")
                         ->where("user_id", $u_id)->pluck('id')->all();                              
                $product_view_count = BusinessInsight::whereIn("product_id",$user_products)
                                                ->whereDate("created_at", ">=", $from_date)
                                                ->whereDate("created_at", "<=",$to_date)
                                                ->count();
                $from_date=date('d-m-Y',strtotime($from_date));
				$to_date=date('d-m-Y',strtotime($to_date));                                
                //Insight report mail                                 
                Mail::send('emails.InsightReportMail', ['name' => $value->name,'email' => $value->email,'profile_visit_count' => $profile_visit_count,'my_networks' => $my_networks,'chat_count' => $chat_count,'product_review_count' => $product_view_count,'from_date' => $from_date,'to_date' => $to_date], function($message) use($value,$from_date,$to_date){
                        $message->to($value->email);
                        $message->subject('Insight Report ('.$from_date.' - '.$to_date.') - FMCG');
                    });
        }
    }
}
