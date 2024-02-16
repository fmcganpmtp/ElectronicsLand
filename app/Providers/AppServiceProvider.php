<?php
namespace App\Providers;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\SellerProduct;
use App\Models\SellerProductTemp;
use App\Models\FrontEndMenu;
use App\Models\Message;
use App\Models\Contentpage;
use App\User;
use App\Models\Mynetworks;
use App\Models\Chat_contact_delete;
use Illuminate\Support\Facades\View;
use App\Models\Wishlist;
use Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        view::composer('*', function ($gen_all){
        $general_all = DB::table('generalsettings')
            ->get(); 
        $gen_all->with('view_composer_general', $general_all);
        });
        // view::composer('*', function ($top_menu_category){
        //     $top_categories =  Category::where('parent_id',null)->where('name','<>','')->where('have_product','Yes')->get();
        //     $top_menu_category->with('view_composer_TopmenuCats', $top_categories);
        // });
        /*view::composer('*', function ($top_menu_category_visible){
           $top_categories_visible =  FrontEndMenu::with('Category')->get();
           $top_menu_category_visible->with('view_composer_TopmenuCats_visible', $top_categories_visible);
        });*/
        view::composer('*', function ($chact_unreadcnt){
            if (Auth::guard('user')->check())
            {
                $user_id =  Auth::guard('user')->user()->id;

                $users = DB::select( DB::raw("SELECT distinct users.id FROM users
                                                    JOIN subscriptions ON subscriptions.user_id = users.id  AND subscriptions.status='Active'
                                                    WHERE subscriptions.expairy_date > NOW()"));
                $active_sellers = [];
                foreach ($users as $user ) {
                    $active_sellers[] = $user->id;
                }

                  $deleted_id=Chat_contact_delete::where('seller_id',$user_id)->pluck('deleted_id')->all();

                  $chat_count = Message::where('to_user',$user_id)->where('message_status', 'unread')
                                ->whereNotIn('from_user', $deleted_id)
                                ->whereIn('from_user',$active_sellers)->count();
            }
            else
                $chat_count=0;
            $chact_unreadcnt->with('view_composer_chact_unreadcnt', $chat_count);
        });
        view::composer('*', function ($social_media){
            $social_icons =  DB::table('social_media')
            ->get(); 
            $social_media->with('view_composer_socialIcons', $social_icons);
        });
       /* view::composer('*', function ($co_users_visible_criteria){
    
                $flag=$flag2=$flag_blocked_active=false;
                $parent_id='';
                $no_of_package=0;
                if (Auth::guard('user')->check())
                {
                    
                    if(Auth::guard('user')->user()->seller_type=='Co-Seller')
                                                                   $parent_id=Auth::guard('user')->user()->parent_id;
                    else
                                                                   $parent_id=Auth::guard('user')->user()->id;    
                                               
                    $package_data = DB::table('subscriptions')
                                                                   ->leftJoin('order_details', 'subscriptions.order_id', '=', 'order_details.id')
                                                                   ->leftJoin('packages', 'packages.id', '=', 'order_details.package_id')
                                                                   ->where('subscriptions.user_id', '=',$parent_id)
                                                                   ->where('subscriptions.status','active')
                                                                   ->orderBy('subscriptions.id','DESC')
                                                                   ->first();
                                               
                    if(!empty($package_data)){  
                                if( $package_data->subscription_type=='Extended' && $package_data->expairy_date>=date('Y-m-d'))
                                  {  
                                    $flag=true;
                                    $no_of_package=$package_data->no_of_accounts;
                                }
                                else
                                    $flag=false;
                                if( $package_data->expairy_date>=date('Y-m-d'))
                                   {
                                    $flag2=true;
                                    $no_of_package=$package_data->no_of_accounts;
                                }
                                 else
                                    $flag2=false;  
                                    
                                
                                  if(!empty(Auth::guard('user')->user()->status))  
                                  {
                                      if(Auth::guard('user')->user()->status=='Blocked' &&  $package_data->expairy_date>=date('Y-m-d'))
                                      {
                                          $flag_blocked_active = "true";
                                      }
                                  }
                                                               
                    } 
                    else
                            $flag=$flag2=false;
            } 
            else
                $flag=$flag2=false;                             
            $co_users_visible_criteria->with('view_composer_co_users_criteria', ['flag'=>$flag,'flag2'=>$flag2,'flag_blocked_active'=>$flag_blocked_active,'no_of_package'=>$no_of_package]);
    
            
        });*/
        view::composer('*', function ($profile_menu_visible_criteria){
    
               
                $parent_id='';
                $flag=$flag_blocked_active = $flag2 =false;
                $no_of_package=0;
                if (Auth::guard('user')->check())
                {
                    
                    if(Auth::guard('user')->user()->seller_type=='Co-Seller')
                                                                   $parent_id=Auth::guard('user')->user()->parent_id;
                    else
                                                                   $parent_id=Auth::guard('user')->user()->id;    
                                               
                    $package_data = DB::table('subscriptions')
                                                                   ->leftJoin('order_details', 'subscriptions.order_id', '=', 'order_details.id')
                                                                   ->leftJoin('packages', 'packages.id', '=', 'order_details.package_id')
                                                                   ->where('subscriptions.user_id', '=',$parent_id)
                                                                   ->where('subscriptions.status','active')
                                                                   ->whereDate('subscriptions.expairy_date', '>=', Carbon::now())
                                                                   ->orderBy('subscriptions.id','DESC')
                                                                   ->first(); 
                  $product_count_approved = SellerProduct::where("user_id", $parent_id)->where("status",'<>', "deleted")->count(); 
                  $product_count_pending = SellerProductTemp::where("user_id", $parent_id)->where("status",'<>', "deleted")->count(); 
                  $product_count = $product_count_approved + $product_count_pending;
                  //$product_count = SellerProduct::where("user_id", $parent_id)->where("status",'<>', "deleted")->count();   
                 //dd($product_count);
                // dd($package_data)    ;                                              
                                                                   
             
            } 
             
              if(!empty($package_data))
              { if( $package_data->expairy_date>=date('Y-m-d'))
                                   {  $flag2=true;  }
                                
                                  if(!empty(Auth::guard('user')->user()->status))  
                                  {
                                      if(Auth::guard('user')->user()->status=='Blocked' &&  $package_data->expairy_date>=date('Y-m-d'))
                                      {
                                          $flag_blocked_active = "true"; ///u
                                      }
                                  }
                                  /*if($package_data->market_uploads>0 || $package_data->market_uploads=='')
                                  $market_uploads = 1;
                                  else
                                  $market_uploads=0;*/
                                  $prd_menu_visble=0;
                                  $market_productRequests = $package_data->market_productRequests;
                                  $market_uploads = $package_data->market_uploads;
                                  if($package_data->market_uploads=='' || $package_data->market_uploads>0)
                                  $prd_menu_visble=1;
                                  if($package_data->market_uploads=='')
                                    	$prdts_to_uplod="Unlimited";
                                  elseif($market_uploads>0 && $market_uploads>$product_count)
                                        $prdts_to_uplod=$market_uploads-$product_count;
                                  else
                                        $prdts_to_uplod=0;
                              
                $profile_menu_visible_criteria->with('view_composer_profile_menu_visible_criteria', 
                ['general_companyProfile'=>$package_data->general_companyProfile,
                'flag2'=>$flag2, //user plan active
                'flag_blocked_active'=>$flag_blocked_active,
                'general_companyDashboard'=>$package_data->general_companyDashboard,
                'general_profileInsights'=>$package_data->general_profileInsights,
                'general_accesToMedia'=>$package_data->general_accesToMedia,
                'market_Search'=>$package_data->market_Search,
                'market_uploads'=>$market_uploads,
                'market_productRequests'=>$package_data->market_productRequests,
                'market_createProductRequests'=>$package_data->market_createProductRequests,
                'network_expand'=>$package_data->network_expand,
                'prd_menu_visble'=>$prd_menu_visble,
               // 'market_uploads'=>$package_data->market_uploads,
                'active_product_count'=>$product_count,
               
                'prdts_to_uplod'=>$prdts_to_uplod,
                
                'network_chat'=>$package_data->network_chat]);
              }
              else
                 $profile_menu_visible_criteria->with('view_composer_profile_menu_visible_criteria', ['general_companyProfile'=>'','active_product_count'=>0,'prd_menu_visble'=>'','prdts_to_uplod'=>'','general_companyDashboard'=>'','flag2'=>$flag2,'flag_blocked_active'=>$flag_blocked_active,'general_profileInsights'=>'','general_accesToMedia'=>'','market_Search'=>'','market_uploads'=>'','market_productRequests'=>'','market_createProductRequests'=>'','network_expand'=>'','network_chat'=>'']);
            
        });
        view::composer('*', function ($footer_help){
            $footer=Contentpage::WhereRaw('find_in_set("Footer Help",page_position)')->select('seo_url','page')->get(); 
            $footer_help->with('view_footer_help', $footer);
        });
        view::composer('*', function ($top_content){
            $top=Contentpage::WhereRaw('find_in_set("Top",page_position)')->select('seo_url','page')->get(); 
            $top_content->with('view_top_content', $top);
        });
        view::composer('*', function ($footer_info){
            $footer=Contentpage::WhereRaw('find_in_set("Footer Information",page_position)')->select('seo_url','page')->get();
            $footer_info->with('view_footer_info', $footer);
        });
        view::composer('*', function ($wishCounts){
            $wishCount = 0;
            if(Auth::guard('user')->check()) {
                $user_id = Auth::guard('user')->user()->id;
                $wish_listed=  Wishlist::where('user_id','=',$user_id)->where('wishlist','=','Yes')->get();
                $wishCount = count($wish_listed);
            }
            $wishCounts->with('view_composer_wishCount', $wishCount);
        });
    // view::composer('*', function ($header_user){
        
   //     if(Auth::guard('user')->check()) {
   //         $user_id = Auth::guard('user')->user()->id;
    //        $view_composer_user=  User::find($user_id);
    //    } else $view_composer_user=null;
   //     $header_user->with('view_composer_user', $view_composer_user);
   // });
    }
}
