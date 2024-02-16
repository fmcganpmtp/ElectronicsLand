<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\AdminRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class AdminRoleController extends Controller
{
    //loading page
    public function createAdminRole()
    {
        $permissions = Permission::all();
        return view(
            "admin.admin_roles.create-admin-role",
            compact("permissions")
        );
    }
    //save admin role in db
    public function saveAdminRole(Request $request)
    {
        request()->validate([
            "role_name" => [
                "required",
                "string",
                "max:255",
                "unique:admin_roles",
            ],
            "permission" => ["required"],
        ]);
        $input = $request->all();
        $permission = $request->input("permission");
        $permission = implode(",", $permission);
        $input["permission"] = $permission;
        AdminRole::create($input);
        return redirect()
            ->route("list.admin.roles")
            ->with("message", "Admin Role Created");
    }
    //fetching admin role
    public function listAdminRoles()
    {
        $adminroles = AdminRole::paginate(20);

        return view("admin.admin_roles.list-admin-role", compact("adminroles"));
    }
    //fetching data table admin role data
    public function getrolevalues(Request $request)
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
        $searchValue = $search_arr["value"]; // Search value
        // total admin roles
        $totalRecords = AdminRole::select("count(*) as allcount")->count();
        // filtered data count
        $totalRecordswithFilter = AdminRole::select("count(*) as allcount")
            ->where("role_name", "like", "%" . $searchValue . "%")
            ->count();

        // Get records, also we have included search filter as well
        $records = AdminRole::select(
            "admin_roles.*",
            DB::raw("(SELECT count(*) FROM admins
        WHERE admins.adminrole = admin_roles.id group by  admin_roles.id) as cnt")
        )
            ->orderBy($columnName, $columnSortOrder)
            ->where("role_name", "like", "%" . $searchValue . "%")
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = [];
        foreach ($records as $record) {
            $data_arr[] = [
                "id" => $record->id,
                "role_name" => $record->role_name,
                "created_at" => date(
                    "d-m-Y h:m:s a",
                    strtotime($record->created_at)
                ),
                "cnt" => $record->cnt == "" ? 0 : $record->cnt,
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
    //edit admin roles
    public function editAdminRole($adminRoleId)
    {
        $adminRole = AdminRole::find($adminRoleId);
        $permissions = Permission::all();
        if (empty($adminRole)) {
            return redirect()
                ->route("list.admin.roles")
                ->with("message", "Admin Role Not Exits");
        }
        return view(
            "admin.admin_roles.edit-AdminRole",
            compact("adminRole", "permissions")
        );
    }
    //update admin roles
    public function updateAdminRole(Request $request)
    {
        request()->validate([
            "role_name" => [
                "required",
                "string",
                "max:255",
                Rule::unique("admin_roles")->ignore($request->get("id")),
            ],
            "permission" => ["required"],
        ]);

        $adminrole_id = $request->get("id");
        $adminRole = AdminRole::find($adminrole_id);

        $input = $request->all();
        $permission = $request->input("permission");
        $permission = implode(",", $permission);
        $input["permission"] = $permission;
        $adminRole->update($input);
        return redirect()
            ->route("list.admin.roles")
            ->with("message", "Role Data Updated");
    }
    //delete single admin role
    public function deleteAdminRole($adminRoleId)
    {
        $adminRole = AdminRole::find($adminRoleId);
        if (empty($adminRole)) {
            return redirect()
                ->route("list.admin.roles")
                ->with("message", "Admin role not Exists");
        }
        $adminRole->delete();
        return redirect()
            ->route("list.admin.roles")
            ->with("message", "Admin Role Deleted!");
    }
}
