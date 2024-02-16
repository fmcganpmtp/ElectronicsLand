<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use App\User;
use App\Models\Message;
use App\Models\SellerProduct;
use App\Models\SellerProductTemp;
use App\Models\Mynetworks;
use App\Models\BusinessInsight;
use App\Models\LoginActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\FrontEnd\PublicMiddlewareController;

class UsersInsightController extends Controller
{
    protected $PublicMiddlewareController;
    public function __construct(PublicMiddlewareController $PublicMiddlewareController){
        $this->PublicMiddlewareController = $PublicMiddlewareController;
    }
     public function index()
    {
        
        return view("admin.seller.seller_insight_report");
        
    }
    public function buyerindex()
    {
        return view("admin.buyer.buyer_insight_report");
    }
    public function getsellersinsightlist(Request $request)
    {  
        
        $active_sellers=$this->PublicMiddlewareController->getexpireduserslist(); 

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
        $totalRecords =User::select('count(*) as allcount')
            ->where('usertype','seller')->where('seller_type','Master')
            ->where('users.status','<>','Deleted')->count();
        
        $totalRecordswithFilter = User::leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
           ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where(DB::raw('CONCAT_WS(users.name,email,phone,buyer_companies.company_name)'), 'LIKE','%'.$searchValue.'%');
            })
            ->where('usertype','seller') ->where('seller_type','Master') 
            ->where('users.status','<>','Deleted')        
            ->count();       

        // Get records, also we have included search filter as well
        $records = User::leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
            ->select('users.*','buyer_companies.company_name')  
            ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where(DB::raw('CONCAT_WS(users.name,email,phone,buyer_companies.company_name)'), 'LIKE','%'.$searchValue.'%');
            })
            ->where('usertype','seller')->where('seller_type','Master') 
            ->where('users.status','<>','Deleted')       
           // ->orderBy($columnName,$columnSortOrder)
           // ->skip($start)
           // ->take($rowperpage)
            ->get();
          
        $data_arr = array();       
        foreach ($records as $record) {

            $active_prd_cnt=SellerProduct::where('user_id',$record->id)
                    ->where('status','active')->count();
            $pending_prd_cnt=SellerProductTemp::where('user_id',$record->id)
                    ->where('status','pending')->count();
            $networks=Mynetworks::where('user_id',$record->id)->pluck('mynetwork_id')->first();
            if($networks!='')
            {
                $arrayData=explode(',', rtrim($networks,','));
                $network_cnt=User::whereIn('id',$arrayData)
                ->whereIn('users.id',$active_sellers)->count();  
            }
            else
                $network_cnt=0; 
            $mesage_sent=Message::where('from_user',$record->id)->count();
            $message_received=Message::where('to_user',$record->id)->count();
            $logincount=LoginActivity::where('user_id',$record->id)->count();

            $data_arr[] = array(
                "id" => $record->id,
                "name" =>$record->name, 
                "company_name" => $record->company_name, 
                "active_prd_cnt" =>$active_prd_cnt, 
                "pending_prd_cnt" =>$pending_prd_cnt,
                "network_cnt" =>$network_cnt,   
                "mesage_sent" =>$mesage_sent,   
                "message_received" =>$message_received,
                "login_cnt" => $logincount
            );  
        }

      
   
        usort($data_arr, function ($a, $b) use ($columnSortOrder,$columnName) {

            if ($columnName != 'name' && $columnName != 'company_name') 
            {
                if ($columnSortOrder === 'asc') 
                    return $a[$columnName] - $b[$columnName];
                else 
                    return $b[$columnName] - $a[$columnName];
            }
            else
            {
                $valueA = $a[$columnName];
                $valueB = $b[$columnName];

                if ($columnSortOrder === 'asc') 
                    return strcasecmp($valueA, $valueB); 
                else 
                    return strcasecmp($valueB, $valueA); 
                
            }

            
        });
    
        $paginatedData = array_slice($data_arr, $start, $rowperpage);

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $paginatedData,
        );
        echo json_encode($response);       
    }   

    public function getbuyersinsightlist(Request $request)
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

        $active_sellers=$this->PublicMiddlewareController->getexpireduserslist(); 

        $totalRecords =User::select('count(*) as allcount')
            ->where('usertype','buyer')->where('seller_type','Master')
            ->where('users.status','<>','Deleted')->count();
        
        $totalRecordswithFilter = User::leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
           ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where(DB::raw('CONCAT_WS(users.name,email,phone,buyer_companies.company_name)'), 'LIKE','%'.$searchValue.'%');
            })
            ->where('usertype','buyer') ->where('seller_type','Master') 
            ->where('users.status','<>','Deleted')        
            ->count();       

        // Get records, also we have included search filter as well
        $records = User::leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
            ->select('users.*','buyer_companies.company_name')  
            ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where(DB::raw('CONCAT_WS(users.name,email,phone,buyer_companies.company_name)'), 'LIKE','%'.$searchValue.'%');
            })
            ->where('usertype','buyer')->where('seller_type','Master') 
            ->where('users.status','<>','Deleted')       
            // ->orderBy($columnName,$columnSortOrder)
            // ->skip($start)
            // ->take($rowperpage)
            ->get();
        $data_arr = array();       
        foreach ($records as $record) {

            $product_view=BusinessInsight::where('user_id',$record->id)->count();
            $networks=Mynetworks::where('user_id',$record->id)->pluck('mynetwork_id')->first();
            if($networks!='')
            {
                $arrayData=explode(',', rtrim($networks,','));
                $network_cnt=User::whereIn('id',$arrayData)
                ->whereIn('users.id',$active_sellers)->count();  
            }
            else
                $network_cnt=0;
            $mesage_sent=Message::where('from_user',$record->id)->count();
            $message_received=Message::where('to_user',$record->id)->count();
            $logincount=LoginActivity::where('user_id',$record->id)->count();


            $data_arr[] = array(
                "id" => $record->id,
                "name" =>$record->name, 
                "company_name" => $record->company_name, 
                "product_view" =>$product_view, 
                "network_cnt" =>$network_cnt,   
                "mesage_sent" =>$mesage_sent,   
                "message_received" =>$message_received,
                "login_cnt" => $logincount
            );  
            }


    
   
        usort($data_arr, function ($a, $b) use ($columnSortOrder,$columnName) {

            if ($columnName != 'name' && $columnName != 'company_name') 
            {
                if ($columnSortOrder === 'asc') 
                    return $a[$columnName] - $b[$columnName];
                else 
                    return $b[$columnName] - $a[$columnName];
            }
            else
            {
                $valueA = $a[$columnName];
                $valueB = $b[$columnName];

                if ($columnSortOrder === 'asc') 
                    return strcasecmp($valueA, $valueB); 
                else 
                    return strcasecmp($valueB, $valueA); 
                
            }

            
        });
    
        $paginatedData = array_slice($data_arr, $start, $rowperpage);

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $paginatedData,
        );

       
        echo json_encode($response);       
    } 
}
