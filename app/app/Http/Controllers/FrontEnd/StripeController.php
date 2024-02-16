<?php
namespace App\Http\Controllers\FrontEnd;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Subscription;
use App\Models\Package;
use App\Models\PackageAccount;
use Session;
use Carbon\Carbon;
use DB;
require_once base_path() . "/vendor/stripe/stripe-php/init.php";
class StripeController extends Controller
{
    public function checkout()
    {
        return view("checkout");
    }
    public function session(Request $request)
    {
        Session::put('auto_renewal', $request->get('auto_renewal'));
         //Create Stripe Token
        \Stripe\Stripe::setApiKey("STRIPESECRETKEY");
        $email = $name = "";
        if (session()->has("user_checkout_details")) {
            $email = session("user_checkout_details.email");
            $name = session("user_checkout_details.nme");
        }
        
         $amount = number_format(($request->get("amount")*100) , 0, '', '');
        $item = $request->get("item");
        \Stripe\Stripe::setApiKey(config("stripe.sk"));

        $session = \Stripe\Checkout\Session::create([
            "customer_email" => $email,
            "line_items" => [
                [
                    "price_data" => [
                        "currency" => "usd",
                        "product_data" => [
                            "name" => $item,
                        ],

                        "unit_amount" => $amount,
                    ],
                    "quantity" => 1,
                ],
            ],
            "mode" => "payment",
            "success_url" => route("success"),
            "cancel_url" => route("subscription.details"),
        ]);

        /*  $response = \Stripe\Token::create(array( 
  "card" => array(
   "number" => session('user_checkout_details.card_number'),
    "exp_month" => session('user_checkout_details.card_month'),
    "exp_year" => session('user_checkout_details.card_year'),
    "cvc" => session('user_checkout_details.card_cvc')
)));*/

        if (
            session()->missing("package_id") ||
            session()->missing("accounts_id")
        ) {
            return redirect()->route("subscription.details");
        }
        return redirect()->away($session->url);
    }
    public function success(Request $request)
    {
        
        
        $user_id = Auth::guard("user")->user()->id;
        if((session()->missing('package_id')) || (session()->missing('accounts_id')))  {
        return redirect()->route('subscription.details'); 
        }
        $order_type = $old_pkg_id = "";
        if (!empty(Session::get("order_type"))) {
            $order_type = Session::get("order_type");
        }
        if (!empty(Session::get("old_pkg_id"))) {
            $old_pkg_id = Session::get("old_pkg_id");
        }
        if (session()->has('user_checkout_details')) {
            $order_id = OrderDetail::create(Session::get("user_checkout_details"))
            ->id;
       }
       else {
        return redirect()->route('subscription.details'); 
       }
        $accounts = PackageAccount::where("id", Session::get("accounts_id"))->first();
        $package = Package::where("id", Session::get("package_id"))->first();
        $input = [
            "user_id" => $user_id,
            "package_id" => Session::get("package_id"),
            "type" => $package->user_type,
            "date" => Carbon::today(),
            "order_id" => $order_id,
            "order_total" => Session::get("order_total"),
            'auto_renewal' => Session::get('auto_renewal'),
          //  "auto_renewal" => 1,
        ];
         $package_validity = $package->package_validity;
		 $purchsed_date = Carbon::today();
		 if ($package_validity == "3 months") {
                $Enddate = Carbon::now()->addMonths(3);
            } //add 3 months from date of purchase
            if ($package_validity == "6 months") {
                $Enddate = Carbon::now()->addMonths(6);
            } //add 6 months from date of purchase
            if ($package_validity == "One year") {
                $Enddate = Carbon::now()->addMonths(12);
            } //add 12 months from date of purchase
            if ($order_type == "Renew") {
            Session::forget("last_oreder_total");
            $input["status"] = "Renewed";
            DB::table("subscriptions")
                ->where("user_id", $user_id)
                ->where("package_id", Session::get("old_pkg_id"))
                ->orderBy("id", "desc")
                ->take(1)
                ->update(["status" => "Renewed"]);
            $old_subscription = Subscription::where("user_id", $user_id)
                ->where("package_id", $old_pkg_id)
                ->orderBy("id", "DESC")
                ->first(); //prev subscription detail to calculate exp dt
            $id = $old_subscription->id;
            
            $current_endDate = $old_subscription->expairy_date; //
            $current_endDate = Carbon::createFromFormat( "Y-m-d",$current_endDate );
            
                $input["expairy_date"] = $Enddate->toDateString(); // carbon date format to noramal date
                if (!$current_endDate->isPast()) {
                //active packages
                if ($package_validity == "3 months") {
                    $Enddate1 = $current_endDate->addMonths(3);
                }
                if ($package_validity == "6 months") {
                    $Enddate1 = $current_endDate->addMonths(6);
                }
                if ($package_validity == "One year") {
                    $Enddate1 = $current_endDate->addMonths(12);
                }
                $input["expairy_date"] = $Enddate1->toDateString(); // carbon date format to noramal date
            }
            $input["status"] = "Active";
            $input["auto_renewal"] = $old_subscription->auto_renewal ?? '1';
            Subscription::create($input);
            } 
			elseif ($order_type == "Upgrade") {
            DB::table("subscriptions")
                ->where("user_id", $user_id)
                ->where("package_id", Session::get("old_pkg_id"))
                ->orderBy("id", "desc")
                ->take(1)
                ->update(["status" => "Upgraded"]);
            $old_subscription = Subscription::where("user_id", $user_id)
                ->where("package_id", Session::get("old_pkg_id"))
                ->orderBy("id", "DESC")
                ->first(); //prev subscription detail to calculate exp dt
                
            $input["auto_renewal"] = $old_subscription->auto_renewal ?? '1';
            $input["status"] = "Active";
            $input["expairy_date"] = $Enddate; //
            Subscription::create($input);
            } 
			elseif ($order_type == "Expired") {
            DB::table("subscriptions")
                ->where("user_id", $user_id)
                ->where("package_id", Session::get("old_pkg_id"))
                ->orderBy("id", "desc")
                ->take(1)
                ->update(["status" => "Expired"]);
            $old_subscription = Subscription::where("user_id", $user_id)
                ->where("package_id", Session::get("old_pkg_id"))
                ->orderBy("id", "DESC")
                ->first(); //prev subscription detail to calculate exp dt
			
           $auto_renewal = $old_subscription->auto_renewal;
            
            $input["status"] = "Active";
            $input["expairy_date"] = $Enddate; //
            $input["auto_renewal"] = $auto_renewal;
            Subscription::create($input);
            } else {
            Session::forget("last_oreder_total");
            $input["status"] = "Active";
            $input["expairy_date"] = $Enddate;
			$input["auto_renewal"] =   Session::get('auto_renewal') ?? '1';
		    Subscription::create($input);
        }
        $usertype = Package::find(Session::get("package_id"))->user_type;
        $input = [
            "usertype" => $usertype,
        ];
        Session::forget("old_pkg_id");
        Session::forget("order_type");
        Session::forget("last_oreder_total");
        Session::forget("user_checkout_details");
        Session::forget("package_id");
        Session::forget("accounts_id");
        Session::forget("auto_renewal");
        DB::table("users")
            ->where("id", $user_id)
            ->update($input);
        return redirect()->route("order.success");
    }
}
