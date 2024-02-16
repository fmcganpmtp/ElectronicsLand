<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\ProfileAccountDeleteRequest;
use App\Models\SellerProduct;
use App\User;
use App\Models\Category;
use App\Http\Controllers\FrontEnd\PublicMiddlewareController;
use Illuminate\Http\Request;

class RequestDeleteController extends Controller
{
    protected $PublicMiddlewareController;
    public function __construct(PublicMiddlewareController $PublicMiddlewareController) 
    {
        $this->middleware('auth:admin');
        $this->PublicMiddlewareController = $PublicMiddlewareController;
    }
    //display content page
    public function index(Request $request) {
        return view('admin.account_request_delete.request_delete');
    } 
    //function changes status of delete request
    public function adminprofiledelete (Request $request)
    {
        $status=$request->get('status');
        $id=$request->get('id');
        $requestdelete=  ProfileAccountDeleteRequest::find($id);
        $requestdelete->status =$status;
        $requestdelete->save();
        if($status=='Deleted')
        {
            //status changing
            $User=  User::find($requestdelete->user_id);
            $User->status ='Deleted';
            $update=$User->save();
            DB::table('users')->where('parent_id',$requestdelete->user_id)->update(['status'=>'Deleted']);
            //deleted user product status changed to deleted
            DB::table('seller_products')->where('user_id', $requestdelete->user_id)->update(array('status' => 'deleted')); 

            $seller_active_products=SellerProduct::where('user_id',$requestdelete->user_id)->get();
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

        }
        
        echo json_encode("Status Changed");
    } 
     
    //ajax call returns all delete request list
    public function getrequestdeletelist(Request $request)
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
        $searchValue=$request->get('search_key');
        $status=$request->get('status');
        //get all count of delete request
        $totalRecords =ProfileAccountDeleteRequest::select('count(*) as allcount')
            ->when($request->get('status')!='', function ($query) use ($request) {
                $query->where('status',$request->get('status'));
            })
            ->count();
        //get all count of filtered data
        $totalRecordswithFilter = ProfileAccountDeleteRequest::leftJoin('users', 'profile_account_delete_requests.user_id', '=', 'users.id')
            ->leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
            ->when($request->get('status')!='', function ($query) use ($request) {
                $query->where('profile_account_delete_requests.status',$request->get('status'));
            })
            ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where(DB::raw('CONCAT_WS(users.name,email,phone,buyer_companies.company_name)'), 'LIKE','%'.$searchValue.'%');
            })
            ->count();       

        // Get records, also we have included search filter as well
        $records = ProfileAccountDeleteRequest::leftJoin('users', 'profile_account_delete_requests.user_id', '=', 'users.id')
            ->leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
            ->select('users.*','buyer_companies.company_name','profile_account_delete_requests.status as reqstatus','profile_account_delete_requests.reason','profile_account_delete_requests.created_at' ,'profile_account_delete_requests.id as req_id')  
            ->when($request->get('status')!='', function ($query) use ($request) {
                $query->where('profile_account_delete_requests.status',$request->get('status'));
            })
            ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where(DB::raw('CONCAT_WS(users.name,email,phone,buyer_companies.company_name)'), 'LIKE','%'.$searchValue.'%');
            })
            ->orderBy($columnName,$columnSortOrder)      
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
           
        foreach ($records as $record) {
            $data_arr[] = array(
                "id" => $record->req_id,
                "name" =>$record->name, 
                "company_name" => $record->company_name,
                "phone" => $record->phone,
                "email" => $record->email,
                "reason" => $record->reason,
                'reqstatus'=>$record->reqstatus,
                'created_at' => date('d-m-Y', strtotime($record->created_at))
            );
             
         }
        //response json array
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);       
    }	
}
