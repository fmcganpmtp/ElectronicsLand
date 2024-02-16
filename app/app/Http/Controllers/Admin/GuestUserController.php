<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use File;
use App\User;
use App\Models\Country;
use App\Models\BuyerCompany;
use App\Models\SellerOpeningTime;
use App\Models\CompanyType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
use App\Exports\GuestExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Helpers\XMLWriter;
class GuestUserController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:admin");
    }

    public function index(Request $request)
    {
        $search_key = $request->get("search_key");
        $status = $request->get("status");
        $filter = $request->get("filter");
        if ($filter == "Excel") {
            return Excel::download(
                new GuestExport($search_key, $status),
                "Guest_List.xls"
            );
        } else {
            return view("admin.guestuser.guest_users_list");
        }
    }

    public function create()
    {
        $countries = Country::select("id", "name")->get();
        $company_types = CompanyType::select("id", "company_type")->get();
        return view(
            "admin.guestuser.guest-profile",
            compact("countries", "company_types")
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "surname" => "required",
            "email" => [
                "required",
                "email",
                "regex:/(.+)@(.+)\.(.+)/i",
                Rule::unique("users")->where(function ($query) {
                    return $query->where("status", "<>", "Deleted");
                }),
            ],
            "address" => "required ",
            "default_password" => "required|min:8",
            "country_id" => "required",
            "accepted_payments" => "required ",
            "company_name" => "required",
            "company_zip" => "required",
            "company_location" => "required",
            "company_street" => "required",
            "company_type" => "required",
            "country_id" => "required",
            "default_password" => "required|min:8 ",
            "phone" => [
                "required",
                'regex:/^(^([+]+)(\d+)?$)$/',
                Rule::unique("users")->where(function ($query) {
                    return $query->where("status", "<>", "Deleted");
                }),
            ],
        ]);

        $languages_speak = $deliver_options = $accepted_payments = "";
        if (!empty($request->get("languages_speak"))) {
            $languages_speak = $request->get("languages_speak");
        }

        if (!empty($request->get("deliver_options"))) {
            $deliver_options = $request->get("deliver_options");
        }
        if (!empty($request->get("accepted_payments"))) {
            $accepted_payments = $request->input("accepted_payments");
        }

        $input = [
            "name" => $request->get("name"),
            "surname" => $request->get("surname"),
            "address" => $request->get("address"),
            "email" => $request->get("email"),
            "company_name" => $request->get("company_name"),
            "company_location" => $request->get("company_location"),
            "company_street" => $request->get("company_street"),
            "company_zip" => $request->get("company_zip"),
            "company_type" => $request->get("company_type"),
            "registration_no" => $request->get("registration_number"),
            "deliver_options" => $deliver_options,
            "accepted_payments" => $accepted_payments,
            "languages_speak" => $languages_speak,
            "gst_no" => $request->get("gst_no"),
            "position" => $request->get("position"),
            "country_id" => $request->get("country_id"),
            "default_password" => $request->get("default_password"),
            'email_status' => 'Yes',
        ];

        $input["phone"] = $request->get("phone");
        $input["about"] = $request->get("about");
        $input["usertype"] = "guest";
        $input["company"] = "No";
        $input["password"] = Hash::make($request->get("default_password"));
        $input["profile_pic"] = "";

        if (request()->hasFile("profile_pic")) {
            $extension = request("profile_pic")->extension();
            $fileName = "user_pic" . time() . "." . $extension;
            $destinationPath = public_path() . "/uploads/userImages";
            request("profile_pic")->move($destinationPath, $fileName);
            $input["profile_pic"] = $fileName;
        }

        $user = User::create($input);

        $input["user_id"] = $user->id;
        if (request()->hasFile("imgpicture")) {
            $extension = request("imgpicture")->extension();
            $fileName = "company_pic" . time() . "." . $extension;
            $destinationPath = public_path() . "/uploads/BuyerCompany";
            request("imgpicture")->move($destinationPath, $fileName);
            $input["company_image"] = $fileName;
        }

        $sellercmpy = BuyerCompany::create($input);

        $input["seller_id"] = $user->id;

        $checkSun = $request->get("chSunday");
        $input["day"] = "Sunday";
        $input["opening_time"] = $request->get("opSundayhr");
        $input["closing_time"] = $request->get("clSundayhr");
        $input["Open_am_pm"] = $request->get("SunOpen_am_pm");
        $input["Close_am_pm"] = $request->get("WedClose_am_pm");

        if ($checkSun == "Yes") {
            $input["closed"] = "Yes";
            $input["opening_time"] = "";
            $input["closing_time"] = "";
            $input["Open_am_pm"] = "";
            $input["Close_am_pm"] = "";
        } else {
            $input["closed"] = "No";
        }
        $SellerOpeningTime = SellerOpeningTime::create($input);

        $chMonday = $request->get("chMonday");
        $input["day"] = "Monday";
        $input["opening_time"] = $request->get("opMondayhr");
        $input["closing_time"] = $request->get("clMondayhr");
        $input["Open_am_pm"] = $request->get("MonOpen_am_pm");
        $input["Close_am_pm"] = $request->get("MonClose_am_pm");
        if ($chMonday == "Yes") {
            $input["closed"] = "Yes";
            $input["opening_time"] = "";
            $input["closing_time"] = "";
            $input["Open_am_pm"] = "";
            $input["Close_am_pm"] = "";
        } else {
            $input["closed"] = "No";
        }

        $SellerOpeningTime = SellerOpeningTime::create($input);

        $chTuesday = $request->get("chTuesday");
        $input["day"] = "Tuesday";
        $input["opening_time"] = $request->get("opTuesdayhr");
        $input["closing_time"] = $request->get("clTuesdayhr");
        $input["Open_am_pm"] = $request->get("TueOpen_am_pm");
        $input["Close_am_pm"] = $request->get("TueClose_am_pm");
        if ($chTuesday == "Yes") {
            $input["closed"] = "Yes";
            $input["opening_time"] = "";
            $input["closing_time"] = "";
            $input["Open_am_pm"] = "";
            $input["Open_am_pm"] = "";
        } else {
            $input["closed"] = "No";
        }
        $SellerOpeningTime = SellerOpeningTime::create($input);

        $chWednesday = $request->get("chWednesday");
        $input["day"] = "Wednesday";
        $input["opening_time"] = $request->get("opWednesdayhr");
        $input["closing_time"] = $request->get("clWednesdayhr");
        $input["Open_am_pm"] = $request->get("WedOpen_am_pm");
        $input["Close_am_pm"] = $request->get("WedClose_am_pm");
        if ($chWednesday == "Yes") {
            $input["closed"] = "Yes";
            $input["opening_time"] = "";
            $input["closing_time"] = "";
            $input["Open_am_pm"] = "";
            $input["Close_am_pm"] = "";
        } else {
            $input["closed"] = "No";
        }

        $SellerOpeningTime = SellerOpeningTime::create($input);
        $chThursday = $request->get("chThursday");
        $input["day"] = "Thursday";
        $input["opening_time"] = $request->get("opThursdayhr");
        $input["closing_time"] = $request->get("clThursdayhr");
        $input["Open_am_pm"] = $request->get("ThuOpen_am_pm");
        $input["Close_am_pm"] = $request->get("ThuClose_am_pm");
        if ($chThursday == "Yes") {
            $input["closed"] = "Yes";
            $input["opening_time"] = "";
            $input["closing_time"] = "";
            $input["Open_am_pm"] = "";
            $input["Close_am_pm"] = "";
        } else {
            $input["closed"] = "No";
        }

        $SellerOpeningTime = SellerOpeningTime::create($input);

        $chFriday = $request->get("chFriday");
        $input["day"] = "Friday";
        $input["opening_time"] = $request->get("opFridayhr");
        $input["closing_time"] = $request->get("clFridayhr");
        $input["Open_am_pm"] = $request->get("FriOpen_am_pm");
        $input["Close_am_pm"] = $request->get("FriClose_am_pm");
        if ($chFriday == "Yes") {
            $input["closed"] = "Yes";
            $input["opening_time"] = "";
            $input["closing_time"] = "";
            $input["Open_am_pm"] = "";
            $input["Close_am_pm"] = "";
        } else {
            $input["closed"] = "No";
        }

        $SellerOpeningTime = SellerOpeningTime::create($input);

        $chSaturday = $request->get("chSaturday");
        $input["day"] = "Saturday";
        $input["opening_time"] = $request->get("opSaturdayhr");
        $input["closing_time"] = $request->get("clSaturdayhr");
        $input["Open_am_pm"] = $request->get("SatOpen_am_pm");
        $input["Close_am_pm"] = $request->get("SatClose_am_pm");
        if ($chSaturday == "Yes") {
            $input["closed"] = "Yes";
            $input["opening_time"] = "";
            $input["closing_time"] = "";
            $input["Open_am_pm"] = "";
            $input["Close_am_pm"] = "";
        } else {
            $input["closed"] = "No";
        }

        $SellerOpeningTime = SellerOpeningTime::create($input);

        Mail::send(
            "admin/guestuser/email-template",
            [
                "name" => $input["name"],
                "email" => $input["email"],
                "password" => $input["default_password"],
            ],
            function ($message) use ($input) {
                $message->from("example@gmail.com", "Fmcg");
                $message
                    ->to($input["email"], $input["name"])
                    ->subject("Fmcg Login Credential");
            }
        );

        echo json_encode("Guest Added successfully!");
    }

    public function update(Request $request)
    {
        $userId = $request->get("user_id");
        $request->validate([
            "name" => "required",
            "surname" => "required",
            "email" => [
                "required",
                "email",
                "regex:/(.+)@(.+)\.(.+)/i",
                Rule::unique("users")
                    ->ignore($userId)
                    ->where(function ($query) {
                        return $query->where("status", "<>", "Deleted");
                    }),
            ],
            "address" => "required ",
            "company_name" => "required",
            "company_zip" => "required",
            "company_location" => "required",
            "company_street" => "required",
            "company_type" => "required",
            "country_id" => "required",
            "accepted_payments" => "required ",
            "phone" => [
                "required",
                'regex:/^(^([+]+)(\d+)?$)$/',
                Rule::unique("users")
                    ->ignore($userId)
                    ->where(function ($query) {
                        return $query->where("status", "<>", "Deleted");
                    }),
            ],
        ]);

        $languages_speak = $deliver_options = $accepted_payments = "";
        if (!empty($request->get("languages_speak"))) {
            $languages_speak = $request->get("languages_speak");
        }

        if (!empty($request->get("deliver_options"))) {
            $deliver_options = $request->get("deliver_options");
        }
        if (!empty($request->get("accepted_payments"))) {
            $accepted_payments = $request->input("accepted_payments");
        }

        $input = [
            "name" => $request->get("name"),
            "surname" => $request->get("surname"),
            "address" => $request->get("address"),
            "email" => $request->get("email"),
            "about" => $request->get("about"),
            "position" => $request->get("position"),
            "country_id" => $request->get("country_id"),
        ];

        $input["phone"] = $request->get("phone");
        // $input['usertype'] = 'buyer';
        // $input['company'] = 'No';
        // $input['password'] =Hash::make($request->get('default_password'));
        // $input['profile_pic'] ='';

        if (request()->hasFile("profile_pic")) {
            $extension = request("profile_pic")->extension();
            $fileName = "user_pic" . time() . "." . $extension;
            $destinationPath = public_path() . "/uploads/userImages";
            request("profile_pic")->move($destinationPath, $fileName);
            $input["profile_pic"] = $fileName;
        }

        $user = User::where("id", $userId)->update($input);

        $input["user_id"] = $userId;
        $input = [
            "company_name" => $request->get("company_name"),
            "company_type" => $request->get("company_type"),
            "company_street" => $request->get("company_street"),
            "company_zip" => $request->get("company_zip"),
            "company_location" => $request->get("company_location"),
            "company_land" => $request->get("company_land"),
            "opening_hours" => $request->get("opening_hours"),
            "about_company" => $request->get("about_company"),
            "accepted_payments" => $accepted_payments,
            "user_id" => $userId,
            "languages_speak" => $languages_speak,
            "registration_no" => $request->get("registration_number"),
            "gst_no" => $request->get("gst_no"),
        ];
        if (request()->hasFile("imgpicture")) {
            $extension = request("imgpicture")->extension();
            $fileName = "company_pic" . time() . "." . $extension;
            $destinationPath = public_path() . "/uploads/BuyerCompany";
            request("imgpicture")->move($destinationPath, $fileName);
            $input["company_image"] = $fileName;
        }

        $user_cmpy = BuyerCompany::where("user_id", "=", $userId)->first();

        if (!empty($user_cmpy)) {
            DB::table("buyer_companies")
                ->where("user_id", $userId)
                ->update($input);
        } else {
            BuyerCompany::create($input);
        }
        $input = [];
        $input["seller_id"] = $userId;

        $checkSun = $request->get("chSunday");
        $input["day"] = "Sunday";
        $input["opening_time"] = $request->get("opSundayhr");
        $input["closing_time"] = $request->get("clSundayhr");
        $input["Open_am_pm"] = $request->get("SunOpen_am_pm");
        $input["Close_am_pm"] = $request->get("WedClose_am_pm");

        if ($checkSun == "Yes") {
            $input["closed"] = "Yes";
            $input["opening_time"] = "";
            $input["closing_time"] = "";
            $input["Open_am_pm"] = "";
            $input["Close_am_pm"] = "";
        } else {
            $input["closed"] = "No";
        }
        $sunday = SellerOpeningTime::where("seller_id", "=", $userId)
            ->where("day", "=", "Sunday")
            ->first();
        if (isset($sunday->id)) {
            $SellerOpeningTime = DB::table("seller_opening_times")
                ->where("id", $sunday->id)
                ->update($input);
        } else {
            $SellerOpeningTime = SellerOpeningTime::create($input);
        }

        $chMonday = $request->get("chMonday");
        $input["day"] = "Monday";
        $input["opening_time"] = $request->get("opMondayhr");
        $input["closing_time"] = $request->get("clMondayhr");
        $input["Open_am_pm"] = $request->get("MonOpen_am_pm");
        $input["Close_am_pm"] = $request->get("MonClose_am_pm");
        if ($chMonday == "Yes") {
            $input["closed"] = "Yes";
            $input["opening_time"] = "";
            $input["closing_time"] = "";
            $input["Open_am_pm"] = "";
            $input["Close_am_pm"] = "";
        } else {
            $input["closed"] = "No";
        }

        $monday = SellerOpeningTime::where("seller_id", "=", $userId)
            ->where("day", "=", "Monday")
            ->first();
        if (isset($monday->id)) {
            $SellerOpeningTime = DB::table("seller_opening_times")
                ->where("id", $monday->id)
                ->update($input);
        } else {
            $SellerOpeningTime = SellerOpeningTime::create($input);
        }

        $chTuesday = $request->get("chTuesday");
        $input["day"] = "Tuesday";
        $input["opening_time"] = $request->get("opTuesdayhr");
        $input["closing_time"] = $request->get("clTuesdayhr");
        $input["Open_am_pm"] = $request->get("TueOpen_am_pm");
        $input["Close_am_pm"] = $request->get("TueClose_am_pm");
        if ($chTuesday == "Yes") {
            $input["closed"] = "Yes";
            $input["opening_time"] = "";
            $input["closing_time"] = "";
            $input["Open_am_pm"] = "";
            $input["Open_am_pm"] = "";
        } else {
            $input["closed"] = "No";
        }
        $tuesday = SellerOpeningTime::where("seller_id", "=", $userId)
            ->where("day", "=", "Tuesday")
            ->first();
        if (isset($tuesday->id)) {
            $SellerOpeningTime = DB::table("seller_opening_times")
                ->where("id", $tuesday->id)
                ->update($input);
        } else {
            $SellerOpeningTime = SellerOpeningTime::create($input);
        }

        $chWednesday = $request->get("chWednesday");
        $input["day"] = "Wednesday";
        $input["opening_time"] = $request->get("opWednesdayhr");
        $input["closing_time"] = $request->get("clWednesdayhr");
        $input["Open_am_pm"] = $request->get("WedOpen_am_pm");
        $input["Close_am_pm"] = $request->get("WedClose_am_pm");
        if ($chWednesday == "Yes") {
            $input["closed"] = "Yes";
            $input["opening_time"] = "";
            $input["closing_time"] = "";
            $input["Open_am_pm"] = "";
            $input["Close_am_pm"] = "";
        } else {
            $input["closed"] = "No";
        }

        $wednesday = SellerOpeningTime::where("seller_id", "=", $userId)
            ->where("day", "=", "Wednesday")
            ->first();
        if (isset($wednesday->id)) {
            $SellerOpeningTime = DB::table("seller_opening_times")
                ->where("id", $wednesday->id)
                ->update($input);
        } else {
            $SellerOpeningTime = SellerOpeningTime::create($input);
        }
        $chThursday = $request->get("chThursday");
        $input["day"] = "Thursday";
        $input["opening_time"] = $request->get("opThursdayhr");
        $input["closing_time"] = $request->get("clThursdayhr");
        $input["Open_am_pm"] = $request->get("ThuOpen_am_pm");
        $input["Close_am_pm"] = $request->get("ThuClose_am_pm");
        if ($chThursday == "Yes") {
            $input["closed"] = "Yes";
            $input["opening_time"] = "";
            $input["closing_time"] = "";
            $input["Open_am_pm"] = "";
            $input["Close_am_pm"] = "";
        } else {
            $input["closed"] = "No";
        }

        $thursday = SellerOpeningTime::where("seller_id", "=", $userId)
            ->where("day", "=", "Thursday")
            ->first();
        if (isset($thursday->id)) {
            $SellerOpeningTime = DB::table("seller_opening_times")
                ->where("id", $thursday->id)
                ->update($input);
        } else {
            $SellerOpeningTime = SellerOpeningTime::create($input);
        }

        $chFriday = $request->get("chFriday");
        $input["day"] = "Friday";
        $input["opening_time"] = $request->get("opFridayhr");
        $input["closing_time"] = $request->get("clFridayhr");
        $input["Open_am_pm"] = $request->get("FriOpen_am_pm");
        $input["Close_am_pm"] = $request->get("FriClose_am_pm");
        if ($chFriday == "Yes") {
            $input["closed"] = "Yes";
            $input["opening_time"] = "";
            $input["closing_time"] = "";
            $input["Open_am_pm"] = "";
            $input["Close_am_pm"] = "";
        } else {
            $input["closed"] = "No";
        }

        $friday = SellerOpeningTime::where("seller_id", "=", $userId)
            ->where("day", "=", "Friday")
            ->first();
        if (isset($friday->id)) {
            $SellerOpeningTime = DB::table("seller_opening_times")
                ->where("id", $friday->id)
                ->update($input);
        } else {
            $SellerOpeningTime = SellerOpeningTime::create($input);
        }

        $chSaturday = $request->get("chSaturday");
        $input["day"] = "Saturday";
        $input["opening_time"] = $request->get("opSaturdayhr");
        $input["closing_time"] = $request->get("clSaturdayhr");
        $input["Open_am_pm"] = $request->get("SatOpen_am_pm");
        $input["Close_am_pm"] = $request->get("SatClose_am_pm");
        if ($chSaturday == "Yes") {
            $input["closed"] = "Yes";
            $input["opening_time"] = "";
            $input["closing_time"] = "";
            $input["Open_am_pm"] = "";
            $input["Close_am_pm"] = "";
        } else {
            $input["closed"] = "No";
        }

        $satday = SellerOpeningTime::where("seller_id", "=", $userId)
            ->where("day", "=", "Saturday")
            ->first();
        if (isset($satday->id)) {
            $SellerOpeningTime = DB::table("seller_opening_times")
                ->where("id", $satday->id)
                ->update($input);
        } else {
            $SellerOpeningTime = SellerOpeningTime::create($input);
        }

       echo json_encode("Guest Updated successfully!");
    }

    public function guestedit($id)
    {
        $seller = User::find($id);
        $countries = Country::select("id", "name")->get();
        $SellerOpeningTimes = SellerOpeningTime::where("seller_id", $id)->get();
        $company_types = CompanyType::select("id", "company_type")->get();
        return view(
            "admin.guestuser.guest-profile",
            compact(
                "countries",
                "seller",
                "SellerOpeningTimes",
                "company_types"
            )
        );
    }

    public function getguestuserslist(Request $request)
    {
        $columnIndex_arr = $request->get("order");
        $columnName_arr = $request->get("columns");
        $order_arr = $request->get("order");
        $search_arr = $request->get("search");

        $draw = $request->get("draw");
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page
        $columnIndex = $columnIndex_arr[0]["column"]; // Column index
        $columnName = $columnName_arr[$columnIndex]["data"]; // Column name
        $columnSortOrder = $order_arr[0]["dir"]; // asc or desc

        $searchValue = $request->get("search_key");
        $status = $request->get("status");
        $totalRecords = User::select("count(*) as allcount")
            ->when($request->get("status") != "", function ($query) use (
                $request
            ) {
                $query->where("status", $request->get("status"));
            })
            ->where("usertype", "guest")
            ->where("seller_type", "Master")
            ->where("users.status", "<>", "Deleted")
            ->count();

        $totalRecordswithFilter = User::leftJoin(
            "buyer_companies",
            "buyer_companies.user_id",
            "=",
            "users.id"
        )
            ->leftJoin("countries", "countries.id", "=", "users.country_id")
            ->when($request->get("status") != "", function ($query) use (
                $request
            ) {
                $query->where("users.status", $request->get("status"));
            })
            ->when($searchValue != "", function ($query) use ($searchValue) {
                $query->where(
                    DB::raw(
                        "CONCAT_WS(users.name,email,phone,buyer_companies.company_name,countries.name)"
                    ),
                    "LIKE",
                    "%" . $searchValue . "%"
                );
            })

            ->where("usertype", "guest")
            ->where("seller_type", "Master")
            ->where("users.status", "<>", "Deleted")
            ->count();

        // Get records, also we have included search filter as well
        $records = User::leftJoin(
            "buyer_companies",
            "buyer_companies.user_id",
            "=",
            "users.id"
        )
            ->leftJoin("countries", "countries.id", "=", "users.country_id")
            //->leftJoin('subscriptions', 'subscriptions.user_id', '=', 'users.id')
            ->when($request->get("status") != "", function ($query) use (
                $request
            ) {
                $query->where("users.status", $request->get("status"));
            })
            ->when($searchValue != "", function ($query) use ($searchValue) {
                $query->where(
                    DB::raw(
                        "CONCAT_WS(users.name,email,phone,buyer_companies.company_name,countries.name)"
                    ),
                    "LIKE",
                    "%" . $searchValue . "%"
                );
            })
            ->where("usertype", "guest")
            ->where("seller_type", "Master")
            ->where("users.status", "<>", "Deleted")
            ->orderBy($columnName, $columnSortOrder)
            ->select(
                "users.*",
                "buyer_companies.company_name",
                DB::raw("countries.name as country_name"),
                DB::raw(
                    "(SELECT expairy_date FROM subscriptions WHERE subscriptions.user_id = users.id order BY subscriptions.id DESC limit 1) as expairy_date"
                )
            )
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = [];

        foreach ($records as $record) {
            $status =
                $record->status == "Active"
                    ? '<span style="color:white;background-color:green;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">' .
                        $record->status .
                        "</span>"
                    : ($record->status == "Blocked"
                        ? '<span style="color:white;background-color:red;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">' .
                            $record->status .
                            "</span>"
                        : ($record->status == "Pending"
                            ? '<span style="color:white;background-color:orange;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">' .
                                $record->status .
                                "</span>"
                            : ($record->status == "Rejected"
                                ? '<span style="color:white;background-color:purple;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">' .
                                    $record->status .
                                    "</span>"
                                : "")));

            $name = $record->name . "<br/> " . $status;

            $data_arr[] = [
                "id" => $record->id,
                "name" => $name,
                "company_name" => $record->company_name,
                "status" => $record->status,
                "phone" => $record->phone,
                "email" => $record->email,
                "address" => $record->address,
                "created_at" => date("d-m-Y", strtotime($record->created_at)),
                "country_name" => $record->country_name,
                "subscription" =>
                    $record->expairy_date == ""
                        ? "Nill"
                        : date("d-m-Y", strtotime($record->expairy_date)),
                "status" =>
                    $record->status == "Active"
                        ? '<span style="color:green">' .
                            $record->status[0] .
                            "</span>"
                        : ($record->status == "Blocked"
                            ? '<span style="color:red">' .
                                $record->status[0] .
                                "</span>"
                            : $record->status[0]),
                "status1" => $record->status,
            ];
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        ];
        echo json_encode($response);
    }

    public function guestdetails($id)
    {
        $seller = User::find($id);
        $kycdocs_varified = DB::table("kyc_files")
            ->select(DB::raw("count('*') as status_cnt"))
            ->where("user_id", $id)->where("status", "Active")->pluck("status_cnt")
            ->first(); 
        $varification_status=($kycdocs_varified==3)?"varified":"Not";
        return view("admin.guestuser.guest-view", compact("seller", "id","varification_status"));
    }
}
