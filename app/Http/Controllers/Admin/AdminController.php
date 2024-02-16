<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Admin;
use DB;
use File;
use App\Models\Permission;
use App\Models\AdminRole;
use App\Models\Category;
use App\Models\SellerProduct;
use App\Models\StripeStatus;
use App\Models\TopCategory;
use App\Models\SearchCondition;
use App\Models\FrontEndMenu;
use App\Models\SocialMedia;
use App\Models\Generalsetting;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
class AdminController extends Controller
{
    
    public function stripe_status(Request $request)
    {
        $stripe_status = $request->input("stripe_status");
        $status_update = StripeStatus::where("id", 1)->update([
            "status" => $stripe_status,
        ]);

        return json_encode($status_update);
    }
    public function user_status(Request $request)
    {
        $user_status = $request->input("user_status");
        $user_id = $request->input("user_id");

        $status_update = User::where("id", $user_id)->update([
            "user_status" => $user_status,
        ]);

        return json_encode($status_update);
    }
    
    //showAdmin
    public function showAdmin()
    {
        
        //today total chat count 
        $total_chat_usage=Message::
            //whereDate('created_at', Carbon::today())->
            count();
        //convert to thousand format
        if($total_chat_usage>1000 && $total_chat_usage<1000000)
            $total_chat_usage=number_format(($total_chat_usage/1000),1) ."K";
        else if($total_chat_usage>=1000000)
            $total_chat_usage=number_format(($total_chat_usage/1000000),1) ."M";
        //today total count of engaged users 
        $engached_users1=Message::
            //whereDate('created_at', Carbon::today())->
            pluck('from_user')->all();
        $engached_users2=Message::
            //whereDate('created_at', Carbon::today())->
            pluck('to_user')->all();
        $engached_users=array_merge($engached_users1,$engached_users2);
        $engached_users=count(array_unique($engached_users));
        //convert to thousand format 
        if($engached_users>1000 && $engached_users<1000000)
            $engached_users=number_format(($engached_users/1000),1) ."K";
        else if($engached_users>=1000000)
            $engached_users=number_format(($engached_users/1000000),1) ."M";
            
            
        $totalProducts =SellerProduct::where('status','<>','deleted')->count();
        //convert to thousand format
        if($totalProducts>1000 && $totalProducts<1000000)
            $totalProducts=number_format(($totalProducts/1000),1) ."K";
        else if($totalProducts>=1000000)
            $totalProducts=number_format(($totalProducts/1000000),1) ."M";    
        return view("admin.dashboard",compact('total_chat_usage','engached_users','totalProducts'));
    }
    //chat insight graphical report function
    public function getchat_insight(Request $request)
    {   
        //last week or last month format
        $type = $request->input("type");
        $label  = $chat_insight = [];
        $period="";
        if($type=='last_week')
        {
            $inr = 6;
            $period=Carbon::now()->subdays(6)->format("d-m-Y")." - ".Carbon::now()->format("d-m-Y");
            //loop for last seven days
            while ($inr >= 0) {
                $date = Carbon::now()->subdays($inr);
                array_push($label, $date->format("l"));
                $data1 = Message::whereDate('created_at', $date->format("Y-m-d"))->count();
                array_push($chat_insight, $data1);
                $inr--;
            }
        }
        else
        {
                //last month report creation
                $period=Carbon::now()->subdays(27)->format("d-m-Y")." - ".Carbon::now()->format("d-m-Y");
                
                $profile_views_week1 = Message::whereDate("created_at", ">=", Carbon::now()->subdays(6))
                                             ->count();
                $profile_views_week2 = Message::whereDate("created_at", "<=", Carbon::now()->subdays(6))
                                              ->whereDate("created_at", ">=", Carbon::now()->subdays(13))
                                              ->count();
         
                $profile_views_week3 = Message::whereDate("created_at", "<=", Carbon::now()->subdays(13))
                                              ->whereDate("created_at", ">=", Carbon::now()->subdays(20))
                                              ->count();
         
                $profile_views_week4 = Message::whereDate("created_at", "<=", Carbon::now()->subdays(20))
                                              ->whereDate("created_at", ">=", Carbon::now()->subdays(27))
                                              ->count();
                //asign to response array                              
                $chat_insight=[$profile_views_week1,$profile_views_week2,$profile_views_week3,$profile_views_week4];
                $label=["Week 1", "Week 2", "Week 3", "Week 4"];
         
        }
        $response_data= array('label'=>$label,'chat_insight'=>$chat_insight,'period'=>$period); 
        return json_encode($response_data); 
    }
    //function call for engaged graph report
    public function get_engagedusers(Request $request)
    {
        //last week or last month report
        $type = $request->input("type");
        $label  = $engaged_users = [];
        $period="";
        if($type=='last_week')
        {
            $inr = 6;
            $period=Carbon::now()->subdays(6)->format("d-m-Y")." - ".Carbon::now()->format("d-m-Y");
            //loop for last week data fetching
            while ($inr >=0) {
                $date = Carbon::now()->subdays($inr);
                array_push($label, $date->format("l"));
                $data1= Message::whereDate('created_at', $date->format("Y-m-d"))->pluck('from_user')->all();
                $data2=Message::whereDate('created_at', $date->format("Y-m-d"))->pluck('to_user')->all();
                $data1=array_merge($data1,$data2);
                $data1=count(array_unique($data1));
                array_push($engaged_users,$data1);
                $inr--;
            }
        }
        else
        {
                //last month data fetching
                $period=Carbon::now()->subdays(27)->format("d-m-Y")." - ".Carbon::now()->format("d-m-Y");
                
                $profile_views_week1 = Message::whereDate("created_at", ">=", Carbon::now()->subdays(6))
                                             ->pluck('from_user')->all() ;
                $profile_views_week11 =  Message::whereDate("created_at", ">=", Carbon::now()->subdays(6))
                                             ->pluck('to_user')->all();

                $profile_views_week1=array_merge($profile_views_week1,$profile_views_week11);
                $profile_views_week1=count(array_unique($profile_views_week1));
                                             
                $profile_views_week2 = Message::whereDate("created_at", "<=", Carbon::now()->subdays(6))
                                              ->whereDate("created_at", ">=", Carbon::now()->subdays(13))
                                              ->pluck('from_user')->all();
                $profile_views_week21   =Message::whereDate("created_at", "<=", Carbon::now()->subdays(6))
                                              ->whereDate("created_at", ">=", Carbon::now()->subdays(13))
                                              ->pluck('to_user')->all();
                $profile_views_week2=array_merge($profile_views_week2,$profile_views_week21);
                $profile_views_week2=count(array_unique($profile_views_week2));

                $profile_views_week3 = Message::whereDate("created_at", "<=", Carbon::now()->subdays(13))
                                              ->whereDate("created_at", ">=", Carbon::now()->subdays(20))
                                              ->pluck('from_user')->all() ;
                $profile_views_week31=Message::whereDate("created_at", "<=", Carbon::now()->subdays(13))
                                              ->whereDate("created_at", ">=", Carbon::now()->subdays(20))
                                              ->pluck('to_user')->all();

                $profile_views_week3=array_merge($profile_views_week3,$profile_views_week31);
                $profile_views_week3=count(array_unique($profile_views_week3));

                $profile_views_week4 = Message::whereDate("created_at", "<=", Carbon::now()->subdays(20))
                                              ->whereDate("created_at", ">=", Carbon::now()->subdays(27))
                                              ->pluck('from_user')->all() ; 
                $profile_views_week41 =   Message::whereDate("created_at", "<=", Carbon::now()->subdays(20))
                                              ->whereDate("created_at", ">=", Carbon::now()->subdays(27))
                                              ->pluck('to_user')->all();

                $profile_views_week4=array_merge($profile_views_week4,$profile_views_week41);
                $profile_views_week4=count(array_unique($profile_views_week4));
                //response data array creation                             
                $engaged_users=[$profile_views_week1,$profile_views_week2,$profile_views_week3,$profile_views_week4];
                $label=["Week 1", "Week 2", "Week 3", "Week 4"];
         
        }
        $response_data= array('label'=>$label,'engaged_users'=>$engaged_users,'period'=>$period); 
        return json_encode($response_data); 
    }
    
