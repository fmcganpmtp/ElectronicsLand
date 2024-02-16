<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use File;
use App\Models\OrderDetail;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    } 
    //load package display page
    public function index() {

        $package=Package::get();
        return view('admin.orders.order-list',compact('package'));

    }
    //ajax call display all orders
    public function getorderslist(Request $request)
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
        $package=$request->get('package');
        //total orders count
        $totalRecords =OrderDetail::select('count(*) as allcount')->count();
        //total filtered data count
        $totalRecordswithFilter = OrderDetail::leftJoin('users', 'users.id', '=', 'order_details.user_id')
           ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where(DB::raw('CONCAT_WS(users.name,users.email,order_details.email,order_details.phone,users.phone)'), 'LIKE','%'.$searchValue.'%');
           })
       
            ->when($request->get('package')!='', function ($query) use ($request) {
                 $query->where('order_details.package_id',$request->get('package'));
            })
        ->count();       

        // Get records, also we have included search filter as well
        $records = OrderDetail::leftJoin('subscriptions', 'subscriptions.order_id', '=', 'order_details.id')
        ->leftJoin('users', 'users.id', '=', 'order_details.user_id')
        ->leftJoin('packages', 'packages.id', '=', 'order_details.package_id')
        ->orderBy($columnName,$columnSortOrder) 
        ->when($searchValue!='', function ($query) use ($searchValue) {
            $query->where(DB::raw('CONCAT_WS(users.name,users.email,order_details.email,order_details.phone,users.phone)'), 'LIKE','%'.$searchValue.'%');
           })
        ->when($request->get('package')!='', function ($query) use ($request) {
            $query->where('order_details.package_id',$request->get('package'));
        })  
        ->select('order_details.*',DB::raw("users.name as users_name")
        ,DB::raw("packages.name as package_name"),DB::raw("(CASE WHEN subscriptions.order_id > 0 THEN 'Yes' ELSE 'No' END)  as anyval"),
        DB::raw("users.created_at as created_date"),DB::raw("packages.subscription_type as subscription_type")
        ,DB::raw("packages.package_basic_price as package_basic_price"),
        DB::raw("packages.package_validity as package_validity"),
        DB::raw("packages.user_type as user_type"),DB::raw("subscriptions.expairy_date as expairy_date"))  
        ->skip($start)
        ->take($rowperpage)
        ->get();
        $data_arr = array();
           
        foreach ($records as $record) {
            $data_arr[] = array(
                "id" => $record->id,
                "users_name" => $record->users_name,
                "package_name" => $record->package_name,
                "anyval" => $record->anyval,
                "subscription_type" => $record->subscription_type,
                "package_basic_price" => $record->package_basic_price,
                "package_validity" => $record->package_validity,
                "created_date" => date('d-m-Y', strtotime($record->created_date)),
                "user_type" => $record->user_type, 
                "expairy_date" =>  date('d-m-Y', strtotime($record->expairy_date)),  
                "email" => $record->email,
                "phone" => $record->phone,
                "address" => $record->address        
                );
             
         }
    //ajax response data
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);       
    }
}    