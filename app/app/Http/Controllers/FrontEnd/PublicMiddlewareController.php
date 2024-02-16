<?php

namespace App\Http\Controllers\FrontEnd;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\SellerProduct;
class PublicMiddlewareController extends Controller
{

 public function checkUserValidity(){

     if(Auth::guard('user')->user()) {

                        if(Auth::guard('user')->user()->status=='Deleted'||Auth::guard('user')->user()->status=='Blocked'||Auth::guard('user')->user()->status=='Rejected')
                            return false;
                        if(Auth::guard('user')->user()->seller_type=='Co-Seller')
                            $parent_id=Auth::guard('user')->user()->parent_id;
                        else
                            $parent_id = Auth::guard('user')->user()->id;
                        
                            $package_data = DB::table('subscriptions')
                                            ->leftJoin('order_details', 'subscriptions.order_id', '=', 'order_details.id')
                                            ->leftJoin('packages', 'packages.id', '=', 'order_details.package_id')
                                            
                                            ->where('subscriptions.user_id', '=',$parent_id)
                                            ->where('subscriptions.status','Active')
                                            ->select('subscriptions.id','subscriptions.expairy_date','packages.subscription_type')
                                            ->orderBy('subscriptions.id','DESC')->first();
                                                   
                            if(!empty($package_data)){  
                                //if( $package_data->subscription_type=='Extended' && $package_data->expairy_date>=date('Y-m-d'))
                                if( $package_data->expairy_date>=date('Y-m-d'))
                                    return true;
                                else
                                    return false;                        
                            }
                            else
                                    return false;  

                    }
                    else
                                    return false; 
        }

    public function checkUserContactValidity(){

     if(Auth::guard('user')->user()) {

                        if(Auth::guard('user')->user()->status=='Deleted'||Auth::guard('user')->user()->status=='Blocked'||Auth::guard('user')->user()->status=='Rejected')
                            return false;
                        
                        if(Auth::guard('user')->user()->seller_type=='Co-Seller')
                            $parent_id=Auth::guard('user')->user()->parent_id;
                        else
                            $parent_id = Auth::guard('user')->user()->id;
                        
                            $package_data = DB::table('subscriptions')
                                            ->leftJoin('order_details', 'subscriptions.order_id', '=', 'order_details.id')
                                            ->leftJoin('packages', 'packages.id', '=', 'order_details.package_id')
                                           
                                            ->where('subscriptions.user_id', '=',$parent_id)
                                            ->where('subscriptions.status','Active')
                                            ->select('subscriptions.id','subscriptions.expairy_date','packages.subscription_type')
                                            ->orderBy('subscriptions.id','DESC')->first();
                                                     
                            if(!empty($package_data)){  
                                if($package_data->expairy_date>=date('Y-m-d'))
                                    return true;
                                else
                                    return false;                        
                            }
                            else
                                    return false;  

                    }
                    else
                                    return false; 
        }


        public function checkUserlogin()
        {

            if(Auth::guard('user')->user()) {
                //if(Auth::guard('user')->user()->status=='Deleted'||Auth::guard('user')->user()->status=='Blocked'||Auth::guard('user')->user()->status=='Rejected')
                if(Auth::guard('user')->user()->status=='Deleted'||Auth::guard('user')->user()->status=='Rejected')
                        return false;
                else 
                        return true;               
            }
             return false;

         }  

        public function getexpiredsellerslist()
        {
            $users = DB::select( DB::raw("SELECT distinct users.id FROM users
                                                    JOIN subscriptions ON subscriptions.user_id = users.id  AND subscriptions.status='Active'
                                                    WHERE users.usertype = 'seller' AND subscriptions.expairy_date > NOW()"
                                                ));

            //@TODO : Refactor query to return an array immediately
            $userArray = [];
            foreach ($users as $user ) {
                $userArray[] = $user->id;
            }

           return $userArray;
        }

        public function getexpireduserslist()
        {

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
           /* DB::raw("(CASE 
                WHEN users.seller_type='Co-Seller' and packages.subscription_type!='Extended' THEN 'false'  
                WHEN subscriptions.expairy_date > NOW() THEN 'true' 
                ELSE 'false' 
                END ) as expairy"))
                ->select('users.id',*/
            DB::raw("(CASE 
                
                WHEN subscriptions.expairy_date > NOW() THEN 'true' 
                ELSE 'false' 
                END ) as expairy"))
            ->orderBy('subscriptions.id','DESC')->groupBy('users.id')->get(); 
               
                return $user_list->where('expairy','true')->pluck('id');
           
        } 
        
        public function getnextlevelproductexist($current_id,$parents_path)
        {
            $category_ids = [];
            $current_id=(int)$current_id;
            $categorylist=Category::where('parent_id',$current_id)->where('have_product','Yes')->pluck('id')->all();     
            // all subcategories                   
			array_push($category_ids,$current_id);  //prent category to array

			foreach ($categorylist as $value){
			    if(!in_array($value, $category_ids)) //subctegory id not in $main->id array
					array_push($category_ids,$value); //all category ids as array
				for ($i=0; $i<count($category_ids);$i++){
					$category1=Category::where('parent_id',$category_ids[$i])->where('have_product','Yes')->get();
					foreach ($category1 as  $value2){   
						if(!in_array($value2->id, $category_ids))
							array_push($category_ids,$value2->id);
					}                             
				}                
			}
		    
			$old_category=explode(",",$parents_path);
			$product_list=SellerProduct::whereIn('category_id',$category_ids)
                                ->where('product_visibility','Yes')
                                ->where("status", "active")
                                ->get();
           if(count($product_list)>0)
            { 
                Category::whereIn('id', $old_category)->update(['have_product'=>'Yes']);
                return true;
            }
            else
            {   
                Category::whereIn('id', $old_category)->update(['have_product'=>'No']);
                return false;
            }
            
			
        }
}