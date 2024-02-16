<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use File;
use App\Models\KycFile;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
class AdminkycController extends Controller
{
    //loading page
    public function listuserkycdocs(Request $request)
    {
        $search_key = $request->get("search_key");

        return view("admin.kyc.list-kyc-docs", compact("search_key"));
    }
    //fetching kyc list
    public function getkyclist(Request $request)
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
        //total records count
        $totalRecords = KycFile::leftJoin("users", "users.id","=", "kyc_files.user_id" )
            ->select("count(*) as allcount")
            ->where("users.status", "<>", "Deleted")
            ->count();
        //kyc filtered count
        $totalRecordswithFilter = KycFile::leftJoin(
            "users",
            "users.id",
            "=",
            "kyc_files.user_id"
        )
            //->where('kyc_files.status','!=','')
            ->when($request->get("status") != "", function ($query) use ( $request  ) {
                $query->where("kyc_files.status", $request->get("status"));
            })
            ->when($searchValue != "", function ($query) use ($searchValue) {
                $query ->where("users.name", "Like", "%" . $searchValue . "%");
                   // ->where( "kyc_files.file_type", "Like", "%" . $searchValue . "%" )
            })
            ->where("users.status", "<>", "Deleted")

            ->count();

        // Get records, also we have included search filter as well
        $records = KycFile::leftJoin( "users","users.id", "=","kyc_files.user_id")
            ->when($request->get("status") != "", function ($query) use (  $request ) {
                $query->where("kyc_files.status", $request->get("status"));
            })
            ->when($searchValue != "", function ($query) use ($searchValue) {
                $query  ->where("users.name", "Like", "%" . $searchValue . "%");
                      // ->where( "kyc_files.file_type", "Like", "%" . $searchValue . "%" );
            })
            ->where("users.status", "<>", "Deleted");
            if(!empty($columnSortOrder))
            $records = $records->orderBy($columnName, $columnSortOrder);
            else
            $records = $records->orderBy('kyc_files.id', 'DESC');
            $records = $records->select("kyc_files.*", "users.name", "users.id as user_id")
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = [];

        foreach ($records as $record) {
            $statusicon = $Myprovince =
                $record->status == "Active"
                    ? '<span style="color:white;background-color:green;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">' .
                        $record->status .
                        "</span>"
                    : ($record->status == "In-Active"
                        ? '<span style="color:white;background-color:red;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">' .
                            $record->status .
                            "</span>"
                        : ($record->status == "Rejected"
                            ? '<span style="color:white;background-color:orange;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">' .
                                $record->status .
                                "</span>"
                            : ""));

            $data_arr[] = [
                "id" => $record->id,
                "name" => $record->name,
                "user_id" => $record->user_id,
                "file_type" => $record->file_type . "<br>" . $statusicon,
                "status" => $record->status,
                "reason" => $record->reason,
                "created_at" => date(
                    "d-m-Y h:m:s a",
                    strtotime($record->created_at)
                ),
                "file_path" => $record->file_path,
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
    // fetching aprroved kyc docs
    public function kycApprove($kyc_id)
    {
        $data = KycFile::select("*")
            ->where("id", $kyc_id)
            ->get();
        return view("admin.kyc.view_kyc_docs", compact("data"));
    }
    //approve kyc docs and change user verification status
    public function approveuserdocs(Request $request)
    {
        $input = [
            "status" => "Active",
            "reason" => "",
        ];
        $count = KycFile::where("id", $request->get("id"))->update($input);

        $kycdocs_varified = DB::table("kyc_files")
            ->select(DB::raw("count('*') as status_cnt"))
            ->where("user_id", $request->get("user_id"))
            ->where("status", "Active")
            ->pluck("status_cnt")
            ->first();
        $email_varified = DB::table("users")
            ->select(DB::raw("count('*') as status_cnt"))
            ->where("id", $request->get("user_id"))
            ->where("email_status", "Yes")
            ->pluck("status_cnt")
            ->first();

        if ($kycdocs_varified + $email_varified == 4) {
            $input_varification = [
                "varification_status" => "varified",
            ];
        } else {
            $input_varification = [
                "varification_status" => "not varified",
            ];
        }
        //update verification status
        DB::table("users")
            ->where("id", $request->get("user_id"))
            ->update($input_varification);

        return $count;
    }
    //individual user documents rejection
    public function rejectdocs(Request $request)
    {
        $input_varification = [
            "varification_status" => "not varified",
        ];
        DB::table("users")
            ->where("id", $request->get("user_id"))
            ->update($input_varification);

        $input = [
            "status" => "Rejected",
            "reason" => $request->get("reason"),
        ];

        $count = KycFile::where("id", $request->get("id"))->update($input);

        return $count;
    }
    //fetching all users ,those do not upload all docs
      public function availbleUsers(request $request) {
        $users = User::leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
            ->where(DB::raw('UPPER(CONCAT(users.name,COALESCE(buyer_companies.company_name,"")))'), 'LIKE','%'.strtoupper($request->term).'%')
            ->select("users.id",DB::raw("CONCAT_WS(' - ',users.name,buyer_companies.company_name) as name"),DB::raw("(SELECT count(*) FROM kyc_files WHERE kyc_files.user_id = users.id and kyc_files.status='Active') as doc_cnt"))
            ->where('users.status','<>','Deleted')
            ->where('users.seller_type','Master')
            ->orderBy('users.name','Asc')
            ->get();
        return $users->where('doc_cnt','<',3)->take(20);
    }
}
