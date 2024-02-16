<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use App\Models\Product;
use App\User;
use App\Models\Country;
use App\Models\ProductRequest;
use App\Models\Currency;
use App\Models\Category;
use App\Models\Productbrand;
use App\Models\SellerProduct;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Illuminate\Validation\Rule;
use File;
use DB;
use URL;
use App\Imports\SellerProductImport;
use App\Imports\AdminProductImport;
use Carbon\Carbon;
use App\Http\Controllers\FrontEnd\PublicMiddlewareController;
class ProductController extends Controller
{
    protected $PublicMiddlewareController;
    public function __construct(PublicMiddlewareController $PublicMiddlewareController) 
    {
        $this->PublicMiddlewareController = $PublicMiddlewareController;
    }
    //ajax call list product
    public function listProducts(Request $request) {
        
        $category_id=$request->get('category_id');
        $search_key=$request->get('search_key');
        $stock=$request->get('stock');
        $categories = Category::where('parent_id', null)->where('name','<>','')->orderBy('name','ASC')->get();
        return view('admin.products.listproducts',compact('categories','category_id','search_key','stock'));
    }

    //load seller products display page
    public function listSellerProducts(Request $request) {

        $category_id=$request->get('category_id');
        $search_key=$request->get('search_key');
        $stock=$request->get('stock');
        $categories = Category::where('parent_id', null)->where('name','<>','')->orderBy('name','ASC')->get();
        return view('admin.products.listSellerproducts',compact('categories','category_id','search_key','stock'));
    }
    //display csv upload page
    public function uploadcsvfile() {

        $sellers_list=User::where('usertype','seller')->get();
        
        return view('admin.products.uploadcsv',compact('sellers_list'));
    }
    //csv import products to single seller user 
    public function import( Request $request) 
    {
        $seller_id=$request->seller_id;
        $result = Excel::import(new SellerProductImport($seller_id),request()->file('sellerfile'));
        return back()->withInput()->with('success', 'Products Imported Successfully.');
    }
    //csv imports products to admin table
    public function adminimport( Request $request) 
    {
       
        $result = Excel::import(new AdminProductImport,request()->file('file'));  
        return back()->withInput()->with('success', 'Products Imported Successfully.');
    }
    //update featured product list items
    public function updatesellerfeaturedproduct(Request $request)
    { 
        $id=$request->id;
        $status=$request->status;
        //update featured product
        $Product = SellerProduct::find($id)->update(['featured_product'=>$status]);
        echo json_encode($Product);  
    }
    public function updatesellerseasonproduct(Request $request)
    {
        $id=$request->id;
        $status=$request->status;
        //update featured product
        $Product = SellerProduct::find($id)->update(['special_season'=>$status]);
        echo json_encode($Product);  
    }
    //ajax request fetch admin products
      public function getproductlist(Request $request)
    {  
        
        
        
    //     $allproducts=SellerProduct::leftJoin('users', 'users.id', '=', 'seller_products.user_id')
    //     ->where('product_visibility','Yes')->where('seller_products.status','active')->where('users.status','<>','Deleted')->get();
    // 	foreach ($allproducts as $key => $value) {
    // 		if($value->category_id!=''){
    //         $parents = collect([]);
    //         $parent = Category::where('id', $value->category_id)->first();

    //         while(!is_null($parent)) {
    //             $parents->push($parent);
    //             $parent = $parent->parent;
    //         }
    //         $have_product_category=$parents->pluck('id')->all();
    //         Category::whereIn('id', $have_product_category)->update(['have_product'=>'Yes']);
    //     }
    // 	} 
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
        // $searchValue = $search_arr['value']; // Search value
        
        $category_id=[]; 
        $categoryid=$request->get('category_id'); 
        //get product parent and subcategory id
        if($categoryid !=null){

                    $categorylist = Category::where("parent_id", $categoryid)
                            ->pluck("id")->all(); // all subcategories
                    array_push($category_id,$categoryid); //parent category to array}

                    foreach ($categorylist as $value) {
                        if (!in_array($value, $category_id)) {
                            //subctegory id not in $category_id array
                            array_push($category_id, $value);
                        } //all category ids as array

                        for ($i = 0; $i < count($category_id); $i++) {
                            $category1 = Category::where("parent_id",$category_id[$i])->get();
                            foreach ($category1 as $value2) {
                                //assign all id
                                if (!in_array($value2->id, $category_id)) {
                                    array_push($category_id, $value2->id);
                                }
                            }
                        }
                }
        }
        //total product count
        $totalRecords = Product::select('count(*) as allcount')->count();
        //total filtered product count
        $totalRecordswithFilter = Product::select('count(*) as allcount') 
            ->when($request->get('search_key') !="", function ($query) use ($request) {
                 $query->where(DB::raw('CONCAT_WS(products.name,COALESCE(products.company_name,""),COALESCE(products.location,""))'), 'LIKE','%'.$request->get('search_key').'%');
            })
            ->where(function ($query) use($categoryid,$category_id){

                if($categoryid !=null){

                 foreach ($category_id as $id) 
                     $query->orwhereRaw('find_in_set("'.$id.'",category_id)');
                }
                        
            })
           ->when($request->get('stock') !=null, function ($query) use ($request) {
                 if($request->get('stock')=="instock")
                     {  $query->where(function ($query){
                             $query->where("stock_count",">",0)->orwhere('unlimited_stock','Unlimited');
                            });}
                    else
                      $query-> where("stock_count","<=",0)->orWhereNull('stock_count')->where('unlimited_stock','<>','Unlimited');
                        
            })
           ->count();

       

        // Get records, also we have included search filter as well
        $records = Product::select('*')->when($request->get('search_key') !="", function ($query) use ($request) {
                 $query->where(DB::raw('CONCAT_WS(products.name,COALESCE(products.company_name,""),COALESCE(products.location,""))'), 'LIKE','%'.$request->get('search_key').'%');
            })
           
             ->where(function ($query) use($categoryid,$category_id){

                if($categoryid !=null){

                 foreach ($category_id as $id) 
                     $query->orwhereRaw('find_in_set("'.$id.'",category_id)');
                }
                        
            })
             ->when($request->get('stock') !=null, function ($query) use ($request) {
                 if($request->get('stock')=="instock")
                    {$query->where(function ($query){
                             $query->where("stock_count",">",0)->orwhere('unlimited_stock','Unlimited');
                            });}
                    else
                         $query-> where("stock_count","<=",0)->orWhereNull('stock_count')->where('unlimited_stock','<>','Unlimited');   
            })->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
    
        foreach ($records as $record) {
             $strcat='';
             $Categories = explode(",",$record->category_id);
                foreach($Categories as $item)
                    $strcat.= Category::where('id',$item)->pluck('name')->first()."<br/>";
                    //product images              
                    $strimg='<div class="table-prof"><img style=" width:60px !important;" class="pr_img" src="'.asset("/uploads/defaultImages/no_image.jpg").'"/></div>';                    
                    foreach( $record->ProductImages as $productimage)
                    {     
                        if($productimage->thumbnail == 'yes') 
                           $strimg= '<div class="table-prof"><img style=" width:60px !important;" class="pr_img" src="'.asset("/uploads/productImages/").'/'.$productimage->image_path.'"/></div>';
                    }         
                                                                                        
            $start=$start+1;
            $data_arr[] = array(  
                "id" => $record->id,
                "name" => $record->name,
                "company_name" => $record->company_name,
                "location" => $record->location,
                "product_price" => $record->product_price,
                "special_season" => $record->special_season,
                
                "strcat" => $strcat,
                "strimg" => $strimg,
               );
        }
        //response array
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);       
}
//ajax request send all product of seller
public function getSellerProductlist(Request $request)
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
        //get expired sellers list
        $active_sellers= $this->PublicMiddlewareController->getexpiredsellerslist();
        $category_id=[]; 
        $categoryid=$request->get('category_id'); 
        if($categoryid !=null){

                    $categorylist = Category::where("parent_id", $categoryid)
                            ->pluck("id")->all(); // all subcategories
                    array_push($category_id,$categoryid); //parent category to array}

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
        //total product count
        $totalRecords = SellerProduct::leftJoin('users', 'users.id', '=', 'seller_products.user_id')
            ->select('count(*) as allcount')->where('users.status','<>','Deleted')
            ->where('seller_products.status','!=','deleted')->count();
            //total filtered product count
        $totalRecordswithFilter = SellerProduct::leftJoin('users', 'users.id', '=', 'seller_products.user_id')
            ->leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
            ->leftJoin('countries', 'countries.id', '=', 'users.country_id')
            ->select('count(*) as allcount')->where('users.status','<>','Deleted')
            ->where('seller_products.status','!=','deleted')
            
            ->where(function ($query) use($request){

                if($request->get('search_key') !=""){
                     $query->where(DB::raw('CONCAT_WS(users.name,buyer_companies.company_name,countries.name,buyer_companies.company_location,seller_products.name)'), 'LIKE','%'.$request->get('search_key').'%');
                }
            })
            ->where(function ($query) use($categoryid,$category_id){

                if($categoryid !=null){

                 foreach ($category_id as $id) 
                     $query->orwhereRaw('find_in_set("'.$id.'",category_id)');
                }
                        
            })
             ->when($request->get('stock') !=null, function ($query) use ($request) {
                 if($request->get('stock')=="instock")
                    {$query->where(function ($query){
                             $query->where("stock_count",">",0)->orwhere('unlimited_stock','Unlimited');
                            });}
                    else
                         $query-> where("stock_count","<=",0)->orWhereNull('stock_count')->where('unlimited_stock','<>','Unlimited');   
            })
            ->count();

       

        // Get records, also we have included search filter as well
        $records = SellerProduct::leftJoin('users', 'users.id', '=', 'seller_products.user_id')
            ->leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
            ->leftJoin('countries', 'countries.id', '=', 'users.country_id')
            ->leftJoin('categories', 'categories.id', '=', 'seller_products.category_id')
            ->select('seller_products.*','categories.name as cat_name')->where('users.status','<>','Deleted')->where('seller_products.status','!=','deleted')
            ->where(function ($query) use($request){

                if($request->get('search_key') !=""){
                     $query->where(DB::raw('CONCAT_WS(users.name,buyer_companies.company_name,countries.name,buyer_companies.company_location,seller_products.name)'), 'LIKE','%'.$request->get('search_key').'%');
                }
            })
            ->where(function ($query) use($categoryid,$category_id){

                if($categoryid !=null){

                 foreach ($category_id as $id) 
                     $query->orwhereRaw('find_in_set("'.$id.'",category_id)');
                }
                        
            })
              ->when($request->get('stock') !=null, function ($query) use ($request) {
                 if($request->get('stock')=="instock")
                    {$query->where(function ($query){
                             $query->where("stock_count",">",0)->orwhere('unlimited_stock','Unlimited');
                            });}
                    else
                         $query-> where("stock_count","<=",0)->orWhereNull('stock_count')->where('unlimited_stock','<>','Unlimited');   
            })
            ->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
       
        foreach ($records as $record) {
            //status of products
            if (!in_array($record->user_id,$active_sellers))
                $user_sub_status='<br><span style="color:white;background-color:red;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">Sub. Expired</span>';
             else
                $user_sub_status='<br><span style="color:white;background-color:green;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">Sub. Active</span>'; 
             if ($record->status=='active')
                $product_status='<br><span style="color:white;background-color:green;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">Active</span>';
             else
                $product_status='<br><span style="color:white;background-color:red;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">Pending</span>'; 
             $strcat='';
             $Categories = explode(",",$record->category_id);
             foreach($Categories as $item)
                $strcat.= Category::where('id',$item)->pluck('name')->first()."<br/>";
             //product image                     
             $strimg='<div class="table-prof"><img  class="pr_img" src="'.asset("/uploads/defaultImages/no_image.jpg").'"/></div>';                    
             foreach( $record->SellerProductImage as $productimage)
             {     
                        if($productimage->thumbnail == 'yes') 
                           $strimg= '<div class="table-prof"><img  class="pr_img" src="'.asset("/uploads/productImages/").'/'.$productimage->image_path.'"/></div>';
             }         
            $seller = User::find($record->user_id)  ;
            $ducompany_name=$seller->BuyerCompany->company_name??"";
            $start=$start+1;
            $data_arr[] = array(  
                "id" => $record->id,
                "name" => $record->name.$product_status,
                "company_name" =>  $ducompany_name .$user_sub_status,
                "location" => $seller->BuyerCompany->company_location ?? "",
                "product_price" => $record->product_price,
                "cat_name" => $strcat,
                "strimg" => $strimg,
                "special_season"=>$record->special_season,
                "featured_product"=>$record->featured_product,
                "product_visibility"=>$record->product_visibility
               
               );
        }
        //response array
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);       
}



