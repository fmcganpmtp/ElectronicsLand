<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Package;
use App\User;
use App\Models\Subscription;
use DB;
use App\Models\IncludedFeature;
use App\Models\PackageAccount;
use App\Models\Category;
use App\Models\SellerProduct;
use App\Models\ExcludedFeatures;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\FrontEnd\PublicMiddlewareController;
use Carbon\Carbon;
class PackageController extends Controller
{
    protected $PublicMiddlewareController;
    public function __construct(PublicMiddlewareController $PublicMiddlewareController) 
    {
        $this->middleware('auth:admin');
        $this->PublicMiddlewareController = $PublicMiddlewareController;
    }
    //display list page
    public function listPackages(Request $request) {     
        return view('admin.packages.listPackages');
    }
    //display subscription users list page
    public function subscriptionusers() {
        return view('admin.packages.subscription-users-list');
    }
    //display subscription users list
    public function subscriptionusersdetails($id) {
        $user=User::find($id);
        return view('admin.packages.subscription-users-detailed-view',compact('user'));
    }
    
    //subscription  status changes
    public function subscriptionstatuschange(Request $request)
   {
    $subscription_id=$request->get('subscription_id');
    $status=$request->get('status');


    //find subscription data
	$Subscription=Subscription::find($subscription_id);		
	if($status=="active") {	
	    $status = $Subscription->status;
	    $new_expairy_date = $Subscription->expairy_date;
	    $new_suspend_end_date = $Subscription->suspend_end_date;
	    //if suspend package change status
	    if($status=='Suspended')
	    {
	        $expairy_date= $Subscription->expairy_date;
	        $expairy_date = Carbon::createFromFormat( "Y-m-d",$expairy_date);
	        $suspend_start_date= $Subscription->suspend_start_date;
	        $today = date("Y-m-d");
	        $new_suspend_end_date = $today = Carbon::parse($today);
	        $suspend_start_date = Carbon::parse($suspend_start_date);
	        $days_suspended = $suspend_start_date->diffInDays($today);
	        $new_expairy_date = $expairy_date->addDays($days_suspended);
	    }
	    $Subscription->suspend_end_date = $new_suspend_end_date; 
	    $Subscription->expairy_date =$new_expairy_date;
		$Subscription->status ="Active";
		$Subscription->save();	 
        DB::table('seller_products')->where('user_id',$Subscription->user_id)->where('status','pending')->update(['status'=>'active']);

	}
			
     if($status=="suspended")
	 {
            $Subscription->suspend_start_date = date("Y-m-d"); 	
            $Subscription->suspend_end_date = null; 
            $Subscription->status ="Suspended";
            $Subscription->save();
            DB::table('seller_products')->where('user_id',$Subscription->user_id)->where('status','active')
                ->update(['status'=>'pending']); 
	}  
	
	 $seller_active_products=SellerProduct::where('user_id',$Subscription->user_id)->get();
     $unique_category=[];

            foreach ($seller_active_products as $key => $value) {
                if(!in_array($value->category_id, $unique_category)) 
                    array_push($unique_category,$value->category_id);
            }
            foreach ($unique_category as $value) {
                    $parents = collect([]);
                    $parent = Category::where('id', $value)->first();
                    //array push
                    while(!is_null($parent)) {
                        $parents->push($parent);
                        $parent = $parent->parent;
                    }
                    $old_category=$parents->pluck('id')->all();
                    $commaarray=implode(",",$old_category);
                    //check and fix value next level category products exist
                    $result= $this->PublicMiddlewareController->getnextlevelproductexist($value,$commaarray);
                     if($result==false)
                     {
                        $parent = Category::where('id', $value)->first();
                        if(!empty($parent->parent))
                            $parent = $parent->parent;
                        $parents = collect([]);
                        while(!is_null($parent)) {
                                $parents->push($parent);
                                $parent = $parent->parent;
                        }
                        $old_category=$parents->pluck('id')->all(); 
                        foreach($old_category as $val){
                            $result= $this->PublicMiddlewareController->getnextlevelproductexist($val,$val);
                            // if($result==true)
                            //     break;
                        }
                        
                    }
                    
        }
           
     
           echo json_encode('Status Updated');
}  
    //fetch subscription users list
    public function getsubscriptionuserslist(Request $request)
    {  
         
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $Package=$request->get('Package');
        $user_type=$request->get('user_type');
        $searchValue=$request->get('search_key');
        $company_name=$request->get('company_name');
        //total count
        $totalRecords = Subscription::select('count(*) as allcount')->where(function ($query) {
              $query ->where('subscriptions.status', '=', 'Active')
                     ->orWhere('subscriptions.status', '=', 'Suspended')->orWhere('subscriptions.status', '=', 'Expired');
           })
            ->leftJoin('packages', 'packages.id', '=', 'subscriptions.package_id')
            ->leftJoin('users', 'users.id', '=', 'subscriptions.user_id')
            ->where('users.status','<>','Deleted')
            ->where('packages.status','!=','deleted')
            ->distinct('user_id')->count();
        //total filtered data count
        $totalRecordswithFilter = Subscription::select('count(*) as allcount')->where(function ($query) {
              $query ->where('subscriptions.status', '=', 'Active')
                     ->orWhere('subscriptions.status', '=', 'Suspended')->orWhere('subscriptions.status', '=', 'Expired');
           })
            ->leftJoin('users', 'users.id', '=', 'subscriptions.user_id')
            ->leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
            ->leftJoin('packages', 'packages.id', '=', 'subscriptions.package_id')
            ->where('packages.status','!=','deleted')
            ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where(DB::raw('CONCAT_WS(users.name,users.email,users.phone)'), 'LIKE','%'.$searchValue.'%');
            })
            ->when($Package!='', function ($query) use ($Package) {
                $query->where('subscription_type',$Package);
            }) 
            ->when($company_name!='', function ($query) use ($company_name) {
                $query->where(DB::raw('COALESCE(buyer_companies.company_name,"")'), 'LIKE','%'.$company_name.'%');
            })
            ->when($user_type!='', function ($query) use ($user_type) {
                $query->where('user_type',$user_type);
            })
            ->where('users.status','<>','Deleted')
            ->distinct('subscriptions.user_id') 
            ->count();

        // Get records, also we have included search filter as well
        $records = Subscription::select('subscriptions.*','users.name as user_name','buyer_companies.company_name as company_name',
        'packages.user_type as user_type','packages.subscription_type')->where(function ($query) {
              $query ->where('subscriptions.status', '=', 'Active')
                     ->orWhere('subscriptions.status', '=', 'Suspended')->orWhere('subscriptions.status', '=', 'Expired');
           })
            ->leftJoin('users', 'users.id', '=', 'subscriptions.user_id')
            ->leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
            ->leftJoin('packages', 'packages.id', '=', 'subscriptions.package_id')   
            ->orderBy($columnName,$columnSortOrder)
            ->where('packages.status','!=','deleted')
            ->where('users.status','<>','Deleted')
            ->when($searchValue!='', function ($query) use ($searchValue) {
                 $query->where(DB::raw('CONCAT_WS(users.name,users.email,users.phone)'), 'LIKE','%'.$searchValue.'%');
            })
            ->when($Package!='', function ($query) use ($Package) {
                 $query->where('subscription_type',$Package);
            })  
            ->when($company_name!='', function ($query) use ($company_name) {
                 $query->where(DB::raw('COALESCE(buyer_companies.company_name,"")'), 'LIKE','%'.$company_name.'%');
            })
            ->when($user_type!='', function ($query) use ($user_type) {
                $query->where('user_type',$user_type);
            })
            ->latest()
            ->get()->unique('user_id');
            
        $records = $records->skip($start)
            ->take($rowperpage);
        $data_arr = array();
        foreach ($records as $record) {
                      $Enddate =  $record->expairy_date;
                      $Enddate =  Carbon::createFromFormat('Y-m-d', $Enddate);
                   
                  
          
           $status = 
           (
		   ( $record->status=='Active') ? '<span style="color:white;background-color:green;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.$record->status.'</span>':
		   (($Enddate->isPast() || $record->status=='Expired') ? '<span style="color:white;background-color:red;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">Expired</span>' :
                (($record->status=='Suspended') ? '<span style="color:white;background-color:red;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.$record->status.'</span>' :
                (($record->status=='Upgraded' || $record->status=='Renewed') ?'<span style="color:white;background-color:orange;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.$record->status.'</span>' :
                (($record->status=='In-Active') ? '<span style="color:white;background-color:purple;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.$record->status.'</span>' : ""
                ))))
				);
           $name=$record->user_name.'<br/> '.$status;
          
            $data_arr[] = array(
                "id" => $record->id,
                "user_id" => $record->user_id,
                "user_name" => $name,
                "subscription_type" => $record->subscription_type,
                "user_type" => $record->user_type,
                "company_name" => $record->company_name,
                "expairy_date" => date('d-m-Y', strtotime($record->expairy_date)),
                "date" => date('d-m-Y', strtotime($record->date)),
                "sub_status"=> $record->status,
                );
        }
    //return json data
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);       
}
//get all package list
    public function getpackagelist(Request $request)
    {  
         
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        //$searchValue = $search_arr['value']; // Search value
        $Package=$request->get('Package');
        $user_type=$request->get('user_type');
        $search_key=$request->get('search_key');
        //total count
        $totalRecords = Package::select('count(*) as allcount')->where('status','!=','deleted')->count();
        //total filtered count
        $totalRecordswithFilter = Package::select('count(*) as allcount')->where('status','!=','deleted')
            ->when($Package!='', function ($query) use ($Package) {
                $query->where('subscription_type',$Package);
            })  
            ->when($user_type!='', function ($query) use ($user_type) {
                $query->where('user_type',$user_type);
            })  
            ->when($search_key!='',function($query) use ($search_key) {
                $query->where('name','LIKE','%'.$search_key.'%');
            })
            ->count();

        // Get records, also we have included search filter as well
        $records = Package::select('packages.*',DB::raw("(SELECT count(*) FROM order_details
                WHERE order_details.package_id = packages.id group by  packages.id) as cnt"))
            ->orderBy($columnName,$columnSortOrder)
            ->where('status','!=','deleted')
            ->when($Package!='', function ($query) use ($Package) {
                $query->where('subscription_type',$Package);
            })  
            ->when($user_type!='', function ($query) use ($user_type) {
                $query->where('user_type',$user_type);
            })
            ->when($search_key!='',function($query) use ($search_key) {
                $query->where('name','LIKE','%'.$search_key.'%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
          
            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "subscription_type" => $record->subscription_type,
                "user_type" => $record->user_type,
                "package_basic_price" => $record->package_basic_price,
                "package_validity" => $record->package_validity,
                "cnt" => $record->cnt==''?0: $record->cnt,
                
               );
        }
        //json data
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);       
}
//display add form
    public function createPackage() {
        return view('admin.packages.addPackage');
    }
    
    //save new package data

    public function savePackage(Request $request) 
    {
       //data validation    
        request()->validate([
            'name' => ['required', 'string', 'max:255','unique:packages'],
            //'subscription_type' => ['required', 'string'],
            // 'no_of_accounts' => ['numeric'],
            // 'cost_per_account' => ['numeric','gt:per_account_offer_price'],
            // 'per_account_offer_price' => ['numeric','lt:cost_per_account'],
            // 'package_offer_price ' => ['numeric','lt:package_basic_price'], 
            'package_basic_price' => ['required'],
            //'user_type' =>['required'],
            'package_description' => ['required', 'string'],
        ]);
        $input = [
            'name' => $request->get('name'),
            'subscription_type' => $request->get('subscription_type')??'',
            'user_type' => $request->get('user_type')??'',
            'package_basic_price' => $request->get('package_basic_price')??'',
            'package_offer_price' => $request->get('package_offer_price')??'',
            'package_validity' => $request->get('package_validity')??'',
            'package_description' => $request->get('package_description')??'',
            'general_companyProfile' => $request->get('general_companyProfile')??'',
            'general_companyDashboard' => $request->get('general_companyDashboard')??'',
            'general_profileInsights' => $request->get('general_profileInsights')??'',
            'general_accesToMedia' => $request->get('general_accesToMedia')??'',
            'market_Search' => $request->get('market_Search')??'',
            'market_uploads' => $request->get('market_uploads')??'',
            'market_productRequests' => $request->get('market_productRequests')??'',
            'market_createProductRequests' => $request->get('market_createProductRequests')??'',
            'network_expand' => $request->get('network_expand')??'',
            'network_chat' => $request->get('network_chat')??'',
            'display_order' => $request->get('display_order')??'',
        ];
        //  save  $input;
        $id = Package::create( $input )->id;
        //save extra features,  removed on 22 Dec {
        // foreach ($request->addMoreInputFields as  $value) {
        //     $included_features = $value;
        //     $included_features = array_merge($included_features, ['package_id' => $id]);
        //     IncludedFeature::insert($included_features);
        // }
        // foreach ($request->addMoreInputFieldsExcluded as  $value) {
        //     $excluded_features = $value;
        //     $excluded_features = array_merge($excluded_features, ['package_id' => $id]);
        //     ExcludedFeatures::insert($excluded_features);
        // }
        //save extra features,  removed on 22 Dec }
        $counter = 0;
        if($request->get('no_of_accounts')) {
            foreach($request->get('no_of_accounts') as $value){
   //save no of account            

    if (!is_null($request->no_of_accounts[$counter])) {
                PackageAccount::create([
                    'package_id'=>$id,
                    'no_of_accounts' =>$request->no_of_accounts[$counter],
                    'cost_per_account' =>$request->cost_per_account[$counter],
                    'per_account_offer_price' =>$request->per_account_offer_price[$counter],
                ]);
                $counter++;
            }
        }
        }

        return redirect()->route('list.packges')->with('message','Package Added Successfully');
    }
//delete package
    public function deletePackage($packageId)
    {
        $package=  Package::find($packageId);
        if(empty($package)){ 
            return redirect()->route('list.packges')->with('message', 'Package not Exists');
        }
        $update = $package->update(['status'=>'deleted']);
        return redirect()->route('list.packges')->with('message', 'Package Deleted!');
    }
    //edit package
    public function editPackage($packageId)
    {
        $package = Package::find($packageId);
        return view('admin.packages.edit-package',compact('package'));
    }
//update package
    public function updatePackage(Request $request) {
        //data validation
        request()->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('packages')->ignore($request->package_id)],
            //'subscription_type' => ['required', 'string'],
            // 'no_of_accounts' => ['numeric'],
            // 'cost_per_account' => ['numeric','gt:per_account_offer_price'],
            // 'per_account_offer_price' => ['numeric','lt:cost_per_account'],
           // 'package_offer_price ' => ['numeric','lt:package_basic_price'], 
            'package_basic_price' => ['required'],
           //'user_type' =>['required'],
            'package_description' => ['required', 'string'],            
            
        ]);

        $packageId = $request->get('package_id');
        //fetch package data
        $package = Package::find($packageId);
        $input = [
            'name' => $request->get('name')??'',
            'subscription_type' => $request->get('subscription_type')??'',
            'user_type' => $request->get('user_type')??'',
            'package_basic_price' => $request->get('package_basic_price')??'',
            'package_offer_price' => $request->get('package_offer_price')??'',
            'package_validity' => $request->get('package_validity')??'',
            'package_description' => $request->get('package_description')??'',
            'general_companyProfile' => $request->get('general_companyProfile')??'',
            'general_companyDashboard' => $request->get('general_companyDashboard')??'',
            'general_profileInsights' => $request->get('general_profileInsights')??'',
            'general_accesToMedia' => $request->get('general_accesToMedia')??'',
            'market_Search' => $request->get('market_Search')??'',
            'market_uploads' => $request->get('market_uploads')??'',
            'market_productRequests' => $request->get('market_productRequests')??'',
            'market_createProductRequests' => $request->get('market_createProductRequests')??'',
            'network_expand' => $request->get('network_expand')??'',
            'network_chat' => $request->get('network_chat')??'',
            'display_order' => $request->get('display_order')??'',
        ];
        //update data
        $package->update($input);
        
        //update included features, removed on 22 Dec {
        // DB::table('included_features')->where('package_id', $packageId)->delete();
        // DB::table('excluded_features')->where('package_id', $packageId)->delete();
        // if(!empty($request->addMoreInputFields))
        // { 
        //     foreach ($request->addMoreInputFields as  $value) {
        //     $included_features = $value;
        //     $included_features = array_merge($included_features, ['package_id' => $packageId]);
        //     IncludedFeature::insert($included_features);
        //     }
        // }
        // //update excludeded features
        // if(!empty($request->addMoreInputFieldsExcluded))
        // { 
        //     foreach ($request->addMoreInputFieldsExcluded as  $value) {
        //     $excluded_features = $value;
        //     $excluded_features = array_merge($excluded_features, ['package_id' => $packageId]);
        //     ExcludedFeatures::insert($excluded_features);
        //      }
        // } 
        //update included features, removed on 22 Dec }
        $counter = 0;
    DB::table('package_accounts')->where('package_id', $packageId)->delete();    
    if($request->get('no_of_accounts')) {
    foreach($request->get('no_of_accounts') as $value){
     if (!is_null($request->no_of_accounts[$counter])) { 
                PackageAccount::create([
                    'package_id'=>$packageId,
                    'no_of_accounts' =>$request->no_of_accounts[$counter],
                    'cost_per_account' =>$request->cost_per_account[$counter],
                    'per_account_offer_price' =>$request->per_account_offer_price[$counter],
                ]);
                $counter++;
            }
        }
    }
        return redirect()->route('list.packges')->with('message','Package Updated');
    }
    //display package details
    public function viewPackage($packageId){
        $package = Package::find($packageId); 
          if(empty($package)) {
                return redirect()->route('package.list')->with('message','No Package Found');
            } 
          return view('admin/packages/view-package',compact('package'));  
        }
    //delete package delete
    public function packageAccount(Request $request)
    { 
        
        
        PackageAccount::find($request->id)->delete();
        $message = "Successfully deleted";
        $ajax_status = 'success';
        $return_array = array('ajax_status'=>$ajax_status,'message' =>$message );
        return response()->json($return_array);
    }
    //ajax call get all subscrptionlist
    public function getuserssubscriptionlist(Request $request)
    {  
        
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc       
        $searchValue=trim($request->get('search_key'));
        $user_id=$request->get('user_id');
        $totalRecords =Subscription::leftJoin('users', 'users.id', '=', 'subscriptions.user_id')
            ->select('count(*) as allcount')
            ->where('users.id',$user_id)->count();
        
        $totalRecordswithFilter = Subscription::leftJoin('users', 'users.id', '=', 'subscriptions.user_id')
            ->leftJoin('order_details', 'subscriptions.order_id', '=', 'order_details.id')
            ->leftJoin('packages', 'packages.id', '=', 'order_details.package_id')
            ->when($searchValue!='', function ($query) use ($searchValue) {
                 $query->where('packages.name','Like','%'.$searchValue.'%') ;
            })
            ->where('users.id',$user_id)
            // ->when($request->get('package')!='', function ($query) use ($request) {
             //     $query->where('order_details.package_id',$request->get('package'));
            // })
            ->count();       

        // Get records, also we have included search filter as well
        $records = Subscription::leftJoin('order_details', 'subscriptions.order_id', '=', 'order_details.id')
            ->leftJoin('users', 'users.id', '=', 'order_details.user_id')
            ->leftJoin('packages', 'packages.id', '=', 'order_details.package_id')
            ->orderBy($columnName,$columnSortOrder) 
            ->when($searchValue!='', function ($query) use ($searchValue) {
                 $query->where('packages.name','Like','%'.$searchValue.'%') ;
            })
            ->where('users.id',$user_id)  
            // ->when($request->get('package')!='', function ($query) use ($request) {
            //     $query->where('order_details.package_id',$request->get('package'));
            // })  
            ->select('order_details.*',DB::raw("users.name as users_name")
            ,DB::raw("packages.name as package_name"),
            DB::raw("(CASE WHEN subscriptions.order_id > 0 THEN 'Yes' ELSE 'No' END)  as anyval"),
            DB::raw("users.created_at as created_date"),
            DB::raw("packages.subscription_type as subscription_type")
            ,DB::raw("packages.package_basic_price as package_basic_price"),
            DB::raw("packages.package_validity as package_validity"),
            DB::raw("packages.user_type as user_type")
            ,DB::raw("subscriptions.expairy_date as expairy_date"),
            'subscriptions.id as subs_id','subscriptions.order_total as ord_total','subscriptions.status as sub_status')    
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        $last_package='';   
        foreach ($records as $record) {
            $Enddate =  $record->expairy_date;
            $Enddate =  Carbon::createFromFormat('Y-m-d', $Enddate);
            $status = 
           (
		   ( $record->sub_status=='Active') ? '<span style="color:white;background-color:green;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.$record->sub_status.'</span>':
		   (($Enddate->isPast() || $record->sub_status=='Expired') ? '<span style="color:white;background-color:red;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">Expired</span>' :
                (($record->sub_status=='Suspended') ? '<span style="color:white;background-color:red;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.$record->sub_status.'</span>' :
                (($record->sub_status=='Upgraded' || $record->status=='Renewed') ?'<span style="color:white;background-color:orange;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.$record->sub_status.'</span>' :
                (($record->sub_status=='In-Active') ? '<span style="color:white;background-color:purple;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.$record->sub_status.'</span>' : ""
                ))))
				);
				$name=$record->package_name.'<br/> '.$status;
             if($last_package=='')
                $last_package=$record->id;
                $data_arr[] = array(
                "id" => $record->id,
                "package_name" => $name,
                "package_basic_price" => $record->package_basic_price,
                "created_date" => date('d-m-Y', strtotime($record->created_date)),
                "expairy_date" =>  date('d-m-Y', strtotime($record->expairy_date)),
                "subs_id" =>  $record->subs_id, 
                "sub_status" => $record->sub_status,
                "ord_total" =>  $record->ord_total,
                "last_package"=>$last_package
                );
             
         }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);       
    }

    public function subscriberslist(){
       
    }
    //fetch expiry date
    public function extendexpirydate(Request $request){
        
       $input = ['expairy_date' => $request->get('Extend_date')];
       $count=Subscription::where('id',$request->get('order_id'))->update($input);
       return $count;

   }
    //send email to user
   public function senderRemainder($id){

            $user_data=Subscription::leftJoin('order_details', 'subscriptions.order_id', '=', 'order_details.id')
                ->leftJoin('users', 'users.id', '=', 'order_details.user_id')
                ->leftJoin('packages', 'packages.id', '=', 'order_details.package_id')
                ->where('subscriptions.id',$id)
                ->select('users.*','packages.name as package_name','subscriptions.expairy_date')
                ->first();
          $expiry_date=date("d-m-Y", strtotime($user_data->expairy_date));
// email sending
            Mail::send('admin/packages/renew-expiry-template', ['name' => $user_data->name, 'email' => 
            $user_data->email, 'expairy_date' =>  $expiry_date] ,function ($message) use ($user_data) {
                     $message->from("example@gmail.com",'Fmcg');
                     $message->to($user_data->email,$user_data->name)
                     ->subject('Fmcg Package Renewal Notification');
                 });
             return back()->with('message','Remainder Send successfully!');
    }
   
}