    //shows all users list
    public function listUsers(Request $request)
    {
        $search_key = $request->get("search_key");
        $role = AdminRole::get();
        return view("admin.users.listusers", compact("search_key", "role"));
    }
    // front end menu shows
    public function frontendmenulist()
    {
        $Category = Category::all();
        $FrontEndMenu = FrontEndMenu::pluck("category_id")->all();
        return view(
            "admin.settings.front-end-list-manager",
            compact("Category", "FrontEndMenu")
        );
    }
    //add new menu to front end list
    public function saveFrontEndMenu(Request $request)
    {
        //request()->validate([ "category_id" => ['required'] ]);
        $category = $request->get("category_id");

        FrontEndMenu::truncate();
        if (!empty($category)) {
            foreach ($category as $data) {
                $array = ["category_id" => $data];
                FrontEndMenu::create($array);
            }
        }
        return redirect()
            ->route("admin.listfrontendmenu")
            ->with("message", "Front Menu Updated");
    }
    // top category list in front end
    public function topcategorylist()
    {
        $Category = Category::where("parent_id", null)->get();
        $TopCategory = TopCategory::pluck("category_id")->all();
        return view(
            "admin.settings.topcategory",
            compact("Category", "TopCategory")
        );
    }
    //add new item to top category
    public function savetopcategory(Request $request)
    {
        $category = $request->get("category_id");

        TopCategory::truncate();
        if (!empty($category)) {
            foreach ($category as $data) {
                $array = ["category_id" => $data];
                TopCategory::create($array);
            }
        }
        return redirect()
            ->route("admin.listtopcategory")
            ->with("message", "Top Category Updated");
    }
    //data table load users list function
    public function getuserslist(Request $request)
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
        // $searchValue = $search_arr['value']; // Search value
        $searchValue = $request->get("search_key");
        $role_id = $request->get("role_id");
        //total list count
        $totalRecords = DB::table("admins")
            ->select("count(*) as allcount")
            ->count();
        // filtered count    
        $totalRecordswithFilter = DB::table("admins")
            ->leftJoin("admin_roles", "admins.adminrole", "=", "admin_roles.id")
            ->select("count(*) as allcount")
            ->where("admins.id", "!=", Auth::guard("admin")->user()->id)
            ->where("name", "like", "%" . $searchValue . "%")
            ->orWhere("email", "Like", "%" . $searchValue . "%")
            ->orWhere("phone", "Like", "%" . $searchValue . "%")
            ->orWhere("admin_roles.role_name", "Like", "%" . $searchValue . "%")
            ->where(function ($query) use ($request) {
                if ($request->get("role_id") != "") {
                    $query->where("admins.adminrole", $request->get("role_id"));
                }
            })
            //->orWhere('admins.created_at','Like','%'.$searchValue.'%')
            ->count();