//display product creation form
    public function createProduct()
    {   
        $categories = Category::where('parent_id', null)->where('name','<>','')->orderBy('name','ASC')->get();
        $Productbrand = Productbrand::get();
        //currency list
		$currencies =Currency::select('id','shortcode','symbol')->get();
        return view('admin.products.addproduct',compact('categories','Productbrand','currencies'));
    }
    //list all countries
    public function availbleCountries(request $request) {
        $countries = Country::where('name','Like',$request->term.'%')
            ->orderBy('name','ASC')
            ->select("id","name")
            //->limit(10)
            ->get();
        return $countries;
}
//auto complete seller search
public function autoComplateSeller(request $request) {
    $user = User::where('name','Like',$request->name.'%')
        ->select("id","name")
        ->where('usertype','seller')
        ->limit(20)
        ->get();
    $output=[];
    foreach($user as $data){
        $array = ['id' => $data->id, 'name'  =>  $data->name];
        $output[]=$array;
    }
    return json_encode($output);
}
//auto complete serach seller company
 public function autoComplateSellerCompany(request $request) {
    $user = User::leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
            ->where('users.usertype','seller')
            ->whereNotIn('users.status',['Deleted','Blocked'])
	        ->where('buyer_companies.company_name','Like',$request->name.'%')
            ->select('users.id as id','users.name as user_name',"buyer_companies.company_name as name")
            ->limit(20)
            ->get();
		
	
    $output=[];
    foreach($user as $data){
        $array = ['id' => $data->id, 'name'  =>  $data->name , 'user_name' => $data->user_name ];
        $output[]=$array;
    }
    return json_encode($output);
}

    //auto complete admin product list search
    public function autoComplateProduct(request $request) {
        $products = Product::where('name','Like',$request->term.'%')
            ->select("id","name")
            ->limit(10)
            ->get();
        return $products;
    }
