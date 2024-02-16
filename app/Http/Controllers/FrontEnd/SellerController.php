<?php
namespace App\Http\Controllers\FrontEnd; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\User;
use App\Models\ProductImage;
use App\Models\SellerProduct;
use App\Models\SellerProductTemp;
use App\Models\SellerProductImageTemp;
use App\Models\SellerProductImage;
use App\Models\Wishlist;
use App\Models\ProductRequest;
use App\Models\ProductReview;
use App\Models\Productbrand;
use App\Models\BusinessInsight;
use App\Models\Currency;
use App\Models\ProfileAccountDeleteRequest;
use File;
use URL;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Imports\SellerProductImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Illuminate\Support\Facades\Mail;
use App\Models\Product;
use App\Models\Country;
use App\Http\Controllers\FrontEnd\PublicMiddlewareController;
use Illuminate\Http\Request;
use Response;
use Carbon\Carbon;

class SellerController extends Controller
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
    public function SellerProductList()
    {
        if (Auth::guard("user")->check()) {
            if (!$this->PublicMiddlewareController->checkUserlogin()) {
                Auth::guard("user")->logout();
                return redirect(route("home"))->with("message","Access Denied");
            }
            if (!$this->PublicMiddlewareController->checkUserContactValidity()) {
                Auth::guard("user")->logout();
                return redirect(route("home"));
            }
            $user_id = Auth::guard("user")->user()->id;
            $usertype = Auth::guard("user")->user()->usertype;
            $user = User::find($user_id);
            $categories = Category::where("parent_id", null)->where('name','<>','')->orderBy('name','ASC')->get();
        
            //if not seller redirect to home
            if($this->isMobile()) { 
                return view("frontEnd.seller.ListProducts_mobile",compact("user", "categories"));
            } else {
                return view( "frontEnd.seller.ListProducts", compact("user", "categories") );
            }
        }else {
            return redirect()->route("home");
        }
    }

  public function getSPendingProductlist(Request $request)
    {
        $usertype = Auth::guard("user")->user()->usertype;
        if ($usertype != "seller") {
            return redirect()->route("home");
        }
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
        $category_id=$request->get("category_id_pend");
        $userId = Auth::guard("user")->user()->id;
        if (Auth::guard("user")->user()->seller_type != "Master") {
            $userId = Auth::guard("user")->user()->parent_id;
        }
        $totalRecords = SellerProductTemp::select("count(*) as allcount")
          //  ->where("status","!=","active")
            ->where("user_id", $userId)
            ->count();
        /*$totalRecordswithFilter = SellerProductTemp::select(
            "count(*) as allcount"
        )
           // ->where("status","!=","active")
            ->where("user_id", $userId)
             ->when($category_id!='', function ($query) use ($category_id) {
                $query->where(
                    DB::raw(
                        'UPPER(COALESCE(seller_product_temps.categories,""))'
                    ), "LIKE", "%" . strtoupper($category_id) . "%"
                );
            })
             ->when($request->get('stock_pend') !=null, function ($query) use ($request) {
                 if($request->get('stock_pend')=="instock")
                     {  
                         $query->where(function ($query){
                             $query->where("stock_count",">",0)->orWhere('unlimited_stock','Unlimited')->orWhere('stock_count',null)->orWhere('unlimited_stock',null);
                            });}
                    else
                      {
                          $query->where(function ($query){
                              //$query-> where("stock_count","<=",0)->orWhereNull('stock_count')->where('unlimited_stock','<>','Unlimited');
                              $query-> where("stock_count","<=",0)->where('unlimited_stock','<>','Unlimited');
                            });
                      }
                        
            })
            ->where(function ($query) use ($request) {
                if ($request->get("search_key_pend") != "") {
                    $query->where(
                        "name",
                        "Like",
                        "%" . $request->get("search_key_pend") . "%"
                    );
                }
                
            })
            ->count();*/
            
        $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];        

        // Get records, also we have included search filter as well
        $records = SellerProductTemp::select("seller_product_temps.*")
            //  ->where("status","!=", "active")
            ->where("user_id", $userId)
             ->when($category_id!='', function ($query) use ($category_id) {
                $query->where(
                    DB::raw(
                        'UPPER(COALESCE(seller_product_temps.categories,""))'
                    ), "LIKE", "%" . strtoupper($category_id) . "%"
                );
            })
             ->when($request->get('stock_pend') !=null, function ($query) use ($request) {
                 if($request->get('stock_pend')=="instock")
                     {  
                         $query->where(function ($query){
                             $query->where("stock_count",">",0)->orWhere('unlimited_stock','Unlimited')->orWhere('stock_count',null)->orWhere('unlimited_stock',null);
                            });
                         
                     }
                    else
                     {
                          $query->where(function ($query){
                             // $query-> where("stock_count","<=",0)->orWhereNull('stock_count')->where('unlimited_stock','<>','Unlimited');
                              $query-> where("stock_count","<=",0)->where('unlimited_stock','<>','Unlimited');
                            });
                     }
                        
            })
            ->where(function ($query) use ($request) {
                if ($request->get("search_key_pend") != "") {
                    $query->where("name","Like","%" . $request->get("search_key_pend") . "%"
                    );
                }
            });
            
			
		$totalRecordswithFilter	= $records->count();
		$records=$records->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();
           
        $data_arr = [];

        foreach ($records as $record) {
           $strcat  = ""; 
           $strcat = $record->categories;
           $strimg ='<div class="table-prof"><img style=" width:100px !important;" class="pr_img" src="'.asset('/uploads/defaultImages/no_image.jpg').'/"></div>';
            foreach ($record->SellerProductImageTemp as $productimage) {
                if ($productimage->thumbnail == "yes") {
                     if (filter_var($productimage->image_path, FILTER_VALIDATE_URL) !== false)  
                          $strimg= '<div class="table-prof"><img  class="pr_img" src="'.$productimage->image_path.'"/></div>';
                       
                      elseif(filter_var($productimage->image_path, FILTER_VALIDATE_URL) === FALSE)
                      {
                      $validB64 = preg_match("/data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+).base64,.*/", $productimage->image_path);
                         if($validB64) 
                             $strimg= '<div class="table-prof"><img  class="pr_img" src="'.$productimage->image_path.'"/></div>';
                         else 
                         $strimg= '<div class="table-prof"><img  class="pr_img" src="'.asset("/uploads/productImages/").'/'.$productimage->image_path.'"/></div>';
                      }       
                }
            }
            $currency = DB::table("currencies")
                    ->where("id", $record->currency_id)
                    ->pluck("symbol")
                    ->first();
            $data_arr[] = [
                "id" => $record->id,
				"status" => ucfirst($record->status),
                "categories" => $strcat,
                "name" => $record->name,
                "product_price"=>$record->price_on_request=='Price on request'?'Price on request':$currency.' '.$record->product_price,
                "strimg" => $strimg,
                "created_at" => date(
                    "d/m/Y   \&\\n\b\s\p\; \&\\n\b\s\p\; \&\\n\b\s\p\;  g:i A",
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


    public function AddSellerProduct()
    {
        if (Auth::guard("user")->check()) {
            if (!$this->PublicMiddlewareController->checkUserlogin()) {
                Auth::guard("user")->logout();
                return redirect(route("home"))->with(
                    "message",
                    "Access Denied"
                );
            }
            if (
                !$this->PublicMiddlewareController->checkUserContactValidity()
            ) {
                Auth::guard("user")->logout();
                return redirect(route("home"));
            }
        
        $user_id = Auth::guard("user")->user()->id;
        $usertype = Auth::guard("user")->user()->usertype;

        $user = User::find($user_id);
        $varients = SellerProduct::all();
        $Productbrand = Productbrand::orderBy("name")->get();
        $countries = Country::all();
        $currencies = Currency::select("id", "shortcode", "symbol")->get();
        $categories = Category::where("parent_id", null)
            ->orderBy("name")
            ->get();
        if ($usertype == "seller") {
            
            if($this->isMobile()) { 
           return view( "frontEnd.seller.AddSellerProduct_mobile",compact("user","varients","countries","categories","Productbrand","currencies"));
        } else {
            return view( "frontEnd.seller.AddSellerProduct",compact("user","varients","countries","categories","Productbrand","currencies"));
        }
        } 
        }
        else {
            return redirect()->route("home");
        }
    }
    
    
    public function AddProductRequest()
    {
        if (Auth::guard("user")->check()) {
            if (!$this->PublicMiddlewareController->checkUserlogin()) {
                Auth::guard("user")->logout();
                return redirect(route("home"))->with(
                    "message",
                    "Access Denied"
                );
            }
            if (!$this->PublicMiddlewareController->checkUserContactValidity()) {
                Auth::guard("user")->logout();
                return redirect(route("home"));
            }
        
        $user_id = Auth::guard("user")->user()->id;
        $usertype = Auth::guard("user")->user()->usertype;
       /* if ($usertype != "seller" && $usertype != "buyer") { return redirect()->route("home"); }*/
        $user = User::find($user_id);
        
      
        $countries = Country::all();
        $categories = Category::where("parent_id", null)
            ->orderBy("name")
            ->get();
            $Req = '';
            
            if($this->isMobile()) { 
           return view( "frontEnd.seller.AddProductRequest_mobile",compact("user","countries","categories","Req"));
        } else {
            return view( "frontEnd.seller.AddProductRequest",compact("user","countries","categories","Req"));
        }
        } 
       
        else {
            return redirect()->route("home");
        }
    }
    
    public function EditProductRequest($req_id)
    { 
        if (Auth::guard("user")->check()) {
            if (!$this->PublicMiddlewareController->checkUserlogin()) {
                Auth::guard("user")->logout();
                return redirect(route("home"))->with(
                    "message",
                    "Access Denied"
                );
            }
            if (!$this->PublicMiddlewareController->checkUserContactValidity()) {
                Auth::guard("user")->logout();
                return redirect(route("home"));
            }
        
        $user_id = Auth::guard("user")->user()->id;
        $usertype = Auth::guard("user")->user()->usertype;
        /*
        if ($usertype != "seller" && $usertype != "buyer") { return redirect()->route("home"); }*/
        $user = User::find($user_id);
        
      
        $countries = Country::all();
        $categories = Category::where("parent_id", null)
            ->orderBy("name")
            ->get();
            $Req = ProductRequest::find($req_id); 
            $cat_selected = Category::where("id", $Req->category_id)->first();
           
            if($this->isMobile()) { 
           return view( "frontEnd.seller.EditProductRequest_mobile",compact("user","countries","categories","Req","cat_selected"));
        } else {
            return view( "frontEnd.seller.EditProductRequest",compact("user","countries","categories","Req","cat_selected"));
        }
        } 
       
        else {
            return redirect()->route("home");
        }
    }
    public function insertproductrequest(Request $request)
    {
    
        $data = $request->validate([
        "product_description" => "required|string",
        "category_id" => "required",
        ]);
       $parent_id = $user_id = Auth::guard("user")->user()->id;
       $seller_type=Auth::guard("user")->user()->seller_type;
        if (Auth::guard("user")->user()->seller_type != "Master") {
            $parent_id = Auth::guard("user")->user()->parent_id;
        }
            $country_id=$category_id = "";
            if (!empty($request->input("category_id"))) {
                $category_id = $request->input("category_id");
            }

            if (!empty($request->input("subcategory_id"))) {
                $category_id = $request->input("subcategory_id");
            }

            if (!empty($request->input("subsubcategory_id"))) {
                $category_id = $request->input("subsubcategory_id");
            }

            
            
            $imageName ="";

            if ($request->hasFile("product_gallery")) {
                $file = $request->file("product_gallery");
                     $imageName = time() . "_" . $file->getClientOriginalName();
                     $destinationPath = public_path() . "/uploads/productRequests";
                     $file->move($destinationPath, $imageName);
                
            }
            if(!empty($request->input('country_id'))) {
            $country_id  = $request->input('country_id');
            $country_id=implode(",",$country_id);
            } 
            $inp = [];  
            $inp['category_id'] = $category_id??'';
            $inp['user_id'] = $user_id;
            $inp['seller_type'] = $seller_type;
            $inp['parent_id'] = $parent_id;
            $inp['country_id'] = $country_id;
            $inp['product_description'] = $request->input('product_description')??'';
            $inp['quantity'] = $request->input('quantity')??'';
            $inp['language'] = $request->input('language')??'';
            $date1  =$date  = Carbon::now();
			$end_date = $date->addDays(10); 
            $inp['req_date'] = Carbon::now();
            $inp['extend_by_date'] = Carbon::now();
			$inp['req_end_date'] = $end_date;
            $inp['prod_img'] = $imageName;
            
            ProductRequest::create($inp);
             $msg='Product Request Created!';
            \Session::flash('product_message',$msg);
            \Session::reflash(); 
           return json_encode($msg);

           
        }
    public function updateProductRequest(Request $request)
    {
        $data = $request->validate([
        "product_description" => "required|string",
        "category_id" => "required",
        ]);
       $user_id = Auth::guard("user")->user()->id;
       $req_id = $request->get("req_id");
        $product = ProductRequest::find($req_id);
        if (Auth::guard("user")->user()->seller_type != "Master") {
            $user_id = Auth::guard("user")->user()->parent_id;
        }
            $country_id = $category_id = "";
            if (!empty($request->input("category_id"))) {
                $category_id = $request->input("category_id");
            }

            if (!empty($request->input("subcategory_id"))) {
                $category_id = $request->input("subcategory_id");
            }

            if (!empty($request->input("subsubcategory_id"))) {
                $category_id = $request->input("subsubcategory_id");
            }

            $imageName =$product->prod_img??"";
            if ($request->hasFile("product_gallery")) {
                $file = $request->file("product_gallery");
                     $imageName = time() . "_" . $file->getClientOriginalName();
                     $destinationPath = public_path() . "/uploads/productRequests";
                     $file->move($destinationPath, $imageName);
                
            }
            if(!empty($request->input('country_id'))) {
            $country_id  = $request->input('country_id');
            $country_id=implode(",",$country_id);
            } 
            $date = new Carbon($product->req_end_date);
            $inp = [];  
            $inp['category_id'] = $category_id??'';
            $inp['user_id'] = $user_id;
            $inp['country_id'] = $country_id;
            $inp['product_description'] = $request->input('product_description')??'';
            $inp['quantity'] = $request->input('quantity')??'';
            $inp['language'] = $request->input('language')??'';
         //   $inp['extend_by_date'] =  Carbon::now();
            $inp['prod_img'] = $imageName;
            
            
            
        
		$product->update($inp);
            
             $msg='Product Request Updated!';
            \Session::flash('product_message',$msg);
            \Session::reflash(); 
           return json_encode($msg);

           
        }    
   public function ProductRequests($search_key = null)
    {
        $validity = $this->PublicMiddlewareController->checkUserContactValidity();
        $active_sellers = $this->PublicMiddlewareController->getexpiredsellerslist();
        $type_searchby_text="";
        if (Auth::guard("user")->check()) {
            if (!$this->PublicMiddlewareController->checkUserlogin()) {
                Auth::guard("user")->logout();
                return redirect(route("home"))->with(
                    "message",
                    "Access Denied"
                );
            }
        }

        $user_id = $user =   "";
        $category_id=$_REQUEST['cat_id']??null; ///url passed main categories
        if (Auth::guard("user")->check()) {
            $user_id = Auth::guard("user")->user()->id;
            $user = User::find($user_id);
            $usertype = Auth::guard("user")->user()->usertype;
            $seller_type=Auth::guard("user")->user()->seller_type;
			 if (Auth::guard("user")->user()->seller_type != "Master") {
				$user_id = Auth::guard("user")->user()->parent_id;
			 }
        }
       /* if ($usertype != "seller" && $usertype != "buyer") { return redirect()->route("home"); }*/
        $productRequests = ProductRequest::select("product_requests.*")
                    //->where("product_requests.parent_id", "<>", $user_id)
                   ->whereDate('req_end_date', '>', Carbon::now())
                    ->latest("product_requests.created_at")
                    ->get();   
        $values = $prd_req_cat_ids =  $parent_cat_id=[];
        if(!empty($productRequests)){
        foreach ($productRequests as $sproduct) {
            $values[] = trim($sproduct->category_id);
                $parent=Category::find($sproduct->category_id);
                if(!empty( $parent)) 
                 {  
                    $parent_id=$parent->id;
                    while(!empty($parent)) 
                    {   
                        $parent = $parent->parent;
                        if(!empty( $parent)) 
                            $parent_id=$parent->id;
                    }
                    $parent_cat_id[]= $parent_id; 
                }
           
        }
        }
        $prd_req_cat_ids1 = array_unique($parent_cat_id);           
        $prd_req_cat_ids = Category::whereIn("id", $prd_req_cat_ids1)->orderBy('name','asc')->get();    //all parent cats for top main categories     
                    
		if(is_null($category_id)){ //url parameter cat_id not exists
             $category_id=[];
              foreach ($productRequests as $sproduct) 
                {
                    foreach (explode(",", $sproduct->category_id) as $value) 
                    {
                        $category_id[] = trim($value);
                    }
                }
               $categorylist = Category::whereIn("id", $category_id)
                               ->whereNotNull('parent_id')
                               ->get();
               $category_id_all = [];
               foreach ($categorylist as $value) {
               if (!in_array($value->id, $category_id_all)) 
                    array_push($category_id_all, $value->id);
               }

            $left_menu_list = [];
            for ( $i = 0; $i < count($category_id_all) && count($categorylist) > 0;  $i++ ) { 
                $categorylist = Category::where( "id",  $category_id_all[$i] )->get();
                foreach ($categorylist as $value2) {
                    array_push($left_menu_list, [
                        "id" => $value2->id,
                        "name" => $value2->name,
                    ]);
                }
            }
}
        else{  
      $categorylist = Category::where("parent_id", $category_id)->get();
            $category_id_all =  [];
            foreach ($categorylist as $value) {
                if (!in_array($value->id, $category_id_all)) 
                    array_push($category_id_all, $value->id);
               

                    for ($i = 0; $i < count($category_id_all); $i++) {
                        $category1 = Category::where("parent_id", $category_id_all[$i])->get();
                        foreach ($category1 as $value2) {
                            if (!in_array($value2->id, $category_id_all)) 
                                array_push($category_id_all, $value2->id);               
                        }
                    }
                 
            }

            $left_menu_list = [];
            for ( $i = 0; $i < count($category_id_all) && count($categorylist) > 0;  $i++ ) {
                $categorylist = Category::where( "id",  $category_id_all[$i] )->get();
                foreach ($categorylist as $value2) {
                    array_push($left_menu_list, [
                        "id" => $value2->id,
                        "name" => $value2->name,
                    ]);
                }
            }
  }          
            
        
            if($this->isMobile()) 
                return view("frontEnd.seller.product-request_mobile",compact('prd_req_cat_ids',"user","validity","search_key","left_menu_list", "type_searchby_text"));
            else 
                return view("frontEnd.seller.product-request",compact('prd_req_cat_ids',"user","validity","search_key","left_menu_list", "type_searchby_text"));
}


    public function ProuctRquestSearch(Request $request)
    {
        $active_sellers = $this->PublicMiddlewareController->getexpiredsellerslist();
        $validity = $this->PublicMiddlewareController->checkUserContactValidity();
        $start_from = $request->input("start_from");
        $main_category_id=$request->input("category_id_l")??null;  //top main category selected
        $per_page = $request->input("per_page");
        $category_id = [];
         $user_id = Auth::guard("user")->user()->id;
			 $seller_type=Auth::guard("user")->user()->seller_type;
			 if (Auth::guard("user")->user()->seller_type != "Master") {
				$user_id = Auth::guard("user")->user()->parent_id;
			 }
        
        
        $search_key = $request->input("search"); 
        $type_searchby_text = $request->input("type_searchby_text");
        $topcategorysearch = $request->input("topcategorysearch");
        //dd($request->input("topcategorysearch"));
        if ($request->input("category_id") != "") //left side checkbox categories
		{
            $category_id = explode(",", $request->input("category_id"));
		    $categorylist = Category::whereIn("parent_id", $category_id)
                            ->pluck("id")->all(); // all subcategories

                    foreach ($categorylist as $value) {
                        if (!in_array($value, $category_id)) {
                            //subctegory id not in $category_id array
                            array_push($category_id, $value);
                        } //all category ids as array

                        for ($i = 0; $i < count($category_id); $i++) {
                            $category1 = Category::where("parent_id",$category_id[$i])->get();
                            foreach ($category1 as $value2) {
                                if (!in_array($value2->id, $category_id)) {
                                    array_push($category_id, $value2->id);
                                }
                            }
                        }
                }
           
		
	}
        elseif (!is_null($main_category_id)) { 
                     $categorylist = Category::where("parent_id", $main_category_id)
                            ->pluck("id")->all(); // all subcategories
                            
                     array_push($category_id, $main_category_id); //parent category to array}
                     foreach ($categorylist as $value) {
                        if (!in_array($value, $category_id)) {
                            //subctegory id not in $category_id array
                            array_push($category_id, trim($value));
                        } //all category ids as array

                        for ($i = 0; $i < count($category_id); $i++) {
                            $category1 = Category::where("parent_id",$category_id[$i])->get();
                            foreach ($category1 as $value2) {
                                if (!in_array($value2->id, $category_id)) {
                                    array_push($category_id, trim($value2->id)); 
                                }
                            }
                        }
                }
            
            
           
        } 
            
            $products = ProductRequest::
                leftJoin("users","users.id", "=", "product_requests.parent_id")
                ->leftJoin("buyer_companies","buyer_companies.user_id", "=", "product_requests.parent_id")
                ->select(["product_requests.product_description AS prd_name", "product_requests.*","users.country_id","buyer_companies.company_name"])
                //->where("product_requests.parent_id", "<>", $user_id)
                ->whereDate('req_end_date', '>', Carbon::now())
                ->where(function ($query) use (  $category_id ) {
                    foreach ($category_id as $id) {
                        $query->orWhere("category_id", $id);
                        
                    }
                });

            if ($search_key != "") {
                $products = $products->where("product_requests.product_description", "LIKE",  "%".$search_key . "%");
				
            }
             

            

            if ($request->input("country_id") != "") {
                $country_id = explode(",", $request->input("country_id"));
                $products = $products->where(function ($query) use (
                    $country_id
                ) {
                    foreach ($country_id as $c_id) {
                       // $query->orWhereRaw( 'find_in_set("' . $c_id . '",product_requests.country_id)');
                        $query->orWhereRaw( 'find_in_set("' . $c_id . '",users.country_id)');
                      
                    }
                });
              
            }

            

            $count = $products->count();
            $products = $products
                            ->orderBy('product_requests.id','Desc')
                            ->skip($start_from)
                            ->take($per_page)
                            ->get();
                           

            $data_return = [];
            foreach ($products as $data) {
                $prd_img = URL::asset("/images/no-image.jpg");
                if(!empty($data->prod_img))
                $prd_img = URL::asset("/uploads/productRequests/".$data->prod_img);
                 
              

                $seller_id = $data->user_id;
                $seller = User::find($seller_id);
                
                

                $days_remaining = $diff = now()->diffInDays($data->req_end_date);
                

                // $country_ids =[]; $countries='';
		        // if (!empty($data->country_id)) {
                //     $country_ids = explode(",", $data->country_id);
                //     $countries = Country::whereIn("id", $country_ids)->pluck("name")->all();
                // }
                $seller_country_name = DB::table("countries")
                    ->where("id", $seller->country_id)
                    ->pluck("name")
                    ->first();
                $data_return[] = [
                    "product_name" => ucfirst($data->prd_name),
                    "user_id" => $data->user_id,
                    "company_name" => $data->company_name,
                    "quantity" => $data->quantity,
                    "language" => $data->language,
                    "parent_id" => $data->parent_id,
                    "seller_type" => $data->seller_type,
                    "product_thumbnail" => $prd_img,
                    "days_remaining" => $days_remaining,
                    "country" => $seller_country_name,
                    "stock_count" => $data->stock_count,
                    "product_id" => $data->id,
                    "req_date" => $data->req_date,
                ];
            }
            $return_array = [
                "count" => $count,
                "product_data" => $data_return,
            ];
        return json_encode($return_array);
    }
    
    
     public function MyProductRequests($search_key = null)
    {
        $validity = $this->PublicMiddlewareController->checkUserContactValidity();
        $active_sellers = $this->PublicMiddlewareController->getexpiredsellerslist();
        $type_searchby_text="";
        if (Auth::guard("user")->check()) {
            if (!$this->PublicMiddlewareController->checkUserlogin()) {
                Auth::guard("user")->logout();
                return redirect(route("home"))->with(
                    "message",
                    "Access Denied"
                );
            }
        }

        $user_id = $user =   "";
        $category_id=$_REQUEST['cat_id']??null;
        if (Auth::guard("user")->check()) {
            $user_id = Auth::guard("user")->user()->id;
			 $seller_type=Auth::guard("user")->user()->seller_type;
			 if (Auth::guard("user")->user()->seller_type != "Master") {
				$user_id = Auth::guard("user")->user()->parent_id;
			 }
            $user = User::find($user_id);
            $usertype = Auth::guard("user")->user()->usertype;
        }
      /*  if ($usertype != "seller" && $usertype != "buyer") { return redirect()->route("home"); }*/
        $productRequests = ProductRequest::select("product_requests.*")
                    ->where("product_requests.parent_id", $user_id)
                    ->whereDate('req_end_date', '>', Carbon::now())
                    ->latest("product_requests.created_at")
                    ->get();             
        $values = $prd_req_cat_ids =  $parent_cat_id=[];
        if(!empty($productRequests)){
        foreach ($productRequests as $sproduct) {
            $values[] = trim($sproduct->category_id);
                $parent=Category::find($sproduct->category_id);
                if(!empty( $parent)) 
                 {  
                    $parent_id=$parent->id;
                    while(!empty($parent)) 
                    {   
                        $parent = $parent->parent;
                        if(!empty( $parent)) 
                            $parent_id=$parent->id;
                    }
                    $parent_cat_id[]= $parent_id; 
                }
           
        }
        }
        $prd_req_cat_ids1 = array_unique($parent_cat_id);           
        $prd_req_cat_ids = Category::whereIn("id", $prd_req_cat_ids1)->orderBy('name','asc')->get();    //all parent cats for top main       
                    
		if(is_null($category_id)){ //url parameter cat not exists
             $category_id=[];
              foreach ($productRequests as $sproduct) 
                {
                    foreach (explode(",", $sproduct->category_id) as $value) 
                    {
                        $category_id[] = trim($value);
                    }
                }
               $categorylist = Category::whereIn("id", $category_id)
                               ->whereNotNull('parent_id')
                               ->get();
               $category_id_all = [];
               foreach ($categorylist as $value) {
               if (!in_array($value->id, $category_id_all)) 
                    array_push($category_id_all, $value->id);
               }

            $left_menu_list = [];
            for ( $i = 0; $i < count($category_id_all) && count($categorylist) > 0;  $i++ ) { 
                $categorylist = Category::where( "id",  $category_id_all[$i] )->get();
                foreach ($categorylist as $value2) {
                    array_push($left_menu_list, [
                        "id" => $value2->id,
                        "name" => $value2->name,
                    ]);
                }
            }
}
        else{
      $categorylist = Category::where("parent_id", $category_id)->get();
            $category_id_all =  [];
            foreach ($categorylist as $value) {
                if (!in_array($value->id, $category_id_all)) 
                    array_push($category_id_all, $value->id);
               

                    for ($i = 0; $i < count($category_id_all); $i++) {
                        $category1 = Category::where("parent_id", $category_id_all[$i])->get();
                        foreach ($category1 as $value2) {
                            if (!in_array($value2->id, $category_id_all)) 
                                array_push($category_id_all, $value2->id);               
                        }
                    }
                 
            }

            $left_menu_list = [];
            for ( $i = 0; $i < count($category_id_all) && count($categorylist) > 0;  $i++ ) {
                $categorylist = Category::where( "id",  $category_id_all[$i] )->get();
                foreach ($categorylist as $value2) {
                    array_push($left_menu_list, [
                        "id" => $value2->id,
                        "name" => $value2->name,
                    ]);
                }
            }
  }          
            
        
            if($this->isMobile()) 
                return view("frontEnd.seller.my-product-request_mobile",compact('prd_req_cat_ids',"user","validity","search_key","left_menu_list", "type_searchby_text"));
            else 
                return view("frontEnd.seller.my-product-request",compact('prd_req_cat_ids',"user","validity","search_key","left_menu_list", "type_searchby_text"));
}


  public function MyProuctRquestSearch(Request $request)
    {
        $active_sellers = $this->PublicMiddlewareController->getexpiredsellerslist();
        $validity = $this->PublicMiddlewareController->checkUserContactValidity();
        $start_from = $request->input("start_from");
        $main_category_id=$request->input("category_id_l")??null;
        $per_page = $request->input("per_page");
        $category_id = [];
        $search_key = $request->input("search"); 
        $type_searchby_text = $request->input("type_searchby_text");
        $user_id = Auth::guard("user")->user()->id;
			 $seller_type=Auth::guard("user")->user()->seller_type;
			 if (Auth::guard("user")->user()->seller_type != "Master") {
				$user_id = Auth::guard("user")->user()->parent_id;
			 }
        if ($request->input("category_id") != "") 
		{
            $category_id = explode(",", $request->input("category_id"));
		    $categorylist = Category::whereIn("parent_id", $category_id)
                            ->pluck("id")->all(); // all subcategories

                    foreach ($categorylist as $value) {
                        if (!in_array($value, $category_id)) {
                            //subctegory id not in $category_id array
                            array_push($category_id, $value);
                        } //all category ids as array

                        for ($i = 0; $i < count($category_id); $i++) {
                            $category1 = Category::where("parent_id",$category_id[$i])->get();
                            foreach ($category1 as $value2) {
                                if (!in_array($value2->id, $category_id)) {
                                    array_push($category_id, $value2->id);
                                }
                            }
                        }
                }
           
		
	}
        elseif (!is_null($main_category_id)) { 
                     $categorylist = Category::where("parent_id", $main_category_id)
                            ->pluck("id")->all(); // all subcategories
                            
                     array_push($category_id, $main_category_id); //parent category to category_id array}
                     foreach ($categorylist as $value) {
                        if (!in_array($value, $category_id)) {
                            //subctegory id not in $category_id array
                            array_push($category_id, trim($value));
                        } //all category ids as array

                        for ($i = 0; $i < count($category_id); $i++) {
                            $category1 = Category::where("parent_id",$category_id[$i])->get();
                            foreach ($category1 as $value2) {
                                if (!in_array($value2->id, $category_id)) {
                                    array_push($category_id, trim($value2->id)); 
                                }
                            }
                        }
                }
            
           
           
        } 
        $today = Carbon::now();
            $products = ProductRequest::leftJoin("users","users.id", "=", "product_requests.parent_id")
                ->leftJoin("buyer_companies","buyer_companies.user_id", "=", "product_requests.parent_id")
                ->select(["product_requests.product_description AS prd_name","product_requests.country_id AS country_id", "product_requests.*","users.country_id","buyer_companies.company_name"])
                ->where("product_requests.parent_id", $user_id)
                ->whereDate('req_end_date', '>', $today)
                ->where(function ($query) use (  $category_id ) {
                    foreach ($category_id as $id) {
                        $query->orWhere("product_requests.category_id", $id);
                     }
                });

            if ($search_key != "") {
                $products = $products->where("product_requests.product_description", "LIKE",  "%".$search_key . "%");
			 }
             if ($request->input("country_id") != "") {
                $country_id = explode(",", $request->input("country_id"));
                $products = $products->where(function ($query) use (
                    $country_id
                ) {
                    foreach ($country_id as $c_id) {
                       // $query->orWhereRaw( 'find_in_set("' . $c_id . '",product_requests.country_id)');
                        $query->orWhereRaw( 'find_in_set("' . $c_id . '",users.country_id)');
                    }
                });
                // $products = $products->whereIn('users.country_id',$country_id);
            }

            

            $count = $products->count();
            $products = $products
                            ->orderBy('product_requests.id','Desc')
                            ->skip($start_from)
                            ->take($per_page)
                            ->get();
                           

            $data_return = [];
            foreach ($products as $data) {
                $prd_img = URL::asset("/images/no-image.jpg");
                if(!empty($data->prod_img))
                $prd_img = URL::asset("/uploads/productRequests/".$data->prod_img);
                 
              

                $seller_id = $data->user_id;
                $seller = User::find($seller_id);
                
                

                $days_remaining = $diff = now()->diffInDays($data->req_end_date);
                 
                $country_ids =[]; $countries='';
		        /*if (!empty($data->country_id)) {
                    $country_ids = explode(",", $data->country_id);
                    $countries = Country::whereIn("id", $country_ids)->pluck("name")->all();
                }*/
                $seller_country_name = DB::table("countries")
                    ->where("id", $seller->country_id)
                    ->pluck("name")
                    ->first();
                $data_return[] = [
                    "product_name" => ucfirst($data->prd_name),
                    "user_id" => $data->user_id,
                    "parent_id" => $data->parent_id,
                    "seller_type" => $data->seller_type,
                    "pr_id" => $data->id,
                    "product_thumbnail" => $prd_img,
                    "days_remaining" => $days_remaining,
                    "country" => $seller_country_name,
                    "company_name" => $data->company_name,
                    "quantity" => $data->quantity,
                    "language" => $data->language,
                    "stock_count" => $data->stock_count,
                    "product_id" => $data->id,
                    "req_date" => $data->req_date,
                ];
            }
            $return_array = [
                "count" => $count,
                "product_data" => $data_return,
            ];
        return json_encode($return_array);
    }
    
    public function deleteProductRequest($req_id)
    {
        $prodreq = ProductRequest::find($req_id);
        if (empty($prodreq)) {
            return redirect()
                ->route("MyProduct.Requests")
                ->with("message", "Product Request not Exists");
        }
        $prodreq->delete();
        return redirect()
            ->route("MyProduct.Requests")
            ->with("req_message", "Product Request Deleted!");
    }
    public function ExtendRequest($req_id)
    {
        $prodreq = ProductRequest::find($req_id);
        if (empty($prodreq)) {
            return redirect()
                ->route("MyProduct.Requests")
                ->with("message", "Product Request not Exists");
        }
        $extend_by_date = Carbon::parse($prodreq->extend_by_date);
        if ($extend_by_date->isToday()){
            return redirect()->route("MyProduct.Requests") ->with("req_failed", "Unable to extend now!");
        } else {
        $date =  Carbon::parse($prodreq->req_end_date) ;
        $prodreq->req_end_date = $date->addDays(10); 
        $prodreq->extend_by_date = Carbon::now();
        $prodreq->save();
        return redirect()->route("MyProduct.Requests")->with("req_message", "Product Request Extended!");
        }
    }
    function ajaxSubcat(Request $request)
    {
        $cat_id = $request->cat_id;
        $subcategories = DB::table("categories")
            ->where("parent_id", "=", $cat_id)->orderBy('name','ASC')
            ->get();
        return Response::json($subcategories);
    }

    public function AddUsingExistingProduct()
    {
        if (Auth::guard("user")->check()) {
            if (!$this->PublicMiddlewareController->checkUserlogin()) {
                Auth::guard("user")->logout();
                return redirect(route("home"))->with(
                    "message",
                    "Access Denied"
                );
            }
            if (
                !$this->PublicMiddlewareController->checkUserContactValidity()
            ) {
                Auth::guard("user")->logout();
                return redirect(route("home"));
            }
        
        $user_id = Auth::guard("user")->user()->id;
        $usertype = Auth::guard("user")->user()->usertype;
        $user = User::find($user_id);
        if ($usertype == "seller") {
            //if not seller redirect to home
            
            if($this->isMobile()) { 
             return view( "frontEnd.seller.AddExistingSellerProduct_mobile", compact("user") );
            } else {
             return view( "frontEnd.seller.AddExistingSellerProduct", compact("user") );
            }
        } }
        else {
            return redirect()->route("home");
        }
    }

    public function autoCompleteProduct(request $request)
    {
        if ($request->ajax()) {
            $products = Product::where(
                "name","Like",$request->name . "%")->get();
            $output = "";
            if (count($products) > 0) {
                $output =
                    '<ulclass="list-group" style="dispaly:block;position:relative;z-index:1">';
                foreach ($products as $product) {
                    $output .=
                        '<li class="list-group-item" id="' .
                        $product->id .
                        '">' .
                        $product->name .
                        "</li>";
                }
                $output .= "</ul>";
            } else {
                $output .= '<li class="list-group-item">No Data Found</li>';
            }
            return $output;
        }
    }

    public function AddProductSellerExisting(Request $request)
    {
        $request->validate([
            "product_id" => "required",
        ]);
        $productId = $request->input("product_id");
        $product = Product::find($productId);
        $varients = SellerProduct::all();
        $countries = Country::all();
        $Productbrand = Productbrand::orderBy("name")->get();
        // $product_images= ProductImage::find($productId);
        $product_images = ProductImage::where(
            "product_id",
            "=",
            $productId
        )->get();
        $product_id_list = ProductImage::where("product_id", "=", $productId)
            ->pluck("id")
            ->all();
        $categories = Category::where("parent_id", null)
            ->orderBy("name")
            ->get();
        $currencies = Currency::select("id", "shortcode", "symbol")->get();
        $cat_selected = Category::where("id", $product->category_id)->first();
        
        if($this->isMobile()) { 
           return view("frontEnd.seller.AddSellerProduct3_mobile",compact("product","currencies","cat_selected","Productbrand","categories","varients","countries","product_images","product_id_list"));
        } else {
            return view("frontEnd.seller.AddSellerProduct3",compact("product","currencies","cat_selected","Productbrand","categories","varients","countries","product_images","product_id_list"));
        }
    }

    public function InsertSproduct(Request $request)
    {
        
         $data = $request->validate([
        "name" => "required|string",
        "category_id" => "required",
        "product_price" => [
            "nullable", 
            function ($attribute, $value, $fail) {
                if (empty($value)) {
                    return; 
                }
                if (!is_numeric($value)) {
                    $fail('The price must be a number.');
                }
            },
            "required_unless:price_on_request,!=,Price on request", 
        ],
        
        ], [
            'name.required' => 'The product description field is required.',
            'category_id.required' => 'The category field is required.',
            'product_price.required_unless' => 'The price field is required.',
        ]);
        $user_id = Auth::guard("user")->user()->id;
        $usertype = Auth::guard("user")->user()->usertype;
        if (Auth::guard("user")->user()->seller_type != "Master") {
            $user_id = Auth::guard("user")->user()->parent_id;
        }
        if ($usertype == "seller") {
            //if not seller redirect to home
            $available_countries = $category_id = $variants = "";

            $category_id = "";
            if (!empty($request->input("category_id"))) {
                $category_id = $request->input("category_id");
            }

            if (!empty($request->input("subcategory_id"))) {
                $category_id = $request->input("subcategory_id");
            }

            if (!empty($request->input("subsubcategory_id"))) {
                $category_id = $request->input("subsubcategory_id");
            }

            if (!empty($request->input("variants"))) {
                $variants = $request->input("variants");
                $variants = implode(",", $variants);
            }

            $input = $request->all();
            if(!array_key_exists("unlimited_stock",$input))
            $input['unlimited_stock']='No';
           /* if(!array_key_exists("stock_count",$input) || $input['stock_count']==null)
            $input['stock_count']=0;*/
        $product_price=null;
        if(!empty($request->input('product_price'))) {
        $product_price  = $request->input('product_price');
		$product_price = (double) str_replace(',', '', $product_price)   ;
		$product_price = round($product_price,3);
        } 
		$input['product_price']=$product_price;            
            if (!empty($request->input("available_countries"))) {
                $available_countries = $request->input("available_countries");
                $available_countries = implode(",", $available_countries);
                $input["country_ids"] = $available_countries;
                $input["available_countries"] = "";
            }

            $input["available_countries"] = $available_countries;
            $input["category_id"] = $category_id;
            $input["variants"] = $variants;
            $input["user_id"] = $user_id;
            $input["status"] = "pending";
            $cats_path='';
        if($category_id!="")
        {
            $category=Category::find($category_id);
            
            $i=0; 
            if ($category->getParentsNames()) {
                foreach ($category->getParentsNames()->reverse() as $item)
                {
                        if($i==0)
                             $cats_path = $item->name;
                        else if($i>0)
                             $cats_path .= '>'.$item->name;
                        ++$i;
                }
                $cats_path.='>'.$category->name;
            }
            else
                $cats_path=$category->name;
        }
        else
             $cats_path= $request->input('categories');
             $input['categories']= $cats_path;
            $id = SellerProductTemp::create($input)->id;

            $prev_imgs = $request->input("prev_imgs");

            $prev_imgs = explode(",", $prev_imgs);
            foreach ($prev_imgs as $prev_img_id) {
                $preve_img = ProductImage::find($prev_img_id);
                if (!empty($preve_img)) {
                    $img["thumbnail"] = $preve_img->thumbnail;
                    $img["image_path"] = $preve_img->image_path;
                    $img["product_id"] = $id;
                    SellerProductImageTemp::create($img);
                }
            }
            $sellerProductImageTemp =SellerProductImageTemp::where('product_id',$id)
                    ->where('thumbnail','yes')->first();
            if(empty($sellerProductImageTemp)){
                 $sellerProductImageTemp1 =SellerProductImageTemp::where('product_id',$id)->first();
                 if($sellerProductImageTemp1) 
                    $sellerProductImageTemp1->update(['thumbnail'=>'yes']);
            }
            // if ($request->hasFile("product_image")) {
            //     $file = $request->file("product_image"); 
            //     $fileName = time() . "_" . $file->getClientOriginalName();
            //     $destinationPath = public_path() . "/uploads/productImages";
            //     $file->move($destinationPath, $fileName);
            //     $img["image_path"] = $fileName;
            //     $img["thumbnail"] = "yes";
            //     $img["product_id"] = $id;
            //     SellerProductImageTemp::create($img);
            // }

            if ($request->hasFile("product_gallery")) {
                $files = $request->file("product_gallery");
                $i=0;
                $sellerProductImageTemp =SellerProductImageTemp::where('product_id',$id)
                    ->where('thumbnail','yes')->first();
                foreach ($files as $file) {
                    $imageName = time() . "_" . $file->getClientOriginalName();
                    $img["product_id"] = $id;
                    if($i==0 && empty($sellerProductImageTemp))
                    {    
                        $img["thumbnail"] = "yes";
                        $i=1;
                    }
                    else
                        $img["thumbnail"] = "no";
                    $img["image_path"] = $imageName;
                    $destinationPath = public_path() . "/uploads/productImages";
                    $file->move($destinationPath, $imageName);
                    SellerProductImageTemp::create($img);
                }
            }
            $msg='Product added successfully!'; 
            \Session::flash('product_message',$msg);
             \Session::reflash(); 
            return json_encode($msg);

        } else {
            return redirect()->route("home");
        }
    }

    public function addnewSproduct(Request $request)
    {
    
        $data = $request->validate([
        "name" => "required|string",
        "category_id" => "required",
        "product_price" => [
            "nullable", 
            function ($attribute, $value, $fail) {
                if (empty($value)) {
                    return; 
                }
                if (!is_numeric($value)) {
                    $fail('The price must be a number.');
                }
            },
            "required_unless:price_on_request,!=,Price on request", 
        ],
        
        ], [
            'name.required' => 'The product description field is required.',
            'category_id.required' => 'The category field is required.',
            'product_price.required_unless' => 'The price field is required.',
        ]);

     
       /* if ($request->input("unlimited_stock") != "Unlimited") {
            $data = $request->validate([
                "stock_count" => "required|numeric",
            ]);
        }*/
        $user_id = Auth::guard("user")->user()->id;
        $usertype = Auth::guard("user")->user()->usertype;

        if (Auth::guard("user")->user()->seller_type != "Master") {
            $user_id = Auth::guard("user")->user()->parent_id;
        }

        if ($usertype == "seller") {
            //if not seller redirect to home
            $available_countries = $category_id = $variants = $currency_id = "";
            if (!empty($request->input("available_countries"))) {
                $available_countries = $request->input("available_countries");
                $available_countries = implode(",", $available_countries);
            }
            
            

            $category_id = "";
            if (!empty($request->input("category_id"))) {
                $category_id = $request->input("category_id");
            }

            if (!empty($request->input("subcategory_id"))) {
                $category_id = $request->input("subcategory_id");
            }

            if (!empty($request->input("subsubcategory_id"))) {
                $category_id = $request->input("subsubcategory_id");
            }

            if (!empty($request->input("variants"))) {
                $variants = $request->input("variants");
                $variants = implode(",", $variants);
            }
            $input = $request->all();
            if(!array_key_exists("unlimited_stock",$input))
            $input['unlimited_stock']='No';
           /* if(!array_key_exists("stock_count",$input) || $input['stock_count']==null)
            $input['stock_count']=0;*/
        $product_price=null;
        if(!empty($request->input('product_price'))) {
        $product_price  = $request->input('product_price');
		$product_price = (double) str_replace(',', '', $product_price)   ;
		$product_price = round($product_price,3);
		
        } 
		$input['product_price']=$product_price;            
            if(!empty($request->input('currency_id'))) {
            $input['currency_id'] =  $request->input('currency_id');
            }
            $input["available_countries"] = $available_countries;
            //$input["currency"] = $request->input("currency_id");
            
            //$input["currency_id"] = $currency_id;
            $input["country_ids"] = $available_countries;
            $input["category_id"] = $category_id;
            $input["variants"] = $variants;
            $input["user_id"] = $user_id;
            $input["status"] = "pending";
     
        $cats_path='';
        if($category_id!="")
        {
            $category=Category::find($category_id);
            
            $i=0; 
            if ($category->getParentsNames()) {
                foreach ($category->getParentsNames()->reverse() as $item)
                {
                        if($i==0)
                             $cats_path = $item->name;
                        else if($i>0)
                             $cats_path .= '>'.$item->name;
                        ++$i;
                }
                $cats_path.='>'.$category->name;
            }
            else
                $cats_path=$category->name;
        }
        else
             $cats_path= $request->input('categories');
            $input['categories']= $cats_path;
            
            $id = SellerProductTemp::create($input)->id;
			$prev_imgs = $request->input("prev_imgs");
			$prev_imgs = explode(",", $prev_imgs);
            foreach ($prev_imgs as $prev_img_id) {
                $preve_img = ProductImage::find($prev_img_id);
                if (!empty($preve_img)) {
                    $img["thumbnail"] = $preve_img->thumbnail;
                    $img["image_path"] = $preve_img->image_path;
                    $img["product_id"] = $id;
                    SellerProductImageTemp::create($img);
                }
            }
            
            $sellerProductImageTemp =SellerProductImageTemp::where('product_id',$id)
                    ->where('thumbnail','yes')->first();
            if(empty($sellerProductImageTemp)){
                 $sellerProductImageTemp1 =SellerProductImageTemp::where('product_id',$id)->first();
                 if($sellerProductImageTemp1) 
                    $sellerProductImageTemp1->update(['thumbnail'=>'yes']);
            }
            // if ($request->hasFile("product_image")) {
            //     $file = $request->file("product_image"); 
            //     $fileName = time() . "_" . $file->getClientOriginalName();
            //     $destinationPath = public_path() . "/uploads/productImages";
            //     $file->move($destinationPath, $fileName);
            //     $img["image_path"] = $fileName;
            //     $img["thumbnail"] = "yes";
            //     $img["product_id"] = $id;
            //     SellerProductImageTemp::create($img);
            // }

            if ($request->hasFile("product_gallery")) {
                $files = $request->file("product_gallery");
                $i=0;
                $sellerProductImageTemp =SellerProductImageTemp::where('product_id',$id)
                    ->where('thumbnail','yes')->first();
                foreach ($files as $file) {
                    $imageName = time() . "_" . $file->getClientOriginalName();
                    $img["product_id"] = $id;
                    if($i==0 && empty($sellerProductImageTemp))
                    {    
                        $img["thumbnail"] = "yes";
                        $i=1;
                    }
                    else
                        $img["thumbnail"] = "no";
                    $img["image_path"] = $imageName;
                    $destinationPath = public_path() . "/uploads/productImages";
                    $file->move($destinationPath, $imageName);
                    SellerProductImageTemp::create($img);
                }
            }
             $msg='Product added successfully!';
            \Session::flash('product_message',$msg);
            \Session::reflash(); 
           return json_encode($msg);

        } else {
            return redirect()->route("home");
        }
    }
    public function deleteSellerProductimage($id)
    {
        
        SellerProductImage::find($id)->delete();
        return back();
    }

    public function import(Request $request)
    {
        $result = Excel::import(
            new SellerProductImport(),
            request()->file("file")
        );

        //  return back()->with('success', 'Products Imported Successfully.');
        return redirect()
            ->back()
            ->withSuccess("Products Imported Successfully.");
    }

    public function getSellerproductlist(Request $request)
    {
        $userId = Auth::guard("user")->user()->id;
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
        $userId = Auth::guard("user")->user()->id;

        if (Auth::guard("user")->user()->seller_type != "Master") 
            $userId = Auth::guard("user")->user()->parent_id;
        
         $subcategorys=[];
        if ($request->get("category_id") != "") {
            $category_id  = $request->get("category_id");   
            $categorylist = Category::where("parent_id", $category_id)->pluck("id")->all(); // all subcategories
            array_push($subcategorys, $category_id); //parent category to array

            foreach ($categorylist as $value) {
                if (!in_array($value, $subcategorys)) {
                    //subctegory id not in $category_id array
                    array_push($subcategorys, $value); 
                } // all category ids as array

                for ($i = 0; $i < count($subcategorys); $i++) {
                    $category1 = Category::where("parent_id",$subcategorys[$i])->get();
                    foreach ($category1 as $value2) {
                        if (!in_array($value2->id, $subcategorys)) {
                            array_push($subcategorys, $value2->id);
                        }
                    }
                }
            }
        }
        $totalRecords = SellerProduct::select("count(*) as allcount")
            ->where("user_id", $userId)
            ->where('status','<>','deleted') 
            ->count();
        /*$totalRecordswithFilter = SellerProduct::select("count(*) as allcount")
            ->where("user_id", $userId)
            ->where('status','<>','deleted') 
            ->when($request->get('stock') !=null, function ($query) use ($request) {
                 if($request->get('stock')=="instock")
                     {  $query->where(function ($query){
                             $query->where("stock_count",">",0)->orWhereNull('stock_count')->orwhere('unlimited_stock','Unlimited');
                            });}
                    else
                    {
                        $query->where(function ($query){
                              $query-> where("stock_count","<=",0)->where('unlimited_stock','<>','Unlimited');
                            });
                    }
                        
            })
            ->where(function ($query) use ($request,$subcategorys) {
                if ($request->get("search_key") != "") {
                    $query->where(
                        "name",
                        "Like",
                        "%" . $request->get("search_key") . "%"
                    );
                }
            if ($request->get("category_id") != null) {
               $query->where(function ($query) use ($subcategorys) {
                        foreach ($subcategorys as $term) {
                            $query->orWhereRaw('find_in_set("' . $term . '",category_id)')
                                  ->where("seller_products.status", "active");
                        }
                    });
 
                    
                }
            })
            ->count();*/
        // Get records, also we have included search filter as well
        $records = SellerProduct::leftJoin( "categories",  "categories.id",  "=",  "seller_products.category_id"  )
            ->select("seller_products.*",'categories.name as cat_name','categories.name as cat_name1')
            ->where("user_id", $userId)
            ->where('status','<>','deleted') 
            ->where("user_id", $userId)
             ->when($request->get('stock') !=null, function ($query) use ($request) {
                 if($request->get('stock')=="instock")
                     {  $query->where(function ($query){
                             $query->where("stock_count",">",0)->orWhereNull('stock_count')->orwhere('unlimited_stock','Unlimited');;
                            });}
                    else
                     {
                         $query->where(function ($query){
                             // $query-> where("stock_count","<=",0)->orWhereNull('stock_count')->where('unlimited_stock','<>','Unlimited');
                              $query-> where("stock_count","<=",0)->where('unlimited_stock','<>','Unlimited');
                            });
                     }
                        
            })
            ->where(function ($query) use ($request,$subcategorys) {
                if ($request->get("search_key") != "") {
                    $query->where(
                        "seller_products.name",
                        "Like",
                        "%" . $request->get("search_key") . "%"
                    );
                }
                if ($request->get("category_id") != "") {
                    $query->where(function ($query) use ($subcategorys) {
                        foreach ($subcategorys as $term) {
                            $query->orWhereRaw('find_in_set("' . $term . '",category_id)')
                                  ->where("seller_products.status", "active");
                        }
                    });
                }
            });
            
            
        $totalRecordswithFilter = $records->count();   
        $records=$records->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();    
        $data_arr = [];

        foreach ($records as $record) {
            $strcat = $strcat_parent = "";
            $cat_selected = Category::where(
                "id",
                $record->category_id
            )->first();

            $i = 0;
            if (!empty($cat_selected)) {
                if ($cat_selected->getParentsNames()) {
                    foreach ($cat_selected->getParentsNames()->reverse()as $item_selected) {
                        if ($i == 0) {
                            $strcat_parent .= $item_selected->name;
                        } elseif ($i > 0) {
                            $strcat_parent .= ">" . $item_selected->name;
                        }
                        ++$i;
                    }
                    if ($i > 0)
                        $strcat = $cat_selected->name;
                }
                else
                    $strcat_parent=$cat_selected->name;
            }

           

           
             $strimg ='<div class="table-prof"><img style=" width:100px !important;" class="pr_img" src="'.asset('/uploads/defaultImages/no_image.jpg').'"></div>';
            foreach ($record->SellerProductImage as $productimage) {
                if ($productimage->thumbnail == "yes") {
                    $strimg ='<div class="table-prof"><img style=" width:60px !important;" class="pr_img" src="'.asset("/uploads/productImages/") ."/" .$productimage->image_path.'"></div>';
                }
            }
            $currency = DB::table("currencies")
                    ->where("id", $record->currency_id)
                    ->pluck("symbol")
                    ->first();
                    if($currency==null||is_null($currency))
                    $currency = "$";
                    
            $data_arr[] = [
                "id" => $record->id,
                "name" => $record->name,
                "status" => $record->status,
                "product_price"=>$record->price_on_request=='Price on request'?'Price on request':$currency.' '.$record->product_price,
                "created_at" => date(
                    "d/m/Y   \&\\n\b\s\p\; \&\\n\b\s\p\; \&\\n\b\s\p\;  g:i A",
                    strtotime($record->created_at)
                ),
                "cat_name" => $strcat_parent,
                "cat_name1" => $strcat,
                "strimg" => $strimg,
                "product_visibility"=>$record->product_visibility

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


    public function SellerProductDetails($productId)
    {
        if (Auth::guard("user")->check()) {
            if (!$this->PublicMiddlewareController->checkUserlogin()) {
                Auth::guard("user")->logout();
                return redirect(route("home"))->with(
                    "message",
                    "Access Denied"
                );
            }
            if (
                !$this->PublicMiddlewareController->checkUserValidity() &&
                Auth::guard("user")->user()->seller_type == "Co-Seller"
            ) {
                Auth::guard("user")->logout();
                return redirect(route("home"));
            }
        }
        $is_active_subscriber = $this->PublicMiddlewareController->checkUserContactValidity();

        $product = SellerProduct::where("id", $productId)->first();
        $seller_id = $product->user_id;
        $seller = User::find($seller_id);
        $category_id = $product->category_id;

        if (empty($product)) {
            return redirect(route("home"));
        }

        $varients = $user_id = $user = $wish_listed = "";
        $login_id = null;
        if (Auth::guard("user")->check()) {
            $login_id = $user_id = Auth::guard("user")->user()->id;
            $user = User::find($user_id);

            $wish_listed = Wishlist::where("product_id", "=", $productId)
                ->where("user_id", "=", $user_id)
                ->pluck("wishlist")
                ->first();
        }
        $clientIP = \Request::ip();
        $insight = [];
        $insight = [
            "user_id" => $login_id,
            "product_id" => $productId,
            "category_id" => $category_id,
            "ip_address" => $clientIP,
        ];
        BusinessInsight::create($insight);
        if (!empty($product->available_countries)) {
            $country_ids = explode(",", $product->available_countries);
        } else {
            $country_ids = [];
        }


         


        if (!empty($product->category_id)) {
            $category_id = explode(",", $product->category_id);
		    $categorylist = Category::whereIn("parent_id", $category_id)->where('have_product','Yes')
                            ->pluck("id")->all(); // all subcategories

                    foreach ($categorylist as $value) {
                        if (!in_array($value, $category_id)) {
                            //subctegory id not in $category_id array
                            array_push($category_id, $value);
                        } //all category ids as array

                        for ($i = 0; $i < count($category_id); $i++) {
                            $category1 = Category::where("parent_id",$category_id[$i])->where('have_product','Yes')->get();
                            foreach ($category1 as $value2) {
                                if (!in_array($value2->id, $category_id)) {
                                    array_push($category_id, $value2->id);
                                }
                            }
                        }
                }
            
            
            
            
        } else {
            $cats = $category_id = [];
        }
        $category_id_scrum=[];
        if (!empty($product->category_id)) {
            $categoryscrum=Category::find($product->category_id);
            array_push($category_id_scrum, $product->category_id);
            if (!is_null($categoryscrum->parent)) {
                foreach ($categoryscrum->getParentsNames()->reverse() as $item)
                    array_push($category_id_scrum, $item->id);
            }
            
        }

        $cats = Category::select("id", "name", "slug")
            ->whereIn("id", $category_id_scrum)
            ->get();
            
            
            
            
            
          
         $varients = []   ;

        //Varients
        foreach ($category_id as $id) {
            $varients = SellerProduct::leftJoin('users', 'users.id', '=', 'seller_products.user_id')
            ->leftJoin('countries', 'countries.id', '=', 'users.country_id')
                ->select('seller_products.*','countries.name as country_name')
                ->whereRaw('find_in_set("' . $id . '",seller_products.category_id)')
                ->where("seller_products.status", "active")
                ->where("seller_products.id", "!=", $productId)
                ->where('seller_products.product_visibility','Yes')
                ->latest()
                ->take(8)
                ->get();
        } 

        $productReviews = ProductReview::where("product_id", "=", $productId)
            ->latest()
            ->paginate(5);
        $product_images = SellerProductImage::where(
            "product_id",
            "=",
            $productId
        )->get();
        $countries = Country::all();
        $user_review_exists = "no";
        if ($user_id != "") {
            $user_review_exists = ProductReview::where("product_id", $productId)
                ->where("user_id", $user_id)
                ->exists();
        }

        $productReviewCounts = ProductReview::select("*")
            ->selectRaw("count(*) as Starcount")
            ->selectRaw("SUM(star_rating) as TotalStar")
            ->where("product_id", $productId)
            ->first();

        if ($seller->seller_type != "Master") {
            $user_1 = User::find($seller->parent_id);
        } else {
            $user_1 = User::find($seller->id);
        }

 
        
     if($this->isMobile()) { 
             return view( "frontEnd.products.product-details_mobile", compact(  "user","cats","wish_listed","seller","user_review_exists","productReviews","productId","product","countries","varients","product_images", "productReviewCounts","is_active_subscriber","user_1"));
            } else {
          return view( "frontEnd.products.product-details", compact(  "user","cats","wish_listed","seller","user_review_exists","productReviews","productId","product","countries","varients","product_images", "productReviewCounts","is_active_subscriber","user_1"));
            }   
        
       
        
        
    }

    public function SellerPendingProductDetails($productId)
    {
        if (Auth::guard("user")->check()) {
            if (!$this->PublicMiddlewareController->checkUserlogin()) {
                Auth::guard("user")->logout();
                return redirect(route("home"))->with(
                    "message",
                    "Access Denied"
                );
            }
            if (
                !$this->PublicMiddlewareController->checkUserValidity() &&
                Auth::guard("user")->user()->seller_type == "Co-Seller"
            ) {
                Auth::guard("user")->logout();
                return redirect(route("home"));
            }
        }
        $is_active_subscriber = $this->PublicMiddlewareController->checkUserContactValidity();

        $product = SellerProductTemp::where("id", $productId)->first();
        if(empty($product))
            return redirect(route("home"))->with(
                    "message",
                    "Product Not Found!!"
                );
        $seller_id = $product->user_id;
        $seller = User::find($seller_id);
        $category_id = $product->category_id;

        if (empty($product)) {
            return redirect(route("home"));
        }

        $varients = $user_id = $user = $wish_listed = "";
        $login_id = null;
        if (Auth::guard("user")->check()) {
            $login_id = $user_id = Auth::guard("user")->user()->id;
            $user = User::find($user_id);
        }

        $product_images = SellerProductImage::where(
            "product_id",
            "=",
            $productId
        )->get();
        $countries = Country::all();

        $productReviewCounts = ProductReview::select("*")
            ->selectRaw("count(*) as Starcount")
            ->selectRaw("SUM(star_rating) as TotalStar")
            ->where("product_id", $productId)
            ->first();

        if ($seller->seller_type != "Master") {
            $user_1 = User::find($seller->parent_id);
        } else {
            $user_1 = User::find($seller->id);
        }
        if (!empty($product->category_id)) {
            $category_id = explode(",", $product->category_id);
        } else {
            $cats = $category_id = [];
        }
        
        $category_id_scrum=[];
        if (!empty($product->category_id)) {
            $categoryscrum=Category::find($product->category_id);
            array_push($category_id_scrum, $product->category_id);

            if (!is_null($categoryscrum->parent)) {
                foreach ($categoryscrum->getParentsNames()->reverse() as $item)
                    array_push($category_id_scrum, $item->id);
            }
            
        }
        
        $cats = Category::select("id", "name", "slug")
            ->whereIn("id", $category_id_scrum)
            ->get();
            
        $currency_info = "";
        if(!empty($product->currency_id))
        $currency_info = Currency::find($product->currency_id);
        
        if($this->isMobile()) { 
           return view("frontEnd.products.pending-product-details_mobile",compact("user","cats", "seller","product","countries","product_images","is_active_subscriber", "user_1","currency_info"));
        } else {
            return view("frontEnd.products.pending-product-details",compact("user","cats", "seller","product","countries","product_images","is_active_subscriber", "user_1","currency_info"));
        }
        
    }

    public function EditSellerProduct($productId)
    {
        if (!Auth::guard("user")->check()) 
             return redirect(route("home"))->with("message","Access Denied");
             
        
        if (Auth::guard("user")->user()->seller_type != "Master") 
            $user_id = Auth::guard("user")->user()->parent_id;
        else
            $user_id = Auth::guard("user")->user()->id;

        $user = User::find($user_id);
        $product = SellerProduct::where("id", $productId)
            ->with("SellerProductImage")
            ->first();
           
        $vendor_id = $product->user_id;

        if (empty($product) || $vendor_id != $user_id) {
            return redirect(route("home"));
        }
        $categories = Category::where("parent_id", null)->orderBy('name','ASC')->get();
        $cat_selected = Category::where("id", $product->category_id)->first();

        if (!empty($product->available_countries)) {
            $country_ids = explode(",", $product->available_countries);
        } else {
            $country_ids = [];
        }

        // $country_ids = explode(',',$product->available_countries);
        $varients = SellerProduct::all();
        $Productbrand = Productbrand::orderBy("name")->get();
        $product_images = SellerProductImage::where("product_id", "=",$productId)->get();
        $countries = Country::all();
        $currencies = Currency::select("id", "shortcode", "symbol")->get();

        
        if($this->isMobile()) { 
           return view("frontEnd.seller.edit-Sproduct_mobile",compact("productId","cat_selected","product","Productbrand","categories","countries","user","varients","product_images","currencies"));
        } else {
            return view("frontEnd.seller.edit-Sproduct",compact("productId","cat_selected","product","Productbrand","categories","countries","user","varients","product_images","currencies"));
        }
        
    }

    public function EditPendingSellerProduct($productId)
    {
        if (Auth::guard("user")->user()->seller_type != "Master") 
            $user_id = Auth::guard("user")->user()->parent_id;
        else
            $user_id = Auth::guard("user")->user()->id;
        
        $user = User::find($user_id);
        $product = SellerProductTemp::where("id", $productId)
            ->with("SellerProductImageTemp")
            ->first();
        if(empty($product))
            return redirect(route("home"))->with(
                    "message",
                    "Product Not Found!!"
                );
        $vendor_id = $product->user_id;

        if (empty($product) || $vendor_id != $user_id) {
            return redirect(route("home"));
        }
        $categories = Category::where("parent_id", null)->orderBy('name','ASC')->get();
        $cat_selected = Category::where("id", $product->category_id)->first();

        if (!empty($product->available_countries)) {
            $country_ids = explode(",", $product->available_countries);
        } else {
            $country_ids = [];
        }

        // $country_ids = explode(',',$product->available_countries);
        $varients = SellerProduct::all();
        $Productbrand = Productbrand::orderBy("name")->get();
        $product_images = SellerProductImageTemp::where(
            "product_id",
            "=",
            $productId
        )->get();
        $countries = Country::all();
        $currencies = Currency::select("id", "shortcode", "symbol")->get();

        
        if($this->isMobile()) { 
           return view("frontEnd.seller.edit-pending-Sproduct_mobile",compact("productId","cat_selected", "product","Productbrand", "categories","countries","user","varients","product_images", "currencies"));
        } else {
           return view("frontEnd.seller.edit-pending-Sproduct",compact("productId","cat_selected", "product","Productbrand", "categories","countries","user","varients","product_images", "currencies"));
        }
        
    }

    public function deleteSellerimage(Request $request)
    {
        $images = SellerProductImage::findOrFail($request->id);
        if (File::exists("/uploads/productImages/" . $images->image_path)) {
            File::delete("/uploads/productImages/" . $images->image_path);
        }

        SellerProductImage::find($request->id)->delete();
        $SellerProductImage =SellerProductImage::where('product_id',$images->product_id)->first();
        if($SellerProductImage) 
            $SellerProductImage->update(['thumbnail'=>'yes']);
        $returnArray["result"] = true;
        $returnArray["message"] = "Testimonial image removed successfully.";
        return response()->json($returnArray);
        //return back();
    }
    
    //update product visibility
    public function updateselerproductvisibility(Request $request)
    { 
        $id=$request->id;
        $status=$request->status;
        $Product = SellerProduct::find($id);
        $Product_data = SellerProduct::find($id)->update(['product_visibility'=>$status]);
        //category checking products
        if($Product->category_id!=''){
            $parents = collect([]);
            $parent = Category::where('id', $Product->category_id)->first();
            //array push
            while(!is_null($parent)) {
                $parents->push($parent);
                $parent = $parent->parent;
            }
            $old_category=$parents->pluck('id')->all();
            $commaarray=implode(",",$old_category);
            //check and fix value next level category products exist
            $result= $this->PublicMiddlewareController->getnextlevelproductexist($Product->category_id,$commaarray);
             if($result==false)
             {
                $parent = Category::where('id', $Product->category_id)->first();
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
        echo json_encode($Product_data);  
    }

    public function deletePendingSellerimage(Request $request)
    {
        $images = SellerProductImageTemp::findOrFail($request->id);
        if(filter_var($images->image_path, FILTER_VALIDATE_URL) === TRUE)
        {    
            if (File::exists("/uploads/productImages/" . $images->image_path)) {
                File::delete("/uploads/productImages/" . $images->image_path);
            }
        }

        SellerProductImageTemp::find($request->id)->delete();
        
        $sellerProductImageTemp =SellerProductImageTemp::where('product_id',$images->product_id)->first();
        if($sellerProductImageTemp) 
            $sellerProductImageTemp->update(['thumbnail'=>'yes']);
        $returnArray["result"] = true;
        $returnArray["message"] = "Image removed successfully.";

        return response()->json($returnArray);
        //return back();
    }

    public function updateSellerProduct(Request $request)
    {
      
         $data = $request->validate([
        "name" => "required|string",
        "category_id" => "required",
        "product_price" => [
            "nullable", 
            function ($attribute, $value, $fail) {
                if (empty($value)) {
                    return; 
                }
                if (!is_numeric($value)) {
                    $fail('The price must be a number.');
                }
            },
            "required_unless:price_on_request,!=,Price on request", 
        ],
        
        ], [
            'name.required' => 'The product description field is required.',
            'category_id.required' => 'The category field is required.',
            'product_price.required_unless' => 'The price field is required.',
        ]);
        $productId = $request->get("id");
        $product = SellerProduct::find($productId);

        $available_countries = $category_id = $variants = "";
        if (!empty($request->input("available_countries"))) {
            $available_countries = $request->input("available_countries");
            $available_countries = implode(",", $available_countries);
        }
        $category_id = "";
        if (!empty($request->input("category_id"))) {
            $category_id = $request->input("category_id");
        }

        if (!empty($request->input("subcategory_id"))) {
            $category_id = $request->input("subcategory_id");
        }

        if (!empty($request->input("subsubcategory_id"))) {
            $category_id = $request->input("subsubcategory_id");
        }
        if (!empty($request->input("variants"))) {
            $variants = $request->input("variants");
            $variants = implode(",", $variants);
        }
        $input = $request->all();
        if(!array_key_exists("unlimited_stock",$input))
            $input['unlimited_stock']='No';
          /*  if(!array_key_exists("stock_count",$input) || $input['stock_count']==null)
            $input['stock_count']=0;*/
        $input["price_on_request"] = 'No';
        if ($request->input("price_on_request") != "") {
            $input["price_on_request"] = $request->input("price_on_request");
        }
        $input["price_negotiable"] = null;
        if ($request->input("price_negotiable") != "") {
            $input["price_negotiable"] = $request->input("price_negotiable");
        }
        
        
        $input["currency_id"] = null;
        if ($request->input("currency_id") != "") {
            $input["currency_id"] = $request->input("currency_id");
        }
       
        // Brand Insert section below
        if (!empty($request->input("brands"))) {
            $brand_data = Productbrand::where(
                DB::raw("lower(name)"),
                strtolower(trim($request->input("brands")))
            )
                ->pluck("id")
                ->first();
            if (!empty($brand_data)) {
                $input["brands"] = $brand_data;
            } else {
                $newbrand = ["name" => $request->input("brands")];
                $brand_data = Productbrand::create($newbrand);
                $input["brands"] = $brand_data->id;
            }
        } else {
            $input["brands"] = "";
        }

        $input["available_countries"] = $available_countries;
        $input["category_id"] = $category_id;
        $input["variants"] = $variants;

        // if ($request->hasFile("product_image")) {
        //     $file = $request->file("product_image");
        //     $fileName = time() . "_" . $file->getClientOriginalName();
        //     $destinationPath = public_path() . "/uploads/productImages";
        //     $file->move($destinationPath, $fileName);
        //     $img["image_path"] = $fileName;
        //     $img["thumbnail"] = "yes";
        //     $img["product_id"] = $productId;
        //     DB::table("seller_product_images")
        //         ->where([
        //             ["product_id", "=", $productId],
        //             ["thumbnail", "=", "yes"],
        //         ])
        //         ->delete();
        //     SellerProductImage::create($img);
        // }
    }

    public function profile_account_delete(Request $request)
    {
        $input = [];
        $input["reason"] = $request->input("reason");
        $input["user_id"] = $request->input("user_id");
        $input["status"] = "New";

        $value = ProfileAccountDeleteRequest::create($input);

        return json_encode($value->id);
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

    public function updateSProduct(Request $request)
    {
        
       
        $data = $request->validate([
        "name" => "required|string",
        "category_id" => "required",
        "product_price" => [
            "nullable", 
            function ($attribute, $value, $fail) {
                if (empty($value)) {
                    return; 
                }
                if (!is_numeric($value)) {
                    $fail('The price must be a number.');
                }
            },
            "required_unless:price_on_request,!=,Price on request", 
        ],
        
        ], [
            'name.required' => 'The product description field is required.',
            'category_id.required' => 'The category field is required.',
            'product_price.required_unless' => 'The price field is required.',
        ]);

       /* if ($request->input("unlimited_stock") != "Unlimited") {
            $data = $request->validate([
                "stock_count" => "required|numeric",
            ]);
        }*/
        $productId = $request->get("id");
        $product = SellerProduct::find($productId);

        $available_countries = $category_id = $variants = "";
        if (!empty($request->input("available_countries"))) {
            $available_countries = $request->input("available_countries");
            $available_countries = implode(",", $available_countries);
        }
        $category_id = "";
        if (!empty($request->input("category_id"))) {
            $category_id = $request->input("category_id");
        }

        if (!empty($request->input("subcategory_id"))) {
            $category_id = $request->input("subcategory_id");
        }

        if (!empty($request->input("subsubcategory_id"))) {
            $category_id = $request->input("subsubcategory_id");
        }
        if (!empty($request->input("variants"))) {
            $variants = $request->input("variants");
            $variants = implode(",", $variants);
        }
        $input = $request->all();
            if(!array_key_exists("unlimited_stock",$input))
            $input['unlimited_stock']='No';
           /* if(!array_key_exists("stock_count",$input) || $input['stock_count']==null)
            $input['stock_count']=0;*/
        $product_price=null;
        if(!empty($request->input('product_price'))) {
        $product_price  = $request->input('product_price');
		$product_price = (double) str_replace(',', '', $product_price)   ;
		$product_price = round($product_price,3);
        } 
		$input['product_price']=$product_price;        
        
        $input["price_on_request"] = 'No';
        if ($request->input("price_on_request") != "") {
            $input["price_on_request"] = $request->input("price_on_request");
        }
        $input["price_negotiable"] = null;
        if ($request->input("price_negotiable") != "") {
            $input["price_negotiable"] = $request->input("price_negotiable");
        }
        // Brand Insert section below
       /* if (!empty($request->input("brands"))) {
            $brand_data = Productbrand::where(
                DB::raw("lower(name)"),
                strtolower(trim($request->input("brands")))
            )
                ->pluck("id")
                ->first();
            if (!empty($brand_data)) {
                $input["brands"] = $brand_data;
            } else {
                $newbrand = ["name" => $request->input("brands")];
                $brand_data = Productbrand::create($newbrand);
                $input["brands"] = $brand_data->id;
            }
        } else {
            $input["brands"] = "";
        }*/

        $input["available_countries"] = $available_countries;
        $input["category_id"] = $category_id;
        $input["variants"] = $variants;

        // if ($request->hasFile("product_image")) {
        //     $file = $request->file("product_image");
        //     $fileName = time() . "_" . $file->getClientOriginalName();
        //     $destinationPath = public_path() . "/uploads/productImages";
        //     $file->move($destinationPath, $fileName);
        //     $img["image_path"] = $fileName;
        //     $img["thumbnail"] = "yes";
        //     $img["product_id"] = $productId;
        //     DB::table("seller_product_images")
        //         ->where([
        //             ["product_id", "=", $productId],
        //             ["thumbnail", "=", "yes"],
        //         ])
        //         ->delete();
        //     SellerProductImage::create($img);
        // }

        if ($request->hasFile("product_gallery")) {
            $files = $request->file("product_gallery");
             $i=0;
              $SellerProductImage =SellerProductImage::where('product_id',$productId)
             ->where('thumbnail','yes')->first();
            foreach ($files as $file) {
                $imageName = time() . "_" . $file->getClientOriginalName();
                $img["product_id"] = $productId;
                if($i==0 && empty($SellerProductImage))
                {    
                    $img["thumbnail"] = "yes";
                    $i=1;
                }
                else
                    $img["thumbnail"] = "no";
                $img["image_path"] = $imageName;
                $destinationPath = public_path() . "/uploads/productImages";
                $file->move($destinationPath, $imageName);
                SellerProductImage::create($img);
            }
        }
        $old_category_id=$product->category_id;

        $product->update($input);
        if($old_category_id!=$category_id)
        {
            if($old_category_id!='')
                $this->have_product($old_category_id);
        
        }
        
            //category checking products        
            if($category_id!='')
                        $this->have_product($category_id);
                     
        
        
        $msg='Product '.$request->input('name').' has been updated!'; 
        \Session::flash('product_message',$msg);
        \Session::reflash(); 
        return json_encode($msg);
       
    }

    public function updatePendingSProduct(Request $request)
    {
        
        
        $data = $request->validate([
        "name" => "required|string",
        "category_id"=> ["required_if:categories,==,null"],
        "categories"=> ["required_if:category_id,==,null"],
        "product_price" => [
            "nullable", 
            function ($attribute, $value, $fail) {
                if (empty($value)) {
                    return; 
                }
                if (!is_numeric($value)) {
                    $fail('The price must be a number.');
                }
            },
            "required_unless:price_on_request,!=,Price on request", 
        ],
        
        ], [
            'name.required' => 'The product description field is required.',
            'product_price.required_unless' => 'The price field is required.',
            'category_id.required_if' => ' The category field is required.',
            'categories.required_if' => ' The category field is required.'
        ]);
       

      /*  if ($request->input("unlimited_stock") != "Unlimited") {
            $data = $request->validate([
                "stock_count" => "required|numeric",
            ]);
        }*/
        $productId = $request->get("id");
        $product = SellerProductTemp::find($productId);

        $category_id = $variants = "";

        if (!empty($request->input("category_id"))) {
            $category_id = $request->input("category_id");
        }
        

        if (!empty($request->input("subcategory_id"))) {
            $category_id = $request->input("subcategory_id");
        }

        if (!empty($request->input("subsubcategory_id"))) {
            $category_id = $request->input("subsubcategory_id");
        }

        if (!empty($request->input("variants"))) {
            $variants = $request->input("variants");
            $variants = implode(",", $variants);
        }
        
        $input = $request->all();
            if(!array_key_exists("unlimited_stock",$input))
            $input['unlimited_stock']='No';
          /*  if(!array_key_exists("stock_count",$input) || $input['stock_count']==null)
            $input['stock_count']=0;*/
        $product_price=null;
        if(!empty($request->input('product_price'))) {
        $product_price  = $request->input('product_price');
		$product_price = (double) str_replace(',', '', $product_price)   ;
		$product_price = round($product_price,3);
        } 
		$input['product_price']=$product_price;		
        $input["variants"] = $variants;
        if(!empty($request->input('currency_id'))) {
        $input['currency_id'] =  $request->input('currency_id');
        } 
        
        $input["price_on_request"] = "No";
        if ($request->input("price_on_request") != "") {
            $input["price_on_request"] = $request->input("price_on_request");
        }
        $input["price_negotiable"] = null;
        if ($request->input("price_negotiable") != "") {
            $input["price_negotiable"] = $request->input("price_negotiable");
        }
        $input["category_id"] = $category_id;
        $input["variants"] = $variants;
          $input["country_ids"] = null;
        if (!empty($request->input("country_ids"))) {
            $country_ids = $request->input("country_ids");
            $country_ids = implode(",", $country_ids);
            $input["country_ids"] = $country_ids;
        }
        $input["available_countries"] = null;
        if (!empty($request->input("country_ids"))) {
            $available_countries = $request->input("country_ids");
            $available_countries = implode(",", $available_countries);
            $input["available_countries"] = $available_countries;
        }
        $cats_path='';
        if($category_id!="")
        {
            $category=Category::find($category_id);
            
            $i=0; 
            if ($category->getParentsNames()) {
                foreach ($category->getParentsNames()->reverse() as $item)
                {
                        if($i==0)
                             $cats_path = $item->name;
                        else if($i>0)
                             $cats_path .= '>'.$item->name;
                        ++$i;
                }
                $cats_path.='>'.$category->name;
            }
            else
                $cats_path=$category->name;
        }
        else
             $cats_path= $request->input('categories');
        $input['categories']= $cats_path;  
        
        // if ($request->hasFile("product_image")) {
        //     $file = $request->file("product_image");
        //     $fileName = time() . "_" . $file->getClientOriginalName();
        //     $destinationPath = public_path() . "/uploads/productImages";
        //     $file->move($destinationPath, $fileName);
        //     $img["image_path"] = $fileName;
        //     $img["thumbnail"] = "yes";
        //     $img["product_id"] = $productId;
        //     DB::table("seller_product_image_temps")
        //         ->where([
        //             ["product_id", "=", $productId],
        //             ["thumbnail", "=", "yes"],
        //         ])
        //         ->delete();
        //     SellerProductImageTemp::create($img);
        // }

        if ($request->hasFile("product_gallery")) {
            $files = $request->file("product_gallery");
            $i=0;
            $sellerProductImageTemp =SellerProductImageTemp::where('product_id',$productId)
             ->where('thumbnail','yes')->first();

            foreach ($files as $file) {
                $imageName = time() . "_" . $file->getClientOriginalName();
                $img["product_id"] = $productId;
                if($i==0 && empty($sellerProductImageTemp))
                {    
                    $img["thumbnail"] = "yes";
                    $i=1;
                }
                else
                    $img["thumbnail"] = "no";
                $img["image_path"] = $imageName;
                $destinationPath = public_path() . "/uploads/productImages";
                $file->move($destinationPath, $imageName);
                SellerProductImageTemp::create($img);
            }
        }

        $product->update($input);
        $msg='Product '.$request->input('name').' has been updated!'; 
        \Session::flash('product_message',$msg);
        \Session::reflash(); 
        return json_encode($msg);

    }

    public function deleteSProduct($productId)
    {
        $product = SellerProduct::find($productId);
        $productImages = SellerProductImage::where(
            "product_id",
            "=",
            $productId
        )->get();

        if (empty($product)) {
            return redirect()
                ->route("seller.products")
                ->with("message", "Product not Exists");
        }

        if (!empty($productImages)) {
            foreach ($productImages as $productImage) {
                $path =
                    public_path() ."/uploads/productImages/" .$productImage->image_path;
                if (file_exists($path)) {
                    @unlink($path);
                    $productImage->delete();
                }
            }
        }
        $product->delete();

        //category checking products
        if($product->category_id!=''){
            $this->have_product($product->category_id);
        }
        
        return redirect()
            ->route("seller.products")
            ->with("message_delete", "Product Deleted!");
    }

    public function deletePendingSProduct($productId)
    {
        $product = SellerProductTemp::find($productId);
        $productImages = SellerProductImageTemp::where(
            "product_id",
            "=",
            $productId
        )->get();

        if (empty($product)) {
            return redirect()
                ->route("seller.products")
                ->with("message", "Product not Exists");
        }

        if (!empty($productImages)) {
            foreach ($productImages as $productImage) {
                $path =public_path() ."/uploads/productImages/" .$productImage->image_path;
                if (file_exists($path)) {
                    @unlink($path);
                    $productImage->delete();
                }
            }
        }

        $product->delete();
        return redirect()
            ->route("seller.products")
            ->with("message_pending_deleted", "Product Deleted!");
    }

    public function AddToWishlist(Request $request)
    {
        $productId = $input["product_id"] = $request->id;
        $user_id = $input["user_id"] = Auth::guard("user")->user()->id;
        $input["wishlist"] = "Yes";
        $id_exists = Wishlist::where("product_id", "=", $productId)
            ->where("user_id", "=", $user_id)
            ->pluck("id")
            ->first();
        if ($id_exists > 0) {
            Wishlist::where("product_id", "=", $productId)
                ->where("user_id", "=", $user_id)
                ->update([
                    "wishlist" => "Yes",
                ]);
        } else {
            Wishlist::create($input);
        }
        $returnArray["result"] = true;
        $returnArray["message"] = "Added to wishlist.";

        return response()->json($returnArray);
    }

    public function RemoveWishlist(Request $request)
    {
        $productId = $input["product_id"] = $request->id;
        $user_id = Auth::guard("user")->user()->id;
        $input["wishlist"] = "No";
        Wishlist::where("product_id", "=", $request->id)
            ->where("user_id", "=", $user_id)
            ->update([
                "wishlist" => "No",
            ]);

        $returnArray["result"] = true;
        $returnArray["message"] = "Added to wishlist.";

        return response()->json($returnArray);
    }

    public function List_co_sellers()
    { 
        if (Auth::guard("user")->check()) {
            if (!$this->PublicMiddlewareController->checkUserlogin()) {
                Auth::guard("user")->logout();
                return redirect(route("home"))->with(
                    "message",
                    "Access Denied"
                );
            }
            if (
                !$this->PublicMiddlewareController->checkUserValidity() ||
                Auth::guard("user")->user()->seller_type == "Co-Seller"
            ) {
               // return redirect(route("home"));
            }
        }

        $user_id = Auth::guard("user")->user()->id;
        $usertype = Auth::guard("user")->user()->usertype; 
        if (Auth::guard("user")->user()->seller_type == "Co-Seller") { 
            //return redirect()->route("home");
        }
        $user = User::where("parent_id", $user_id)
            ->where("status", "<>", "Deleted")
            ->get();

        $package_data = DB::table("subscriptions")
            ->leftJoin(
                "order_details",
                "subscriptions.order_id",
                "=",
                "order_details.id"
            )
            ->where("subscriptions.user_id", "=", $user_id) 
            ->where("subscriptions.status", "Active")
            ->orderBy("subscriptions.id", "DESC")
            ->first();

        $noofacnt = $package_data->no_of_accounts;
        if($this->isMobile()) { 
           return view("frontEnd.seller.co-sellers_mobile", compact("user", "noofacnt"));
        } else {
            return view("frontEnd.seller.co-sellers", compact("user", "noofacnt"));
        }
    }

    public function addNewSeller(Request $request)
    {
        
        
        
        $package_data = DB::table("subscriptions")
            ->leftJoin(
                "order_details",
                "subscriptions.order_id",
                "=",
                "order_details.id"
            )
            ->leftJoin(
                "packages",
                "packages.id",
                "=",
                "order_details.package_id"
            )
            ->where(
                "subscriptions.user_id",
                "=",
                Auth::guard("user")->user()->id
            )
            ->where("subscriptions.status", "Active")
            ->orderBy("subscriptions.id", "DESC")
            ->first();

        $user = User::where(
            "parent_id",
            Auth::guard("user")->user()->id
        )
        ->where("status", "<>", "Deleted")
        ->get();

       /* if (
            count($user) >= $package_data->no_of_accounts ||
            $package_data->expairy_date < date("Y-m-d") ||
            $package_data->subscription_type != "Extended"
        ) {
            return json_encode("Co-Seller Adding count Exceeded!");
        }*/

        $request->validate([
            "name" => "required",
            "email" => [
                "required",
                "email",
                "regex:/(.+)@(.+)\.(.+)/i",
                Rule::unique("users")->where(function ($query) {
                    return $query->where("status", "<>", "Deleted");
                }),
            ],
            "phone" => [
                "required",
                'regex:/^(^([+]+)(\d+)?$)$/',
                Rule::unique("users")->where(function ($query) {
                    return $query->where("status", "<>", "Deleted");
                }),
            ],
           // "username" => "required",
            'password' => [
                'required',
                'string',
                'min:8', 'confirmed',         // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
        ]);

        $input = $request->all();
            if(!array_key_exists("unlimited_stock",$input))
            $input['unlimited_stock']='No';
          /*  if(!array_key_exists("stock_count",$input) || $input['stock_count']==null)
            $input['stock_count']=0;*/
        $input["parent_id"] = Auth::guard("user")->user()->id;
        $input["seller_type"] = "Co-Seller";
        $input["country_id"] =Auth::guard('user')->user()->country_id;  
        $usertype = Auth::guard("user")->user()->usertype;
        if ($usertype == "seller") {
            $input["usertype"] = "seller";
        }
        if ($usertype == "buyer") {
            $input["usertype"] = "buyer";
        }
        $input["password"] = Hash::make($request->get("password"));

        if (request()->hasFile("imgupload")) {
            $extension = request("imgupload")->extension();
            $fileName = "user_pic" . time() . "." . $extension;
            $destinationPath = public_path() . "/uploads/userImages";
            request("imgupload")->move($destinationPath, $fileName);
            $input["profile_pic"] = $fileName;
        }

        $user = User::create($input);
        Mail::send(
            "admin/seller/email-template",
            [
                "name" => $input["name"],
                "email" => $input["email"],
                "password" => $request->get("password"),
            ],
            function ($message) use ($input) {
                $message->from("example@gmail.com", "Fmcg");
                $message
                    ->to($input["email"], $input["name"])
                    ->subject("Fmcg Login Credential");
            }
        );
        $usertype = Auth::guard('user')->user()->usertype;  
        if($usertype=='seller')
            echo json_encode("Co-User Added");
        else
            echo json_encode("Co-User Added");

       
    }

    public function usersellersstatusupdates(Request $request)
    {
        $status = $request->get("status");
        $id = $request->get("id");

        $User = User::find($id);
        $User->status = $status;
        $update = $User->save();
        if ($update) {
            echo json_encode("User " . $status);
        } else {
            echo json_encode("Status Not Changed");
        }
    }

    public function deleteUser($userId)
    {
        $user = User::find($userId);
        if (empty($user)) {
            return redirect()
                ->route("user.listcosellers")
                ->with("message", "User not Exists");
        }
        $user->delete();
        return redirect()
            ->route("user.listcosellers")
            ->with("message", "User Deleted!");
    }

    public function WishlistItems()
    {
        if (Auth::guard("user")->check()) {
            if (!$this->PublicMiddlewareController->checkUserlogin()) {
                Auth::guard("user")->logout();
                return redirect(route("home"))->with(
                    "message",
                    "Access Denied"
                );
            }
            if (
                !$this->PublicMiddlewareController->checkUserContactValidity()
            ) {
                Auth::guard("user")->logout();
                return redirect(route("home"));
            }
        }

        $user_id = Auth::guard("user")->user()->id;
        $user = User::find($user_id);
        //$products = SellerProduct::where('user_id',$user_id)->get();
        $wishlists = Wishlist::where("user_id", $user_id)
            ->where("wishlist", "Yes")
            ->pluck("product_id")
            ->all();
        $products = SellerProduct::where("status", "active")
            ->whereIn("id", $wishlists)
            ->with("SellerProductImage")
            ->latest()
            ->paginate(20);
        $countries = Country::all();
        $categories = Category::where("parent_id", null)->orderBy('name','ASC')->get();
        if (Auth::guard("user")->check()) {
            
            if($this->isMobile()) { 
              return view("frontEnd.seller.WishlistItems_mobile",compact("user", "products", "categories", "countries"));
            } else {
         
                 return view("frontEnd.seller.WishlistItems",compact("user", "products", "categories", "countries"));
            }
            
            
           
        } else {
            return redirect()->route("home");
        }
    }

    public function WishlistItemsFilter(Request $request)
    {
        $filter = $request->wish_filter;

        if (Auth::guard("user")->check()) {
            if (!$this->PublicMiddlewareController->checkUserlogin()) {
                Auth::guard("user")->logout();
                return redirect(route("home"))->with(
                    "message",
                    "Access Denied"
                );
            }
            if (
                !$this->PublicMiddlewareController->checkUserValidity() &&
                Auth::guard("user")->user()->seller_type == "Co-Seller"
            ) {
                Auth::guard("user")->logout();
                return redirect(route("home"));
            }
        }

        $user_id = Auth::guard("user")->user()->id;
        $user = User::find($user_id);
        //$products = SellerProduct::where('user_id',$user_id)->get();
        $wishlists = Wishlist::where("user_id", $user_id)
            ->where("wishlist", "Yes")
            ->pluck("product_id")
            ->all();

        $products = SellerProduct::select("*")
            ->where("status", "active")
            ->whereIn("id", $wishlists)
            ->when($filter == "Active", function ($q) {
                return $q->where(function ($query) {
                    $query
                        ->where("product_expiry", ">", Carbon::now())
                        ->orWhereNull("product_expiry");
                });
            })
            ->when($filter == "Expired", function ($q) {
                return $q->where("product_expiry", "<", Carbon::now());
            })
            ->with("SellerProductImage")
            ->latest()
            ->paginate(20);

        $countries = Country::all();
        $categories = Category::where("parent_id", null)->orderBy('name','ASC')->get();
        if (Auth::guard("user")->check()) {
            if($this->isMobile()) { 
              return view("frontEnd.seller.WishlistItems_mobile",compact("user", "products", "categories", "countries"));
            } else {
         
                 return view("frontEnd.seller.WishlistItems",compact("user", "products", "categories", "countries"));
            }
         } else {
            return redirect()->route("home");
        }
    }

    public function loadreview(Request $request)
    {
        $start_from = $request->input("start_from");
        $per_page = $request->input("per_page");
        $productId = $request->input("product_id");
        $productReviews = ProductReview::where("product_id", "=", $productId);
        $count = $productReviews->count();
        $productReviews = $productReviews
            ->skip($start_from)
            ->take($per_page)
            ->get();

        $data_return = [];
        foreach ($productReviews as $data) {
            $created_at = date("M d , Y", strtotime($data->created_at));
            $data_return[] = [
                "name" => $data->name,
                "email" => $data->email,
                "review_title" => $data->review_title,
                "review" => $data->review,
                "star_rating" => $data->star_rating,
                "created_at" => $created_at,
            ];
        }
        $return_array = ["count" => $count, "productReviews" => $data_return];
        return json_encode($return_array);
    }
    public function userproductbulkdelete(Request $request)
    {
        $seller_products=$request->get('seller_products');
        $action_type=$request->get('action_type');
        if($action_type=='products')
        {  
             $allproducts=SellerProduct::whereIn('id',$seller_products)->get();
             SellerProduct::whereIn('id',$seller_products)->delete();

              foreach ($allproducts as $key => $value) {
                  //category checking products        
                          if($value->category_id!='')
                         $this->have_product($value->category_id);
                    
              }
            
        }
        else
            SellerProductTemp::whereIn('id',$seller_products)->delete();
        echo json_encode('Product Deleted');
    }
    
     public function have_product($category_id){
            $parents = collect([]);
            $parent = Category::where('id', $category_id)->first();
            //array push
            while(!is_null($parent)) {
                $parents->push($parent);
                $parent = $parent->parent;
            }
            $old_category=$parents->pluck('id')->all();
            $commaarray=implode(",",$old_category);
            //check and fix value next level category products exist
            $result= $this->PublicMiddlewareController->getnextlevelproductexist($category_id,$commaarray);
             if($result==false)
             {
                $parent = Category::where('id', $category_id)->first();
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
