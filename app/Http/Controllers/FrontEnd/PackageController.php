<?php
namespace App\Http\Controllers\FrontEnd;
use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use App\Models\Subscription;
use Carbon\Carbon;
use App\Models\Package;
use App\Models\PackageAccount;
use Session; 
use App\Models\Country;
use App\Models\StripeStatus;
use DB;
use Illuminate\Support\Facades\Auth; 
//use Request;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;
use App\Http\Controllers\FrontEnd\PublicMiddlewareController;
class PackageController extends Controller
{
    protected $PublicMiddlewareController;
    public function __construct(
        PublicMiddlewareController $PublicMiddlewareController
    ) {
        $this->PublicMiddlewareController = $PublicMiddlewareController;
    }
    function isMobile() {
    if(isset($_SERVER['HTTP_USER_AGENT'])) {
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    if(preg_match('/(tablet|ipad|amazon|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($useragent))) {
        return true ;
    } ;

    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
            return true ;
        }
    }
    return 0 ;
}
    public function PackageListing(Request $request)
    {
        if (Auth::guard("user")->check()) {
            if (!$this->PublicMiddlewareController->checkUserlogin()) {
                Auth::guard("user")->logout();
                return redirect(route("home"))->with(
                    "message",
                    "Access Denied"
                );
            }
            if (Auth::guard("user")->user()->seller_type == "Co-Seller") {
                return redirect(route("home"));
            }
        }
        if (Session::get("order_type") != "Upgraded") {  //previous discount only for upgrade package
            Session::forget("last_oreder_total");
        }
        $user_id = Auth::guard("user")->user()->id;
        $userType = Auth::guard("user")->user()->usertype;
        
        $expairy_date = $packagePrice = '';
        


        
        $old_subscription = Subscription::where("user_id", $user_id)
              //->where("package_id", $old_pkg_id)
                ->orderBy("id", "DESC")
                ->first();
        if(!is_null($old_subscription))   
        {
        $expairy_date = $old_subscription->expairy_date;
        $expairy_date =  Carbon::createFromFormat('Y-m-d', $expairy_date);
        $packagePrice = $old_subscription->Package->package_basic_price;
        $old_pkg_id = $old_subscription->package_id;
        }       
        
        $pkg_type = $request->get("pkg_type"); //
        $query = Package::where("status", "!=", "deleted");
       /* if ($pkg_type == "seller") {
            $query = $query->where("user_type", "Seller");
        } elseif ($pkg_type == "buyer") {
            $query = $query->where("user_type", "Buyer");
        } */
        if(!is_null($old_subscription) && $expairy_date->isFuture() ) { 
            
        $query = $query->where("package_basic_price", ">", $packagePrice);
       /* $query->when($userType == "seller", function ($q) use ($packagePrice) {
                return $q
                    ->where("package_basic_price", ">", $packagePrice)
                    ->where("user_type", "Seller")
                    ->where("status", "!=", "deleted");
            });
            $query->when($userType == "buyer", function ($q) use ($packagePrice) {
                return $q
                    ->where("user_type", "Seller")
                    ->orwhere("user_type", "Buyer")
                    ->where("package_basic_price", ">", $packagePrice)
                    ->where("status", "!=", "deleted");
            });
            $query->where("id", "!=", $old_pkg_id);*/
        }
        $packages = $query->orderBy('display_order','asc') ->get();
        $stripe_status = StripeStatus::pluck('status')->first();
        
        if($this->isMobile()) { 
	    return view("frontEnd.profile-creation.listPackages_mobile",compact("packages","stripe_status"));
        } else {
          return view("frontEnd.profile-creation.listPackages",compact("packages","stripe_status"));
    }
    }
    //package detail page
    public function PackageDetails(Request $request)
    {
        if ($request->get("extended") == "yes") {
            $this->validate(
                $request,
                ["accounts_id" => "required"],
                ["required" => "Please choose number of profiles"]
            );
        }
        $packageId = $request->get("package_id");
        $accounts_id = $request->get("accounts_id");
        $order_type = $old_pkg_id = "";
        if (!empty(Session::get("order_type"))) {
            $order_type = Session::get("order_type");
        }
        if (!empty(Session::get("old_pkg_id"))) {
            $old_pkg_id = Session::get("old_pkg_id");
        }
        $package = Package::find($packageId);
        if (empty($package)) {
            return redirect()
                ->route("package.listing")
                ->with("message", "No Package Found");
        }
        $lowest = Package::orderBy("package_basic_price", "asc")
            ->where("status", "!=", "deleted")
            ->where("id", "!=", $packageId)
            ->first(); //lowext price package at right side
        
        
         if($this->isMobile()) { 
             return view("frontEnd.profile-creation.packageDetails_mobile",compact("package", "lowest", "accounts_id"));
             
            } else {
          return view("frontEnd.profile-creation.packageDetails",compact("package", "lowest", "accounts_id"));
            }
    }
    //create invoice
    public function PackgeInvoice(Request $request)
    {
        $packageId = $request->get("package_id");
        $subscription_id = $request->get("subscription_id");
        $subscription = Subscription::find($subscription_id);
        $package = Package::find($packageId);
        if (empty($package)) {
            return redirect()
                ->route("package.listing")
                ->with("message", "No Package Found");
        }
        
        if($this->isMobile()) { 
           return view("frontEnd.profile-creation.packageInvoice_mobile",compact("package", "subscription"));
        } else {
            return view("frontEnd.profile-creation.packageInvoice",compact("package", "subscription"));
        }
    }
    public function prnpriview(Request $request)
    {
        
        $packageId = $request->get("package_id"); 
        $subscription_id = $request->get("subscription_id");
        $subscription = Subscription::find($subscription_id);
        $package = Package::find($packageId);
        if (empty($package)) {
            return redirect()
                ->route("package.listing")
                ->with("message", "No Package Found");
        }
        
        return view("frontEnd.profile-creation.printSubsription",compact("package", "subscription"));
       
    }
    public function Cart()
    {
           if($this->isMobile()) { 
               return view("frontEnd.profile-creation.Cart_mobile");
            } else {
              return view("frontEnd.profile-creation.Cart");
            }
    }
    public function SubscriptionCheckout(Request $request)
    {
        $countries = Country::all();
        $package_id = $request->pkg_id;
        $accounts_id = $request->accounts_id;
        $accounts = PackageAccount::where("id", $accounts_id)->first();
        $package = Package::where("id", $package_id)->first();
        
         if($request->order_type=='Renew')
        {
        Session::forget("last_oreder_total");
        Session::put("old_pkg_id", $request->get("old_pkg_id"));
        Session::put("order_type", $request->get("order_type"));
        }
        
        
        if($this->isMobile()) { 
            return view("frontEnd.profile-creation.subscriptionCheckout_mobile",compact( "countries", "package","accounts"));
        } else {
             return view("frontEnd.profile-creation.subscriptionCheckout",compact( "countries", "package","accounts"));
        }
        
    }
    public function submitCheckout(Request $request)
    {
        $user_id = Auth::guard("user")->user()->id;
        request()->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [ "required", "string",  "email", "regex:/(.+)@(.+)\.(.+)/i",  "max:255",  ],
            "phone" => ["required", "string"],
            "address" => ["required", "string"],
            "city" => ["required", "string"],
            //"zip" => ["required", "numeric", "max-digits:6"],
             "zip" => ["required"],
          //  "card_month" => 'numeric|max:12',
          //  "card_year" => 'numeric|digits:4|min:2022',
          //  "card_cvc" => 'numeric|digits:3',
            "country" => ["required"],
        ]);
		$package_id = $request->get("package_id");
        $accounts_id = $request->get("accounts_id");
		$order_type = $old_pkg_id = "";
        if (!empty(Session::get("order_type"))) {
            $order_type = Session::get("order_type");
        }
        if (!empty(Session::get("old_pkg_id"))) {
            $old_pkg_id = Session::get("old_pkg_id");
        }
        
        Session::put("user_checkout_details", [
            "name" => $request->get("name"),
            "accounts_id" => $accounts_id,
            "order_type" => $order_type,
            "package_id" => $package_id,
            "user_id" => $user_id,
            "email" => $request->get("email"),
            "phone" => $request->get("phone"),
            "address" => $request->get("address"),
            "city" => $request->get("city"),
            "zip" => $request->get("zip"),
           // "card_number" => $request->get("card_number"),
           // "card_month" => $request->get("card_month"),
            //"card_year" => $request->get("card_year"),
           // "card_cvc" => $request->get("card_cvc"),
            "package_id" => $request->get("package_id"),
        ]);
        
        $accounts = PackageAccount::where("id", $accounts_id)->first();
        $package = Package::where("id", $package_id)->first();
        if (!empty($old_pkg_id) || !empty($order_type)) {
            $old_subscription = Subscription::where("user_id", $user_id)
                ->where("package_id", $old_pkg_id)
                ->orderBy("id", "DESC")
                ->first(); //prev subscription detail to calculate exp dt
           
				 
           
        }
        $auto_renewal = $old_subscription->auto_renewal ?? '1';
        if($this->isMobile()) { 
             return view("frontEnd.profile-creation.Cart_mobile",compact("package", "accounts", "auto_renewal"));
        } else {
             return view("frontEnd.profile-creation.Cart",compact("package", "accounts", "auto_renewal"));
        }
    }
     public function submitCart(Request $request)
    {
        if ($request->get("extended") == "yes") {
            $this->validate(
                $request,
                ["accounts_id" => "required"],
                ["required" => "Please choose number of profiles"]
            );
        }
        $user_id = Auth::guard("user")->user()->id;
        $user = User::find($user_id);
      
        $order_type = $old_pkg_id = "";
	//	$auto_renewal = 1;
		/////////////direct/////
		$package_id = $request->get("package_id");
        $accounts_id = $request->get("accounts_id");
        $order_type = $old_pkg_id = "";
        
        
        if($request->get("order_type")=="Renew")
        {
          Session::put("old_pkg_id", $request->get("old_pkg_id"));
          Session::put("order_type", $request->get("order_type"));
          $old_pkg_id = $request->get("old_pkg_id");
          $order_type =  $request->get("order_type");
          $packageId = $request->get("package_id");
        }
        
        
        else{
            if (!empty(Session::get("order_type"))) {
             $order_type = Session::get("order_type");
             }
            if (!empty(Session::get("old_pkg_id"))) {
                $old_pkg_id = Session::get("old_pkg_id");
            }
        }
        
        
        
        
        
        
        
        
        
        
        
        
        $auto_renewal = 1;
        $accounts = PackageAccount::where("id", $accounts_id)->first();
        $package = Package::where("id", $package_id)->first();
        if (!empty($old_pkg_id) || !empty($order_type)) {
            $old_subscription = Subscription::where("user_id", $user_id)
                ->where("package_id", $old_pkg_id)
                ->orderBy("id", "DESC")
                ->first(); //prev subscription detail to calculate exp dt
            if (!empty($old_subscription->auto_renewal)) {
				 $auto_renewal = $old_subscription->auto_renewal;
            }
        } 
        $no_of_accounts = 0;
        if (!empty($accounts_id)) {
            $no_of_accounts = DB::table("package_accounts")
                ->where("id", $accounts_id)
                ->pluck("no_of_accounts")
                ->first();
        }
		Session::put("user_checkout_details", [
            "name" => $user->name,
            "accounts_id" => $accounts_id,
            "order_type" => $order_type,
            "package_id" => $package_id,
            "user_id" => $user_id,
            "email" => $user->email,
            "phone" => $user->phone,
            "address" => $user->address,
            "no_of_accounts" => $no_of_accounts,
            "city" => $user->city,
            "zip" => $user->zip,
            "package_id" => $request->get("package_id"),
        ]);
		/////////////////end direct///////////////////////
		$old_subscription = Subscription::where("user_id", $user_id)
                ->where("package_id", Session::get("old_pkg_id"))
                ->orderBy("id", "DESC")
                ->first(); //prev subscription detail to calculate exp dt
        if (!empty(Session::get("order_type"))) {
            $order_type = Session::get("order_type");
        }
     if(session()->has('user_checkout_details')) {
            $order_id = OrderDetail::create(Session::get("user_checkout_details"))
            ->id;
       }
     else {
        return redirect()->route('subscription.details'); 
     } 
        $package = Package::where("id", $package_id)->first(); 
        $order_total=0;
		$order_total= $package->package_offer_price ? $package->package_offer_price : $package->package_basic_price	;
        $input = [
            "user_id" => $user_id,
            "package_id" => $package_id,
            "type" => $package->user_type,
            "date" => Carbon::today(),
            "order_id" => $order_id,
            "order_total" => $order_total,
         // 'auto_renewal' => $request->get('auto_renewal'),
            "auto_renewal" => 1,
			"status" => "Active",
        ];
		$package_validity = $EndDate = "";
		// if(!empty($package->package_validity))
        // $package_validity = $package->package_validity;
		// if ($package_validity == "3 months") {
        //         $EndDate = Carbon::now()->addMonths(3);
        //     } //add 3 months from date of purchase
        //     if ($package_validity == "6 months") {
        //         $EndDate = Carbon::now()->addMonths(6);
        //     } //add 6 months from date of purchase
        //     if ($package_validity == "One year") {
        //         $EndDate = Carbon::now()->addMonths(12);
        //     } //add 12 months from date of purchase

            // New subscription options
			$package_validity = 1;
            $EndDate = Carbon::now()->addMonths(1);

			$updt_old = DB::table("subscriptions")
                ->where("user_id", $user_id)
                ->where("package_id", Session::get("old_pkg_id"))
                ->orderBy("id", "desc")
                ->take(1);
        if ($order_type == "Renew") {
            Session::forget("last_oreder_total");
            $updt_old->update(["status" => "Renewed"]);
            $id = $old_subscription->id;
           //  $auto_renewal = $old_subscription->auto_renewal;
            $current_endDate = Carbon::createFromFormat("Y-m-d",$old_subscription->expairy_date);
            $input["expairy_date"] = $EndDate->toDateString(); // carbon date format to noramal date
            if (!$current_endDate->isPast()) {
                //active packages
                // if ($package_validity == "3 months") {
                //     $EndDate1 = $current_endDate->addMonths(3);
                // }
                // if ($package_validity == "6 months") {
                //     $EndDate1 = $current_endDate->addMonths(6);
                // }
                // if ($package_validity == "One year") {
                //     $EndDate1 = $current_endDate->addMonths(12);
                // }
                $EndDate1 = $current_endDate->addMonths(1);
                $input["expairy_date"] = $EndDate1->toDateString(); // carbon date format to noramal date
            }
            
            $input["auto_renewal"] = $auto_renewal;
            
            Subscription::create($input);
        } 
		elseif ($order_type == "Upgrade") {
            $updt_old->update(["status" => "Upgraded"]);
            //$auto_renewal = $old_subscription->auto_renewal;
            $input["expairy_date"] = $EndDate; //
            $input["auto_renewal"] = $auto_renewal;
            
            Subscription::create($input);
        } 
		elseif ($order_type == "Expired") {
            Session::forget("last_oreder_total");
			$updt_old->update(["status" => "Expired"]);
			$input["expairy_date"] = $EndDate; //
            //  $auto_renewal = $old_subscription->auto_renewal;
             //$input["auto_renewal"] = $auto_renewal;
            Subscription::create($input);
        } else {
            Session::forget("last_oreder_total");
            $input["expairy_date"] = $EndDate;
            $input["auto_renewal"] = $auto_renewal;
            Subscription::create($input);
        }
        //$usertype = Package::find($package_id)->user_type;
        $usertype = 'seller';
        $input = [
            "usertype" => $usertype,
        ];
        Session::forget("old_pkg_id");
        Session::forget("order_type");
        Session::forget("last_oreder_total");
        Session::forget("user_checkout_details");
        DB::table("users")
            ->where("id", $user_id)
            ->update($input);
            
        return redirect()->route("order.success");
    }
  /*  public function submitCart(Request $request)
    {
        $user_id = Auth::guard("user")->user()->id;
        $package_id = $request->get("package_id");
        $order_type = $old_pkg_id = "";
		$auto_renewal = 1;
		$old_subscription = Subscription::where("user_id", $user_id)
                ->where("package_id", Session::get("old_pkg_id"))
                ->orderBy("id", "DESC")
                ->first(); //prev subscription detail to calculate exp dt
        if (!empty(Session::get("order_type"))) {
            $order_type = Session::get("order_type");
        }
     if(session()->has('user_checkout_details')) {
            $order_id = OrderDetail::create(Session::get("user_checkout_details"))
            ->id;
       }
     else {
        return redirect()->route('subscription.details'); 
     } 
        $package = Package::where("id", $package_id)->first();
        $input = [
            "user_id" => $user_id,
            "package_id" => $package_id,
            "type" => $package->user_type,
            "date" => Carbon::today(),
            "order_id" => $order_id,
            "order_total" => $request->get("order_total"),
         // 'auto_renewal' => $request->get('auto_renewal'),
            "auto_renewal" => 1,
			"status" => "Active",
        ];
		$package_validity = $EndDate = "";
		if(!empty($package->package_validity))
        $package_validity = $package->package_validity;
		if ($package_validity == "3 months") {
                $EndDate = Carbon::now()->addMonths(3);
            } //add 3 months from date of purchase
            if ($package_validity == "6 months") {
                $EndDate = Carbon::now()->addMonths(6);
            } //add 6 months from date of purchase
            if ($package_validity == "One year") {
                $EndDate = Carbon::now()->addMonths(12);
            } //add 12 months from date of purchase
			
			$updt_old = DB::table("subscriptions")
                ->where("user_id", $user_id)
                ->where("package_id", Session::get("old_pkg_id"))
                ->orderBy("id", "desc")
                ->take(1);
        if ($order_type == "Renew") {
            Session::forget("last_oreder_total");
            $updt_old->update(["status" => "Renewed"]);
            $id = $old_subscription->id;
           //  $auto_renewal = $old_subscription->auto_renewal;
            $current_endDate = Carbon::createFromFormat("Y-m-d",$old_subscription->expairy_date);
            $input["expairy_date"] = $EndDate->toDateString(); // carbon date format to noramal date
            if (!$current_endDate->isPast()) {
                //active packages
                if ($package_validity == "3 months") {
                    $EndDate1 = $current_endDate->addMonths(3);
                }
                if ($package_validity == "6 months") {
                    $EndDate1 = $current_endDate->addMonths(6);
                }
                if ($package_validity == "One year") {
                    $EndDate1 = $current_endDate->addMonths(12);
                }
                $input["expairy_date"] = $EndDate1->toDateString(); // carbon date format to noramal date
            }
            
            $input["auto_renewal"] = $auto_renewal;
            Subscription::create($input);
        } 
		elseif ($order_type == "Upgrade") {
            $updt_old->update(["status" => "Upgraded"]);
            //$auto_renewal = $old_subscription->auto_renewal;
            $input["expairy_date"] = $EndDate; //
            $input["auto_renewal"] = $auto_renewal;
            Subscription::create($input);
        } 
		elseif ($order_type == "Expired") {
            Session::forget("last_oreder_total");
			$updt_old->update(["status" => "Expired"]);
			$input["expairy_date"] = $EndDate; //
            //  $auto_renewal = $old_subscription->auto_renewal;
             //$input["auto_renewal"] = $auto_renewal;
            Subscription::create($input);
        } else {
            Session::forget("last_oreder_total");
            $input["expairy_date"] = $EndDate;
            $input["auto_renewal"] = $auto_renewal;
            Subscription::create($input);
        }
        $usertype = Package::find($package_id)->user_type;
        $input = [
            "usertype" => $usertype,
        ];
        Session::forget("old_pkg_id");
        Session::forget("order_type");
        Session::forget("last_oreder_total");
        Session::forget("user_checkout_details");
        DB::table("users")
            ->where("id", $user_id)
            ->update($input);
        return redirect()->route("order.success");
    }*/
    public function OrderSuccess(Request $request)
    {
        if($this->isMobile()) { 
           return view("frontEnd.profile-creation.orderSuccess_mobile");
        } else {
           return view("frontEnd.profile-creation.orderSuccess");
        }
    }
    public function SubscriptionDetails()
    {
        Session::forget("order_type");
        if (Auth::guard("user")->check()) {
            if (!$this->PublicMiddlewareController->checkUserlogin()) {
                Auth::guard("user")->logout();
                return redirect(route("home"))->with(
                    "message",
                    "Access Denied"
                );
            }
            if (
                !$this->PublicMiddlewareController->checkUserValidity() && Auth::guard("user")->user()->seller_type == "Co-Seller"
            ) {
                Auth::guard("user")->logout();
                return redirect(route("home"));
            }
        }
        if (Auth::guard("user")->user()->seller_type == "Co-Seller") {
            return redirect()->route("home");
        }
        $userId = Auth::guard("user")->user()->id;
       // $user = User::find($userId);
        $subscriptions = Subscription::where("user_id", $userId)
            ->orderBy("id", "DESC")
            ->get();

        $highestPackage = Package::where("status", 'Active')->orderBy("package_basic_price", "DESC")->first();
        
        if($this->isMobile()) { 
	    return view( "frontEnd.profile-creation.SubscriptionDetails_mobile",compact("subscriptions") );
        } else {
          return view( "frontEnd.profile-creation.SubscriptionDetails",compact("subscriptions","highestPackage") );
    }
    }
    public function UpgradePackage(Request $request)
    {
        $order_type = $request->get("order_type");
        $old_pkg_id = $request->get("old_pkg_id");
        Session::put("old_pkg_id", $request->get("old_pkg_id"));
        Session::put("order_type", $request->get("order_type"));
        $package_validity = DB::table("packages")
            ->where("id", $old_pkg_id)
            ->pluck("package_validity")
            ->first();
        // if ($package_validity == "One year") {
        //     $tot_months = 12;
        // }
        // if ($package_validity == "6 months") {
        //     $tot_months = 6;
        // }
        // if ($package_validity == "3 months") {
        //     $tot_months = 3;
        // }
        $tot_months = 1;
        $userId = Auth::guard("user")->user()->id;
        $user = User::find($userId);
        //$orders = $user->Subscription; //orders  of current user
        
        $last_oreder_total = $user->Subscription
            ->where("package_id", $old_pkg_id)
            ->pluck("order_total")
            ->last();
        $startDate = $user->Subscription
            ->where("package_id", $old_pkg_id)
            ->pluck("date")
            ->last();
       /* $endDate = $user->Subscription
            ->where("package_id", $old_pkg_id)
            ->pluck("expairy_date")
            ->last();*/
        $today = Carbon::parse(date("Y-m-d"));
        $startDate = Carbon::parse($startDate);
        $days_used = $startDate->diffInDays($today);
        $used_months = ceil($days_used / 30.5);
        $remaining_percentage = ($tot_months - $used_months) / $tot_months;
        $remaining_amount = round($last_oreder_total * $remaining_percentage,2);
        Session::put("last_oreder_total", $remaining_amount);
        $packagePrice = $request->get("package_basicPrice");
        //$package_ids = $orders->pluck("package_id")->all(); //all package ids purchased
        $userType = Auth::guard("user")->user()->usertype;
        /*$packages = Package::select("*")
            ->where("status", "!=", "deleted")
            ->when($userType == "seller", function ($q) use ($packagePrice) {
                return $q
                    ->where("package_basic_price", ">", $packagePrice)
                    ->where("user_type", "Seller")
                    ->where("status", "!=", "deleted");
            })
            ->when($userType == "buyer", function ($q) use ($packagePrice) {
                return $q
                    ->where("user_type", "Seller")
                    ->orwhere("user_type", "Buyer")
                    ->where("package_basic_price", ">", $packagePrice)
                    ->where("status", "!=", "deleted");
            })
            ->where("id", "!=", $old_pkg_id)
            //->whereNotIn('id', $package_ids)
            ->get();*/
            
            $packages = Package::select("*")
            ->where("status", "!=", "deleted")
            
                    ->where("package_basic_price", ">", $packagePrice)
                    ->where("status", "!=", "deleted")
                    ->where("id", "!=", $old_pkg_id)
            //->whereNotIn('id', $package_ids)
            ->orderBy('display_order','asc') ->get();
            $stripe_status = StripeStatus::pluck('status')->first();
        
        if($this->isMobile()) { 
	    return view("frontEnd.profile-creation.listPackages_mobile",compact("packages", "packagePrice", "stripe_status"));
        } else {
          return view("frontEnd.profile-creation.listPackages",compact("packages", "packagePrice", "stripe_status"));
    }
    }
    public function RenewPackage(Request $request)
    { 
        $user_id = Auth::guard("user")->user()->id;
        Session::forget("last_oreder_total");
        Session::put("old_pkg_id", $request->get("old_pkg_id"));
        Session::put("order_type", $request->get("order_type"));
        $packageId = $request->get("package_id");
        $accounts_id = $request->get("accounts_id");
        $accounts = PackageAccount::where("id", $accounts_id)->first();
        $no_of_accounts = 0;
        if (!empty($accounts_id)) {
            $no_of_accounts = DB::table("package_accounts")
                ->where("id", $accounts_id)
                ->pluck("no_of_accounts")
                ->first();
        }
        $package = Package::where("status", "!=", "deleted")->find($packageId);
        Session::put("user_checkout_details", [
            "name" => Auth::guard("user")->user()->name,
            "accounts_id" => $request->get("accounts_id"),
            "order_type" => $request->get("order_type"),
            "no_of_accounts" => $no_of_accounts,
            "package_id" => $request->get("package_id"),
            "user_id" => $user_id,
            "email" => Auth::guard("user")->user()->email, 
        ]);
        if (empty($package)) {
            return redirect()
                ->route("package.listing")
                ->with("message", "No Package Found");
        }
        $lowest = Package::where("status", "!=", "deleted")
            ->orderBy("package_basic_price", "asc")
            ->where("id", "!=", $packageId)
            ->first(); //lowest price package at right side
			$stripe_status = StripeStatus::pluck('status')->first(); 
			if($stripe_status=="Enabled"){
            
             if($this->isMobile()) { 
                 return view("frontEnd.profile-creation.packageDetails_mobile",compact("package","lowest","accounts_id","accounts","stripe_status"));
                 } else {
                 return view("frontEnd.profile-creation.packageDetails",compact("package","lowest","accounts_id","accounts","stripe_status"));
              }
			}
		    else
		    { 
		        if($this->isMobile()) { 
	              return view("frontEnd.profile-creation.packageDetails1_mobile",compact("package","lowest","accounts_id","accounts","stripe_status"));
                 } else { 
                  return view("frontEnd.profile-creation.packageDetails1",compact("package","lowest","accounts_id","accounts","stripe_status"));
              }
		    }
           
    }
    public function PackageDetails1(Request $request)
    {
        if ($request->get("extended") == "yes") {
            $this->validate(
                $request,
                ["accounts_id" => "required"],
                ["required" => "Please choose number of profiles"]
            );
        }
        $user_id = Auth::guard("user")->user()->id;
		$package_id = $request->get("package_id");
        $accounts_id = $request->get("accounts_id");
        $order_type = $old_pkg_id = "";
        if (!empty(Session::get("order_type"))) {
            $order_type = Session::get("order_type");
        }
        if (!empty(Session::get("old_pkg_id"))) {
            $old_pkg_id = Session::get("old_pkg_id");
        }
        $lowest = Package::orderBy("package_basic_price", "asc")
            ->where("status", "!=", "deleted")
            ->where("id", "!=", $package_id)
            ->first(); //lowext price package at right side
        $accounts = PackageAccount::where("id", $accounts_id)->first();
        $no_of_accounts = 0;
        if (!empty($accounts_id)) {
            $no_of_accounts = DB::table("package_accounts")
                ->where("id", $accounts_id)
                ->pluck("no_of_accounts")
                ->first();
        }
        $package = Package::where("id", $package_id)->first();
		if (empty($package)) {
            return redirect()
                ->route("package.listing")
                ->with("message", "No Package Found");
        }
        if (!empty($old_pkg_id) || !empty($order_type)) {
            $old_subscription = Subscription::where("user_id", $user_id)
                ->where("package_id", $old_pkg_id)
                ->orderBy("id", "DESC")
                ->first(); //prev subscription detail to calculate exp dt
            $auto_renewal = 1;
            //if(!empty($old_subscription->auto_renewal))
			//	$auto_renewal = $old_subscription->auto_renewal;
        }

        Session::put("user_checkout_details", [
            "name" => Auth::guard("user")->user()->name,
            "accounts_id" => $request->get("accounts_id"),
            "order_type" => $request->get("order_type"),
            "no_of_accounts" => $no_of_accounts,
            "package_id" => $request->get("package_id"),
            "user_id" => $user_id,
            "email" => Auth::guard("user")->user()->email,
        ]);

        return view( "frontEnd.profile-creation.packageDetails1", compact("package", "lowest", "accounts_id", "accounts") );
    }

    public function ExpiredPackage(Request $request)
    {
        Session::put("old_pkg_id", $request->get("old_pkg_id"));
        Session::put("order_type", $request->get("order_type"));
        //$userId = Auth::guard("user")->user()->id;
        //$user = User::find($userId);
       // $orders = $user->Subscription; //orders  of current user
        //$packagePrice = $remaining_amount;
        //$package_ids = $orders->pluck("package_id")->all(); //all package ids purchased
        //$userType = Auth::guard("user")->user()->usertype;
        $packagePrice = $request->get("package_basicPrice");
		$packages = Package::select("*")
            ->where("status", "!=", "deleted")
            ->orderBy('display_order','asc') ->get();
        $stripe_status = StripeStatus::pluck('status')->first();    
        
        if($this->isMobile()) { 
	    return view( "frontEnd.profile-creation.listPackages_mobile",compact("packages", "packagePrice", "stripe_status"));
        } else {
          return view( "frontEnd.profile-creation.listPackages",compact("packages", "packagePrice", "stripe_status"));
    }
    }
}