//auto complete seller product search
    public function autoComplateSProduct(request $request) {
        $products = SellerProduct::where('status','active')->where('name','Like',$request->term.'%')
            ->select("id","name")
            ->limit(10)
            ->get();
        return $products;
    }
    //admin product creation
     public function saveProduct(Request $request){
       
       
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
       
                
        //countries array
        $available_countries = $category_id = $variants = "";
        if(!empty($request->input('available_countries'))) {
        $available_countries  = $request->input('available_countries');
        $available_countries=implode(",",$available_countries);
        }
        $product_price=null;
		if(!empty($request->input('product_price'))) {
        $product_price  = $request->input('product_price');
		$product_price = (double) str_replace(',', '', $product_price)   ;
		$product_price = round($product_price,3);
        } 
        $category_id ="";
            if(!empty($request->input('category_id'))) 
			    $category_id = $request->input('category_id');
		
			if(!empty($request->input('subcategory_id'))) 
			    $category_id = $request->input('subcategory_id');
		     
			if(!empty($request->input('subsubcategory_id'))) 
			    $category_id = $request->input('subsubcategory_id');
        if(!empty($request->input('variants'))) {
        $variants = $request->input('variants');
        $variants = implode(",",$variants);
        }
        $input = $request->all();
        // Brand Insert section below
        $brand_id= "";
    if(!empty($request->input('brands'))) 
        $brands = $request->input('brands');
    if($request->input('brands')) { 
        $brand_data=Productbrand::where(DB::raw('lower(name)'),strtolower($request->input('brands')))->pluck('id')->first(); 
    if(!empty($brand_data))
        $brand_id=$brand_data;
    else{
        $newbrand=array('name'=>$request->input('brands'));
        $brand_data=Productbrand::create($newbrand);
        $brand_id=$brand_data->id;
    }
    }
            if(!array_key_exists("unlimited_stock",$input))
            $input['unlimited_stock']='No';
          /*  if(!array_key_exists("stock_count",$input) || $input['stock_count']==null)
            $input['stock_count']=0;*/
        $input['brands'] = $brand_id;
        $input['available_countries'] = $available_countries;
        $input['category_id']=$category_id;
        $input['variants']=$variants;
		$input['product_price']=$product_price;
       //product save
       $input['product_description'] =nl2br($request->input('product_description'));
       
        $id = Product::create($input)->id;
        //image save
        // if($request->hasFile("product_image")){
        //     $file=$request->file("product_image");
        //     $fileName=time().'_'.$file->getClientOriginalName();
        //     $destinationPath = public_path().'/uploads/productImages' ;
        //     $file->move($destinationPath,$fileName);
        //     $img['image_path'] = $fileName;
        //     $img['thumbnail'] = "yes";
        //     $img['product_id'] = $id ;
        //     ProductImage::create($img);

        // }

            if($request->hasFile("product_gallery")){
                $files=$request->file("product_gallery");
                $i=0;
                foreach($files as $file){
                    $imageName=time().'_'.$file->getClientOriginalName();
                    $img['product_id']=$id;
                    if($i==0)
                    {    
                        $img["thumbnail"] = "yes";
                        $i=1;
                    }
                    else
                        $img["thumbnail"] = "no";
                    $img['image_path']=$imageName;
                    $destinationPath = public_path().'/uploads/productImages' ;
                    $file->move($destinationPath,$imageName);
                    ProductImage::create($img);

                }
            }
       
        return json_encode('Product has been created successfully.');
       }
       //load product edit form
      public function editProduct($productId) {
        //load product single data
        $product = Product::find($productId);
        $varients = Product::all();
        $countries = Country::all();
        $product_images= ProductImage::find($productId); 
        $product_images = ProductImage::where('product_id','=',$productId)->get();
        $categories = Category::where('parent_id', null)->orderBy('name','ASC')->get();
		$currencies =Currency::select('id','shortcode','symbol')->get();
		$cat_selected =Category::where('id', $product->category_id)->first();

        $Productbrand = Productbrand::get();
        $product_brand = '';
        if(!empty($product->brands)) 
        $product_brand = DB::table("productbrands")
                ->where("id", $product->brands)
                ->pluck("name")
                ->first();
        if(empty($productId )) 
        return redirect() ->route('admin.products.listproducts')->with('message','Product not exists');
        return view('admin.products.edit-product' ,compact('product','categories','cat_selected','varients','countries','product_images','Productbrand','product_brand','currencies'));
    
    }

    
    //update product data
    public function updateProduct(Request $request) {
        
        
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
                
        $productId = $request->get('id');
        //update product data
        $product = Product::find($productId);
       
        $available_countries = $category_id = $variants = "";
        if(!empty($request->input('available_countries'))) {
        $available_countries  = $request->input('available_countries');
        $available_countries=implode(",",$available_countries);
        }
        $category_id ="";
            if(!empty($request->input('category_id'))) 
			$category_id = $request->input('category_id');
		
			if(!empty($request->input('subcategory_id'))) 
			$category_id = $request->input('subcategory_id');
		     
			if(!empty($request->input('subsubcategory_id'))) 
			$category_id = $request->input('subsubcategory_id');
        if(!empty($request->input('variants'))) {
        $variants = $request->input('variants');
        $variants = implode(",",$variants);
        }
        $input = $request->all();
        $product_price=null;
        if(!empty($request->input('product_price'))) {
            $product_price  = $request->input('product_price');
		    $product_price = (double) str_replace(',', '', $product_price)   ;
		    $product_price = round($product_price,3);
        } 
		$input['product_price']=$product_price;
            if(!array_key_exists("unlimited_stock",$input))
            $input['unlimited_stock']='No';
           /* if(!array_key_exists("stock_count",$input) || $input['stock_count']==null)
            $input['stock_count']=0;*/
        
         $input['price_on_request']  = null;
        if($request->input('price_on_request')!='')
        $input['price_on_request'] = $request->input('price_on_request');
        $input['price_negotiable']  = null;
        if($request->input('price_negotiable')!='')
        $input['price_negotiable'] = $request->input('price_negotiable');
        $input['available_countries'] = $available_countries;
        $input['category_id']=$category_id;
        $input['variants']=$variants;
        //image save
        // if($request->hasFile("product_image")){ 
        //     $file=$request->file("product_image");
        //     $fileName=time().'_'.$file->getClientOriginalName();
        //     $destinationPath = public_path().'/uploads/productImages' ;
        //     $file->move($destinationPath,$fileName);
        //     $img['image_path'] = $fileName;
        //     $img['thumbnail'] = "yes";
        //     $img['product_id'] = $productId ;
        //     DB::table('product_images')->where([
        //         ['product_id', '=', $productId],
        //         ['thumbnail', '=', 'yes'],
        //     ])->delete();
        //     ProductImage::create($img);

        // }

            if($request->hasFile("product_gallery")){
                $files=$request->file("product_gallery");
                $i=0;
                $ProductImage =ProductImage::where('product_id',$productId)
                    ->where('thumbnail','yes')->first();
                foreach($files as $file){
                    if($i==0 && empty($ProductImage))
                    {    
                        $img["thumbnail"] = "yes";
                        $i=1;
                    }
                    else
                        $img["thumbnail"] = "no";
                    $imageName=time().'_'.$file->getClientOriginalName();
                    $img['product_id']=$productId;
                    $img['image_path']=$imageName;
                    $destinationPath = public_path().'/uploads/productImages' ;
                    $file->move($destinationPath,$imageName);
                    ProductImage::create($img);

                }
            }
        			
	// Brand Insert section below
        $brand_id= "";
    if(!empty($request->input('brands'))) 
        $brands = $request->input('brands');
    if($request->input('brands')) { 
        $brand_data=Productbrand::where(DB::raw('lower(name)'),strtolower($request->input('brands')))->pluck('id')->first(); 
    if(!empty($brand_data))
        $brand_id=$brand_data;
    else{
        $newbrand=array('name'=>$request->input('brands'));
        $brand_data=Productbrand::create($newbrand);
        $brand_id=$brand_data->id;
    }
    }
	
	
	    $input['brands'] = $brand_id;
	     $input['product_description'] =nl2br($request->input('product_description'));
	   // dd($input);
        $product->update($input);
        $msg='Product '.$request->input('name').' has been updated!'; 
        return json_encode($msg);
    }
    //load admin product listing page
    public function viewProduct($productId){
        //fetch product etails
        $product = Product::find($productId); 
        $categories = Category::where('parent_id', null)->orderBy('name','ASC')->get();
        $country_ids = explode(',',$product->available_countries);
        $countries = Country::whereIn('id',$country_ids)->pluck('name');
        //if product not found redirect to listing page
          if(empty($product)) {
                return redirect()->route('product.list')->with('message','No Product Found');
            } 
          return view('admin/products/view-product',compact('product','countries','categories'));  
        }
    //load seller product listing page
    public function viewSellerProduct($productId){
        $product = SellerProduct::find($productId); 
        $categories = Category::where('parent_id', null)->orderBy('name','ASC')->get();
        $country_ids = explode(',',$product->available_countries);
        $countries = Country::whereIn('id',$country_ids)->pluck('name'); 
        if(empty($product)) {
                return redirect()->route('product.list')->with('message','No Product Found');
        } 
        $seller_id = $product->user_id;
        $seller = User::find($seller_id);
        if($seller->seller_type!="Master")
            $user_1 = User::find($seller->parent_id); 
        else
            $user_1 = User::find($seller->id); 
        $productReviews = ProductReview::where('product_id','=',$productId)->latest()->paginate(5);
        return view('admin/products/view-SellerProduct',compact('product','countries','categories','seller','productReviews','productId','user_1'));  
    }
	//delete admin product image
	 public function deleteimage(Request $request){ 
        $id=$request->get('id');
        $images=ProductImage::findOrFail($id);
        if (File::exists("/uploads/productImages/".$images->image_path)) {
           File::delete("/uploads/productImages/".$images->image_path);
       }
       
       $output=ProductImage::find($id)->delete();
       $ProductImage =ProductImage::where('product_id',$images->product_id)->first();
        if($ProductImage) 
            $ProductImage->update(['thumbnail'=>'yes']);
       return json_encode($output);
   }
    //delete admin product
   public function deleteProduct($productId)
    {
        $product=  Product::find($productId);
        if(empty($product)){ 
            return redirect()->route('list-products')->with('message', 'Product not Exists');
        }

        $productImages =  ProductImage::where('product_id','=',$product->id)->get();      
        if(!empty($productImages)){
            foreach($productImages as $productImage)
            {
                $path = public_path()."/uploads/productImages/".$productImage->image_path;             
                @unlink($path );               
            }
        
        }
        
        $product->delete();
        return redirect()->route('list-products')->with('message', 'Product Deleted!');
    }
    //update product visibility
    public function updateselerproductvisibility(Request $request)
    { 
        $id=$request->id;
        $status=$request->status;
        $Product = SellerProduct::find($id);
        $status=$Product->update(['product_visibility'=>$status]);
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
        
        echo json_encode($status);  
    }