        // Get records, also we have included search filter as well
        $records = DB::table("admins")
            ->leftJoin("admin_roles", "admins.adminrole", "=", "admin_roles.id")
            ->select(
                "admins.*",
                "admin_roles.role_name as role_name",
                "admin_roles.id as role_id"
            )
            ->orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query
                    ->where("name", "like", "%" . $searchValue . "%")
                    // ->orWhere('admins.id', '!=', Auth::guard('admin')->user()->id)
                    ->orWhere("email", "like", "%" . $searchValue . "%")
                    ->orWhere("phone", "Like", "%" . $searchValue . "%")
                    ->orWhere(
                        "admin_roles.role_name",
                        "Like",
                        "%" . $searchValue . "%"
                    );
            })
            ->where(function ($query) use ($request) {
                if ($request->get("role_id") != "") {
                    $query->where("admins.adminrole", $request->get("role_id"));
                }
            })
            // ->orWhere('admins.created_at','Like','%'.$searchValue.'%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = [];

        foreach ($records as $record) {
            $data_arr[] = [
                "id" => $record->id,
                "profile_pic" => $record->profile_pic
                    ? asset("/uploads/userImages/") . "/" . $record->profile_pic
                    : asset("uploads/defaultImages/default_avatar.png"),
                "name" => $record->name,
                "email" => $record->email,
                "phone" => $record->phone,
                "role_name" => $record->role_name,
                "created_at" => date(
                    "d-m-Y h:m:s a",
                    strtotime($record->created_at)
                ),
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

    public function createUser()
    {
        $adminroles = AdminRole::all();
        return view("admin.users.adduser", compact("adminroles"));
    }
    //add new user
    public function saveUser(Request $request)
    {
        request()->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "email",
                "regex:/(.+)@(.+)\.(.+)/i",
                "max:255",
                "unique:admins",
            ],
            // 'username' => ['required', 'string',  'max:255', 'unique:admins'],
            "phone" => ["required", "max:12", "unique:admins"],
            "adminrole" => ["required"],
            "job_title" => ["required"],
            "password" => ["required", "string", "min:6", "confirmed"],
        ]);
        $input = [
            "name" => $request->get("name"),
            "email" => $request->get("email"),
            // 'username' => $request->get('username'),
            "phone" => $request->get("phone"),
            "about" => $request->get("about"),
            "adminrole" => $request->get("adminrole"),
            "job_title" => $request->get("job_title"),
            "password" => Hash::make($request->get("password")),
        ];
        if (request()->hasFile("image")) {
            $extension = request("image")->extension();
            $fileName = "user_pic" . time() . "." . $extension;
            // request('image')->storeAs('images',$fileName);
            $destinationPath = public_path() . "/uploads/userImages";
            request("image")->move($destinationPath, $fileName);
            $input["profile_pic"] = $fileName;
        }
        Admin::create($input);

        return redirect()
            ->route("list-users")
            ->with("message", "User Added Successfully");
    }
    public function editUser($userId)
    {
        $user = Admin::find($userId);
        $adminroles = AdminRole::all();
        if (empty($user)) {
            return redirect()
                ->route("list-users")
                ->with("message", "User not Exists");
        }
        return view("admin.users.edit-user", compact("user", "adminroles"));
    }

    public function deleteUserimage($id)
    {
        $images = Admin::findOrFail($id);
        if (File::exists("/uploads/userImages/" . $images->profile_pic)) {
            File::delete("/uploads/userImages/" . $images->profile_pic);
        }
        $profImg = Admin::find($id);

        if ($profImg) {
            $profImg->profile_pic = null;
            $profImg->save();
        }
        return back();
    }
    //update single user
    public function updateUser(Request $request)
    {
        $userId = $request->get("user_id");
        $user = Admin::find($userId);
        request()->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "email",
                "regex:/(.+)@(.+)\.(.+)/i",
                Rule::unique("admins")->ignore($request->user_id),
            ],
            "phone" => [
                "required",
                "max:12",
                Rule::unique("admins")->ignore($request->user_id),
            ],
            "adminrole" => ["required"],
        ]);

        if ($request->has("password") != "") {
            request()->validate([
                "password" => ["required", "string", "min:6", "confirmed"],
            ]);
        }

        $input = [
            "name" => $request->get("name"),
            "email" => $request->get("email"),
            //'username' => $user->username,
            "phone" => $request->get("phone"),
            "about" => $request->get("about"),
            "job_title" => $request->get("job_title"),
            "adminrole" => $request->get("adminrole"),
            "password" => $request->has("password")
                ? Hash::make($request->get("password"))
                : $user->password,
        ];

        // $input['adminrole']=$user->adminrole;

        if ($request->has("image")) {
            //return request('image');
            $extension = request("image")->extension();
            $fileName = "user_pic" . time() . "." . $extension;
            $destinationPath = public_path() . "/uploads/userImages";
            request("image")->move($destinationPath, $fileName);
            $input["profile_pic"] = $fileName;
        } else {
            $input["profile_pic"] = $user->profile_pic;
        }
        $user->update($input);

        return redirect()
            ->route("list-users")
            ->with("message", "Profile Data Updated");
    }
    //delete admin users one by one
    public function deleteUser($userId)
    {
        $user = Admin::find($userId);
        if (empty($user)) {
            return redirect()
                ->route("list-users")
                ->with("message", "User not Exists");
        }
        $user->delete();
        return redirect()
            ->route("list-users")
            ->with("message", "User Deleted!");
    }
    //delete profileimage
    public function deleteprofileimage(Request $request)
    {
        $user = Admin::find($request->get("id"));
        $destinationPath =public_path() . "/uploads/userImages/" . $user->profile_pic;
        if (file_exists($destinationPath))
        @unlink($destinationPath);
        $user->profile_pic = "";
        if ($user->save()) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    public function listPermissions(Request $request)
    {
        $search_key = $request->get("search_key");

        return view("admin.permissions.listpermissions", compact("search_key"));
    }

    public function getpermissionslist(Request $request)
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
        //$searchValue = $search_arr['value']; // Search value
        $searchValue = $request->get("search_key");
        $totalRecords = Permission::select("count(*) as allcount")->count();
        $totalRecordswithFilter = Permission::select("count(*) as allcount")
            ->where("name", "like", "%" . $searchValue . "%")
            ->orwhere("slug", "like", "%" . $searchValue . "%")
            ->count();

        // Get records, also we have included search filter as well
        $records = Permission::select("*")
            ->orderBy($columnName, $columnSortOrder)
            ->where("name", "like", "%" . $searchValue . "%")
            ->orwhere("slug", "like", "%" . $searchValue . "%")
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = [];
        foreach ($records as $record) {
            $data_arr[] = [
                "id" => $record->id,
                "name" => $record->name,
                "slug" => $record->slug,
                "created_at" => date(
                    "d-m-Y h:m:s a",
                    strtotime($record->created_at)
                ),
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

    public function createPermission()
    {
        return view("admin.permissions.addpermission");
    }
    public function savePermission(Request $request)
    {
        request()->validate([
            "name" => ["required", "string", "max:255"],
            "slug" => ["required", "string", "max:255"],
        ]);
        $input = [
            "name" => $request->get("name"),
            "slug" => $request->get("slug"),
        ];
        Permission::create($input);
        return redirect()
            ->route("list.permissions")
            ->with("message", "New Permission Added");
    }
    public function editPermission($permissionId)
    {
        $permission = Permission::find($permissionId);
        if (empty($permission)) {
            return redirect()
                ->route("list.permissions")
                ->with("message", "Permission not Exists");
        }
        return view("admin.permissions.edit-permission", compact("permission"));
    }
    public function updatePermission(Request $request)
    {
        request()->validate([
            "name" => ["required", "string", "max:255"],
            "slug" => ["required", "string", "max:255"],
        ]);

        $permissionId = $request->get("permission_id");
        $permission = Permission::find($permissionId);

        $input = [
            "name" => $request->get("name"),
            "slug" => $request->get("slug"),
        ];

        $permission->update($input);
        return redirect()
            ->route("list.permissions")
            ->with("message", "Permission Data Updated");
    }
    public function deletePermission($permissionId)
    {
        $permission = Permission::find($permissionId);
        if (empty($permission)) {
            return redirect()
                ->route("list.permissions")
                ->with("message", "Permission not Exists");
        }
        $permission->delete();
        return redirect()
            ->route("list.permissions")
            ->with("message", "Permission Deleted successfully");
    }

    public function profile()
    {
        $user = Auth::guard("admin")->user();
        $Roleid = Auth::guard("admin")->user()->adminrole;
        $adminroleSlected = AdminRole::find($Roleid);
        $adminroles = AdminRole::all();
        return view(
            "admin.users.profile",
            compact("user", "adminroleSlected", "adminroles")
        );
    }

    public function submitAdminResetPasswordForm(Request $request)
    {
        $request->validate([
            "oldPassword" => "required",
            "password" => "required|string|min:6|confirmed",
            "password_confirmation" => "required",
        ]);
        if (
            !Hash::check(
                $request["oldPassword"],
                Auth::guard("admin")->user()->password
            )
        ) {
            return redirect()
                ->route("admin.profile")
                ->with(
                    "message",
                    "The old password does not match our records."
                );
        }
        $updatePassword = DB::table("password_resets")
            ->where([
                "email" => $request->email,
                "token" => $request->token,
            ])
            ->first();

        $user = Admin::where("email", $request->email)->update([
            "password" => Hash::make($request->password),
        ]);

        DB::table("password_resets")
            ->where(["email" => $request->email])
            ->delete();
        Session()->flush();
        return redirect()
            ->route("admin.login")
            ->with("message", "Your password has been changed!");
        // return redirect()->route('admin.login.form')->with('message', 'Your password has been changed!');
    }
    public function viewPackage($packageId)
    {
        $package = Pakage::find($packageId);
        if (empty($package)) {
            return redirect()
                ->route("package.list")
                ->with("message", "No Package Found");
        }
        return view("admin/packages/view-package", compact("package"));
    }

    public function socialmediaSetting(Request $request)
    {
        $search = $request->query("search_keyword");
        if ($search == "") {
            $social_media = SocialMedia::latest()->paginate(20);
        } else {
            $social_media = SocialMedia::where(
                "name",
                "Like",
                "%" . $search . "%"
            )
                ->latest()
                ->paginate(20);
        }

        return view(
            "admin.settings_socialmedia.socialmedia",
            compact("social_media")
        );
    }

    public function socialmediaSettingCreate(Request $request)
    {
        $this->validate($request, [
            "name" => "required|unique:social_media,name",
            "link" => "required|url",
        ]);
        $file = $request->file("icon");
        $icon = "";
        if ($file) {
            $this->validate($request, [
                "icon" => "mimes:jpeg,jpg,png,webp",
            ]);
            $icon = $fileName =
                time() . $request->file("icon")->getClientOriginalName();
            $request->icon->move(
                public_path("assets\uploads\socialmedia"),
                $fileName
            );
        } else {
            $icon = $request->icon;
        }
        SocialMedia::create([
            "name" => $request->name,
            "icon" => $icon,
            "link" => $request->link,
            "type" => $request->file_type,
        ]);
        return redirect("/admin/socialmedia")->with(
            "message",
            "Social media created successfully."
        );
    }

    public function socialmediaSettingUpdate(Request $request)
    {
        if ($request->table_id) {
            $data = SocialMedia::find($request->table_id);
            $this->validate($request, [
                "name" =>
                    "required|unique:social_media,name," . $request->table_id,
                "link" => "required|url",
            ]);

            $file = $request->file("icon");
            if ($file) {
                if ($data->icon != "" && $data->type == "image") {
                    $file_path =
                        public_path("assets/uploads/socialmedia/") .
                        $data->icon;
                    File::delete($file_path);
                }

                $this->validate($request, [
                    "icon" => "mimes:jpeg,jpg,png,webp",
                ]);
                $fileName =
                    time() . $request->file("icon")->getClientOriginalName();
                $request->icon->move(
                    public_path("assets/uploads/socialmedia/"),
                    $fileName
                );
            }

            if ($request->file_type == "image") {
                SocialMedia::where("id", $request->table_id)->update([
                    "name" => $request->name,
                    "icon" => isset($fileName) ? $fileName : $data->icon,
                    "link" => $request->link,
                    "type" => $request->file_type,
                ]);
            } else {
                if ($request->icon != null) {
                    if ($data->icon != "" && $data->type == "image") {
                        $file_path =
                            public_path("assets/uploads/socialmedia/") .
                            $data->icon;
                        File::delete($file_path);
                    }

                    $icon = isset($fileName) ? $fileName : $request->icon;
                } else {
                    $icon = isset($fileName) ? $fileName : $data->icon;
                }

                SocialMedia::where("id", $request->table_id)->update([
                    "name" => $request->name,
                    "icon" => $icon,
                    "link" => $request->link,
                    "type" => $request->file_type,
                ]);
            }
            return redirect()
                ->route("admin.socialmedia")
                ->with("message", "Social media updated successfully");
        } else {
            return redirect()
                ->route("admin.socialmedia")
                ->with("message", "Social media updated successfully");
        }
    }

    public function socialmediadestroy($id)
    {
        $data = SocialMedia::find($id);
        if ($data) {
            if ($data->icon != "" && $data->type == "image") {
                $file_path =
                    public_path("assets/uploads/socialmedia/") . $data->icon;
                File::delete($file_path);
            }
            $data->delete();
        }
        return redirect("/admin/socialmedia")->with(
            "message",
            "Social media deleted successfully."
        );
    }

    public function settings()
    {
        $settings = Generalsetting::get();
        $SearchCondition = SearchCondition::pluck("search_name")->all();
         $stripe_status = StripeStatus::pluck('status')->first(); 
        return view(
            "admin.settings_socialmedia.settings",
            compact("settings", "SearchCondition","stripe_status")
        );
    }

    public function storesettings(Request $request)
    {
        $settings = Generalsetting::all();

        $cnt = 1;
        if ($settings) {
            // 'phone_number' => 'nullable|regex:/[0-9]{9}/',

            $this->validate($request, [
                "notification_email" => "nullable|regex:/(.+)@(.+)\.(.+)/i",
                "website_url" => "nullable",
                "company_email" => "nullable|regex:/(.+)@(.+)\.(.+)/i",
                // 'hotline' => 'nullable|regex:/[0-9]{9}/',
                // 'site_currency' => 'nullable',
                // 'site_currency_icon' => 'nullable',
                // 'compony_address' => 'nullable',
                // 'shipping_charge_149' => 'required|integer|min:0',
                // 'shipping_charge_499' => 'nullable|integer|min:0',
            ]);
            foreach ($settings as $items) {
                $fieldname = $items->item;

                $file = $request->file($fieldname);
                if ($file) {
                    //Delete existing image coDe--
                    $gensettings = Generalsetting::find($items->id);
                    if (!empty($gensettings) && $gensettings->value != "") {
                        $file_path =
                            public_path("/assets/uploads/logo/") .
                            $gensettings->value;
                        File::delete($file_path);
                    }

                    $this->validate($request, [
                        $fieldname => "mimes:jpeg,jpg,png,webp,svg|max:2048",
                    ]);

                    $fileName =
                        "c_logo_" .
                        $cnt .
                        time() .
                        "." .
                        $request->$fieldname->extension();
                    $request->$fieldname->move(
                        public_path("/assets/uploads/logo/"),
                        $fileName
                    );

                    Generalsetting::find($items->id)->update([
                        "value" => $fileName,
                    ]);
                } elseif (
                    $items->item != "company_logo" &&
                    $items->item != "footer_logo"
                ) {
                    switch ($items->item) {
                        case $items->item:
                            Generalsetting::find($items->id)->update([
                                "value" => $request->$fieldname,
                            ]);
                            break;
                    }
                }
                $cnt++;
            }
            return redirect()
                ->route("admin.settings")
                ->with("success", "Settings updated successfully.");
        } else {
            return redirect()
                ->route("admin.settings")
                ->withErrors("General settings details not found.");
        }
    }

    public function storesearchcriteria(Request $request)
    {
        $SearchCondition = $request->get("searchcriteria");

        SearchCondition::truncate();
        if (!empty($SearchCondition)) {
            foreach ($SearchCondition as $data) {
                $array = ["search_name" => $data];
                SearchCondition::create($array);
            }
        }
        return redirect()
            ->route("admin.settings")
            ->with("success", "Search criterias updated successfully.");
    }

    public function remove_image(Request $request)
    {
        if ($request->id != "") {
            $Generalsetting = Generalsetting::find($request->id);
            if ($Generalsetting) {
                if ($Generalsetting->value != "") {
                    $imagefile =
                        public_path("/assets/uploads/logo/") .
                        $Generalsetting->value;
                    File::delete($imagefile);
                    Generalsetting::find($request->id)->update(["value" => ""]);

                    $returnArray["result"] = true;
                    $returnArray["message"] = "Image removed successfully.";
                } else {
                    $returnArray["result"] = false;
                    $returnArray["message"] = "Failed. Image not found.";
                }
            } else {
                $returnArray["result"] = false;
                $returnArray["message"] = "Failed. Details not found.";
            }
        } else {
            $returnArray["result"] = false;
            $returnArray["message"] =
                "Failed. Something went wrong id not found.";
        }
        return response()->json($returnArray);
    }

    public function removeprofilepic()
    {
        $admin = Admin::findOrFail(Auth::guard("admin")->user()->id);
        $path = public_path() . "/uploads/userImages/" . $admin->profile_pic;
        $data = $admin->update(["profile_pic" => ""]);
        if (file_exists($path))
        @unlink($path);
        return json_encode($data);
    }
}