//delete seller products
    public function deleteSellerProduct($productId)
    {
        $product=  SellerProduct::find($productId);
        $product->update(['status'=>'deleted']);
        //category checking products
        if($product->category_id!=''){
            $parents = collect([]);
            $parent = Category::where('id', $product->category_id)->first();
            //array push
            while(!is_null($parent)) {
                $parents->push($parent);
                $parent = $parent->parent;
            }
            $old_category=$parents->pluck('id')->all();
            $commaarray=implode(",",$old_category);
            //check and fix value next level category products exist
            $result= $this->PublicMiddlewareController->getnextlevelproductexist($product->category_id,$commaarray);
             if($result==false)
             {
                $parent = Category::where('id', $product->category_id)->first();
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
        if(empty($product)){ 
            return redirect()->route('list-products')->with('message', 'Product not Exists');
        }
        return redirect()->route('list-Sellerproducts')->with('message', 'Product Deleted!');
    }
    
    public function listProductRequest(Request $request) {

        $category_id=$request->get('category_id');
        $search_key=$request->get('search_key');
        $categories = Category::where('parent_id', null)->where('name','<>','')->orderBy('name','ASC')->get();
        $countries = Country::all();
        $productRequests = ProductRequest::select("product_requests.*")
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
        $prd_req_cat_ids = Category::whereIn("id", $prd_req_cat_ids1)->orderBy('name','ASC')->get(); 
        return view('admin.products.listProductRequests',compact('prd_req_cat_ids','categories','category_id','search_key',"countries"));
    }
    
    public function getProductRequest(Request $request)
    {  
        
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $searchValue = $search_arr = $request->get('search_key')??"";
        $category_id=[]; 
        $categoryid=$request->get('category_id'); 
        if($categoryid !=null){

                    $categorylist = Category::where("parent_id", $categoryid)
                            ->pluck("id")->all(); // all subcategories
                    array_push($category_id,$categoryid); //parent category to array}

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
		
        $draw = $request->get('draw'); 
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        //$searchValue = $search_arr['value']; // Search value
        //total ProductRequest count
        $totalRecords = ProductRequest::count();
        //total filtered data count
        $totalRecordswithFilter = ProductRequest::leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'product_requests.user_id')
        ->leftJoin("users","users.id", "=", "product_requests.parent_id")
            ->select('count(*) as allcount',"buyer_companies.company_name as cmp_name")
            ->when($searchValue != "", function ($query) use ($searchValue) {
              
                $query ->where('product_description', 'like', '%' . $searchValue . '%');
               
            })
            ->where(function ($query) use($categoryid,$category_id){

                if($categoryid !=null){

                 foreach ($category_id as $id) 
                     $query->orwhereRaw('find_in_set("'.$id.'",category_id)');
                }
                        
            });
            if ($request->input("country_id") != "") {
                $country_id = explode(",", $request->input("country_id"));
                $totalRecordswithFilter = $totalRecordswithFilter->where(function ($query) use (
                    $country_id
                ) {
                    foreach ($country_id as $c_id) {
                        $query->WhereRaw( 'find_in_set("' . $c_id . '",product_requests.country_id)');
                    }
                });
            }
            if ($request->input("company_name") != "") {
                $company_name = $request->input("company_name");
                $totalRecordswithFilter = $totalRecordswithFilter->where(function ($query) use ($company_name) 
                {
                    $query ->where('buyer_companies.company_name', 'like', '%' . $company_name . '%');
                });
            }
        $totalRecordswithFilter = $totalRecordswithFilter->count();

        // Get records, also we have included search filter as well
        $records = ProductRequest::
            leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'product_requests.user_id')
            ->leftJoin("users","users.id", "=", "product_requests.parent_id")
            ->select('product_requests.*');
            if(!empty($columnSortOrder))
            $records = $records->orderBy($columnName, $columnSortOrder);
            else
            $records = $records->orderBy('product_requests.id','Desc');
            $records = $records->when($searchValue != '', function ($query) use ($searchValue)  {
                   
                         $query ->where('product_description', 'like', '%' . $searchValue . '%');
                   
            })
            ->where(function ($query) use($categoryid,$category_id){

                if($categoryid !=null){

                 foreach ($category_id as $id) 
                     $query->orwhereRaw('find_in_set("'.$id.'",category_id)');
                }
                        
            });
           if ($request->input("country_id") != "") {
                $country_id = explode(",", $request->input("country_id"));
                
                $records = $records->where(function ($query) use (
                    $country_id
                ) {
                    foreach ($country_id as $c_id) {
                        //$query->WhereRaw( 'find_in_set("' . $c_id . '",product_requests.country_id)');
                        $query->orWhereRaw( 'find_in_set("' . $c_id . '",users.country_id)');
                    }
                });
            }
            if ($request->input("company_name") != "") {
                $company_name = $request->input("company_name");
                $records = $records->where(function ($query) use ($company_name) 
                {
                    $query ->where('buyer_companies.company_name', 'like', '%' . $company_name . '%');
                });
            }
            
            
        $records = $records->skip($start)
        ->take($rowperpage)
        
        ->get();
        $data_arr = array();
        foreach ($records as $record) {
            
                $user=User::find($record->parent_id);
                
                $prd_img = URL::asset("/images/no-image.jpg");
                if(!empty($record->prod_img))
                $prd_img = URL::asset("/uploads/productRequests/".$record->prod_img);
                $prd_img ='<a href="'.$prd_img.'"  target="_blank"><img src="'.$prd_img.'" style="width:100px;"></a>';
                
                
                $country_ids =[]; $countries='';
		        if (!empty($record->country_id)) {
                    $country_ids = explode(",", $record->country_id);
                    $countries = Country::whereIn("id", $country_ids)->pluck("name")->all();
                }
                
                $category_name = $record->Category->name;
                $expiry_status="";
                $end_date =  '<span style="color:green;">'.$record->req_end_date.'</span>';
                $date = Carbon::parse($record->req_end_date);
                if ($date->isPast())
                    {    $expiry_status = "Expired";
                        $end_date= '<span style="color:#ff0000;">'.$record->req_end_date.'<br>Expired</span>'; }
                else
                {
                        $days_remaining = $diff = now()->diffInDays($record->req_end_date);
                        $end_date= '<span style="color:green;">'.$record->req_end_date.'<br>'.$days_remaining.'Days Remaining</span>';
                }
                
                
                $data_arr[] = array(
                "id"=> $record->id,
                "product_description" => $record->product_description,
				"category_name" => $category_name,
				"country_name" => $countries,
				'req_by' => $user->BuyerCompany->company_name,
				"quantity" => $record->quantity??'',
				"language" => $record->language??'',
				"prod_img" => $prd_img,
				"req_date" => $record->req_date,
				'expiry_status' =>$expiry_status,
				"req_end_date" => $end_date
                
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
public function ExtendRequest($req_id)
    { 
        $prodreq = ProductRequest::find($req_id);
        if (empty($prodreq)) {
            return redirect()
                ->route("list.ProductRequest")
                ->with("message", "Product Request not Exists");
        }
        
        $extend_by_date = Carbon::parse($prodreq->extend_by_date);
        if ($extend_by_date->isToday()){
            return redirect()->route("list.ProductRequest") ->with("req_failed", "Unable to extend now!");
        } else {
        $date =  Carbon::parse($prodreq->req_end_date) ;
        $prodreq->req_end_date = $date->addDays(10); 
        $prodreq->extend_by_date = Carbon::now();
        $prodreq->save();
        return redirect()->route("list.ProductRequest")->with("req_message", "Product Request Extended!");
        }
    }
    public function deleteProductRequest($req_id)
    {
        $prodreq = ProductRequest::find($req_id);
        if (empty($prodreq)) {
            return redirect()
                ->route("list.ProductRequest")
                ->with("message", "Product Request not Exists");
        }
        $prodreq->delete();
        return redirect()
            ->route("list.ProductRequest")
            ->with("req_message", "Product Request Deleted!");
    }
    public function viewProductRequest($req_id)
    { 
       
        $Req = ProductRequest::find($req_id);
        $user= User::find($Req->user_id);
        $cmp_name = $user->BuyerCompany->company_name??'';
        $country_ids =[]; $countries='';
		        if (!empty($Req->country_id)) {
                    $country_ids = explode(",", $Req->country_id);
                    $countries = Country::whereIn("id", $country_ids)->pluck("name")->all();
                    $countries = implode( ',', $countries );
                } 
               // dd($countries);
        $prd_img = URL::asset("/images/no-image.jpg");
                if(!empty($Req->prod_img))
                $prd_img = URL::asset("/uploads/productRequests/".$Req->prod_img);
                $prd_img ='<a href="'.$prd_img.'"  target="_blank"><img src="'.$prd_img.'" style="width:100px;"></a>';
        //$cat_selected = Category::where("id", $Req->category_id)->first();
        return view( "admin.products.viewProductRequest",compact("Req",'prd_img','countries','cmp_name'));
       
      
       
       
    }
    
}
