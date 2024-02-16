<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Admin;
use DB;
use File;
use App\Models\SellerProduct;
use App\Models\SellerProductTemp;
use App\Models\SellerProductImage;
use App\Models\SellerProductImageTemp;
use App\Models\Category;
use App\Models\Country;
use App\Models\SellerOfflineCategory;
use App\Models\Productbrand;
use App\Models\Currency;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
use App\Http\Controllers\FrontEnd\PublicMiddlewareController;

class VendorProductController extends Controller
{
    protected $PublicMiddlewareController;
    public function __construct(PublicMiddlewareController $PublicMiddlewareController) 
    {
        $this->PublicMiddlewareController = $PublicMiddlewareController;
    }
    //display content page
    public function listVendorProduct(Request $request) {
        
        $category_id=$request->get('category_id');
        //list category drop down data
        $categories = Category::where('parent_id', null)->where('name','<>','')->orderBy('name','ASC')->get();
        //list active users
        $User = User::leftJoin('subscriptions', 'subscriptions.user_id', '=', 'users.id')
                      ->leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
                       ->where('users.status','<>','Deleted')
                       ->where('subscriptions.status','Active')
                       ->where('users.usertype','seller')
                       ->where('subscriptions.expairy_date', '>', NOW())
                       ->select('users.id as id','users.name as name', 'buyer_companies.company_name as company_name')
                       ->orderBy('users.name','ASC')
                       ->groupBy('users.id')
                       ->get();
        //country list
        $Country = Country::get();
        return view('admin.vendor_product.vendor_product_list',compact('categories','User','Country','category_id'));
    }

   //ajax request return temporay table list 
   
   
    public function getvendorproductlist(Request $request)
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
        // $searchValue = $search_arr['value']; // Search value
        $searchValue=$request->get('search_key');
        $user_id=$request->get('user_id');
        $category_id=$request->get('category_id');
        //total temorary products count
        $totalRecords =SellerProductTemp::leftJoin('users', 'users.id', '=', 'seller_product_temps.user_id')
            ->select('count(*) as allcount')->where('users.status','<>','Deleted')
            ->where('seller_product_temps.status','!=','deleted')->count();
        //filtered product count
        $totalRecordswithFilter = SellerProductTemp::leftJoin('users', 'users.id' ,"=" ,'seller_product_temps.user_id')
            ->leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
            ->select('count(*) as allcount')    
            ->where('seller_product_temps.status','!=','deleted')
            ->where('users.status','<>','Deleted')
            //product name,company name wise search
            ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where(
                    DB::raw(
                        'UPPER(CONCAT(seller_product_temps.name,COALESCE(buyer_companies.company_name,"")))'
                    ), "LIKE", "%" . strtoupper($searchValue) . "%"
                );
            })
            //category wise filtering
            ->when($category_id!='', function ($query) use ($category_id) {
                $query->where(
                    DB::raw(
                        'UPPER(COALESCE(seller_product_temps.categories,""))'
                    ), "LIKE", "%" . strtoupper($category_id) . "%"
                );
            })
            ->when($user_id!='', function ($query) use ($user_id) {
                $query->where('users.id',$user_id);
            })
            ->count();
        $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];        

        // Get records, also we have included search filter as well
        $records = SellerProductTemp::leftJoin('users', 'users.id' ,"=" ,'seller_product_temps.user_id')
            ->leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
            ->select('seller_product_temps.*','users.name as sellername','users.store_name','buyer_companies.company_name')
            ->orderBy($columnName,$columnSortOrder)
            ->where('seller_product_temps.status','!=','deleted')
            ->where('users.status','<>','Deleted')
            ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where(
                    DB::raw(
                        'UPPER(CONCAT(seller_product_temps.name,COALESCE(buyer_companies.company_name,"")))'
                    ), "LIKE", "%" . strtoupper($searchValue) . "%"
                );
            }) 
             ->when($category_id!='', function ($query) use ($category_id) {
                $query->where(
                    DB::raw(
                        'UPPER(COALESCE(seller_product_temps.categories,""))'
                    ), "LIKE", "%" . strtoupper($category_id) . "%"
                );
            })
            ->when($user_id!='', function ($query) use ($user_id) {
                $query->where('users.id',$user_id);
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
       

        foreach ($records as $record) {
            
            $dummy_image=asset("/uploads/defaultImages/no_image.jpg");
            
            $strimg= '<div class="table-prof"><img  class="pr_img" src="'.$dummy_image.'"></div>';
            foreach( $record->SellerProductImageTemp as $productimage)
            {  
                //checking thumbnail image exist
                if($productimage->thumbnail == 'yes') 
                {  
                    if (filter_var($productimage->image_path, FILTER_VALIDATE_URL) !== false) { 
                          $strimg= '<div class="table-prof"><img  class="pr_img" src="'.$productimage->image_path.'"/></div>';
                      } 
                      elseif(filter_var($productimage->image_path, FILTER_VALIDATE_URL) === FALSE)
                      {
                      $validB64 = preg_match("/data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+).base64,.*/", $productimage->image_path);
                         if($validB64){ 
                             $strimg= '<div class="table-prof"><img  class="pr_img" src="'.$productimage->image_path.'"/></div>';
                         }
                         else{ 
                         $strimg= '<div class="table-prof"><img  class="pr_img" src="'.asset("/uploads/productImages/").'/'.$productimage->image_path.'"/></div>';
                          }
                      
                      }          
                
            }
            }

            //return status of image
            $status = (
                ($record->status=='pending' || $record->status=='') ? '<span style="color:white;background-color:red;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">Pending</span>':
                (($record->status=='active') ? '<span style="color:white;background-color:green;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.ucfirst($record->status).'</span>' :
                (($record->status=='rejected') ?'<span style="color:white;background-color:orange;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.ucfirst($record->status).'</span>' :
                '')));      

           //respose array
           
            $data_arr[] = array(
                "id" => $record->id,
                "profile_pic" => $strimg,
				"categories" => $record->categories,
                "name" => $record->name. '<br>'.$status,
                "store_name" => $record->company_name,
                "sellername" => $record->sellername
                
                
               );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);       
}


   
   
   
   
   
   
 //display product details page  
   
 public function viewProduct($productId){
        $product = SellerProductTemp::find($productId); 
        $company_info = User::find($product->user_id);
        $currency_info = "";
	$currencies = Currency::all(); //currency list
    $countries = Country::all(); //country list
        if(!empty($product->currency_id))
        $currency_info = Currency::find($product->currency_id);
        if($company_info->seller_type!="Master")
            $company_info = User::find($company_info->parent_id); 
        if(empty($product)) 
            return redirect()->route('admin.listVendorProduct')->with('message','No Product Found');
            
        return view('admin/vendor_product/view-vendor-product',compact('product','company_info','currency_info','countries'));  
} 
//deleting temporary product image	 
public function deleteimage($id){ 

        $images=SellerProductImageTemp::findOrFail($id);
        if (File::exists("/uploads/productImages/".$images->image_path)) {
           File::delete("/uploads/productImages/".$images->image_path);
       }

     //  SellerProductImageTemp::find($id)->delete();
       return back();
}
//display edit tempoary product page
public function editProduct($productId) {
        
    $product = SellerProductTemp::find($productId);
    // $varients = SellerProduct::all();
	$currencies = Currency::all(); 
    $countries = Country::all(); 
    $product_images = SellerProductImageTemp::where('product_id','=',$productId)->get();
    $categories = Category::where('parent_id', null)->orderBy('name','ASC')->get();
    $cat_selected =Category::where('id', $product->category_id)->first();
    $Productbrand = Productbrand::get();

    if(empty($productId )) 
        return redirect() ->route('admin.listVendorProduct')->with('message','Product not exists');

    return view('admin/vendor_product/edit-vendor-product' ,compact('product','categories','cat_selected','countries','product_images','Productbrand','currencies'));
    
}
//create slug name for product
public function create_slug($string)
{
    $items = array("index", "create_slug", "show", "create", "store", "edit", "update", "destroy");
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
    $categories_cnt = Category::where(DB::raw('lower(slug)'),strtolower($slug))->get()->count();
    if (in_array($slug, $items) || $categories_cnt>0) 
        $slug = $slug .'-'. date('ymd').time();
    return $slug;
}
//approve temporay product
public function vendorproductapproval(Request $request)
{
    $seller_products=$request->get('seller_products');
    $status=$request->get('status');

    $cats_path='';
    if($request->get('category_id')!="")//saving parent and subcategory path
    {
        $seller_prd_ids=$request->get('seller_products');
        $cat_new_id=$request->get('category_id');
        $category=Category::find($cat_new_id);
        
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
        //updating category id and category path name      
        SellerProductTemp::whereIn('id',$seller_prd_ids)->update(['category_id'=>$cat_new_id, 'categories'=> $cats_path]);
    }
    
    if($status=="addcategory") 
        return json_encode('Category Added'); //if only category changing
    //updating all product list    
    foreach($seller_products as $item){
	   $SellerProduct=SellerProductTemp::find($item);
	   if(!empty($SellerProduct->categories))
	        $cats_path=$SellerProduct->categories;
	   else
	        $cats_path='';
	if($status=="active") {	//if satus is changing	
		 
    if(empty( $SellerProduct->category_id))
    {
        //catgory fetching
        // if(!empty($SellerProduct->categories))
        //     $NewCategories = explode(',', $SellerProduct->categories); 
        // else
        //     $NewCategories=[];
      $cats=[];
      $NewCategory=$SellerProduct->categories;
   // foreach($NewCategories as $NewCategory)
    //{ 
        $prd_ids = $vartnts= $ctrys=  $cats = array();
         //csv > seperated values to create category
        if (str_contains($NewCategory, '>')) 
        {
                $parents = explode('>', $NewCategory);
                $prevperent=null;
                foreach($parents as $key => $data)
                {
                    $data=trim($data);
                    if($key==0)
                        $parent_id=null;

                    $cats1 = Category::where(DB::raw('lower(name)'),strtolower($data))->where('parent_id',$parent_id)->pluck('id')->first(); 
        
                    if(!empty($cats1))  
                        $prevperent=  $parent_id= $cats1;
                    else
                    {
                        $catg1['name'] = trim($data); 
                        $catg1['parent_id'] = $prevperent; 
                        $seo_url = $this->create_slug($data);
                        $catg1['slug'] = $seo_url;
                        $catCreate = Category::create($catg1); 
                        $prevperent= $parent_id= $catCreate->id;
                    }
                    
                }
                array_push($cats,$prevperent); 
        }
        else
        {
            $cats1 = Category::where(DB::raw('lower(name)'),strtolower(trim($SellerProduct->categories)))->pluck('id')->first();
			

            if(!empty($cats1)) 
                 array_push($cats,$cats1);
            else
            {
                $seo_url = $this->create_slug($NewCategory);
                $catg1['name'] = trim($NewCategory);
                $catg1['parent_id'] = null; 
                $catg1['slug'] = $seo_url;
                $catCreate = Category::create($catg1); 
                array_push($cats,$catCreate->id);
            }
        }
    //}
    $CatList = implode(', ', $cats); 
}  
if(!empty($CatList))
    $categoriesNew= $CatList;
else 
    $categoriesNew= $SellerProduct->category_id;
			 
// Brand Insert section below
$brand_id= "";
if(isset($SellerProduct->brands)) { 
    $brand_data=Productbrand::where(DB::raw('lower(name)'),strtolower($SellerProduct->brands))->pluck('id')->first(); 
    if(!empty($brand_data))
        $brand_id=$brand_data;
    else{
        $newbrand=array('name'=>$SellerProduct->brands);
        $brand_data=Productbrand::create($newbrand);
        $brand_id=$brand_data->id;
    }
}  

     
 
 // Currency Insert section below
 $currency= null;
 if(isset($SellerProduct->currency_id)) {
      $currency= $SellerProduct->currency_id;
 }
 
 
else if(isset($SellerProduct->currency)) { 

    $currency_data=Currency::where(DB::raw('lower(symbol)'),strtolower($SellerProduct->currency))->pluck('id')->first(); 
	
    if(!empty($currency_data)) { 
	$currency=$currency_data; }
	elseif($SellerProduct->currency=="" || ($SellerProduct->currency==null) || is_null($SellerProduct->currency)) 
	{ $currency=null; }
    else{
        $newcurrency=array('symbol'=>$SellerProduct->currency);
        $currency_data=Currency::create($newcurrency);
        $currency=$currency_data->id;
    }
}  

     

// Coutries Insert section below
$NewCountries= "";
if(isset($SellerProduct->country_ids)) { 
	$CountryList = $SellerProduct->country_ids; 
}  


else if(isset($SellerProduct->countries)) { 
    $NewCountries = explode(',', $SellerProduct->countries); 
   
   
foreach($NewCountries as $NewCountry){
    $ctrys1 = Country::where(DB::raw('lower(name)'),strtolower($NewCountry))->pluck('id')->first();

    if($ctrys1)  array_push($ctrys,$ctrys1);
    else{
        $newCty['name'] = $NewCountry; 
        $catCreate = Country::create($newCty); 
        array_push($ctrys,$catCreate->id);
    }
}
$CountryList = implode(',', $ctrys); 
}  

if(!empty($CountryList)) $NewCountries= $CountryList;

$product_expiry =null; 
if(isset($SellerProduct->product_expiry)) {      
    $date = date_parse($SellerProduct->product_expiry);
    if ($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"]))
        $product_expiry = date('Y-m-d', strtotime($SellerProduct->product_expiry));

	}
if(isset($SellerProduct->BBD)) {      
    $date = date_parse($SellerProduct->BBD);
    if ($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"]))
        $BBD = date('Y-m-d', strtotime($SellerProduct->BBD));

}
   
    //moving temp product into seller product table    
        
  
    $ret =  SellerProduct::create([
            'name'     => trim($SellerProduct->name),
			'currency_id'    => $currency, 
            'product_price'    => $SellerProduct->product_price, 
            'SKU'    => $SellerProduct->SKU, 
            'EAN_GTIN'    => $SellerProduct->EAN_GTIN, 
            'batch'    => $SellerProduct->batch, 
            'leadtime' => $SellerProduct->leadtime, 
            'pcs_box' => $SellerProduct->pcs_box,
            'pcs_pallet' => $SellerProduct->pcs_pallet, 
            'box_pallet' => $SellerProduct->box_pallet, 
            'stock_count'    => $SellerProduct->stock_count, 
            'label_language'    => $SellerProduct->label_language, 
            'category_id'   => $categoriesNew, 
            'available_countries'   => $NewCountries, 
            'product_color'    => $SellerProduct->product_color, 
            'product_weight'    => $SellerProduct->product_weight ?? $SellerProduct->product_size ?? '', 
            'product_size'    => $SellerProduct->product_size, 
            'product_dimension'    => $SellerProduct->product_dimension, 
            'minimal_order'    => $SellerProduct->minimal_order, 
            'product_condition'    => $SellerProduct->product_condition, 
            'product_description'    => $SellerProduct->product_description,
            'location'    => $SellerProduct->location,
            'varients_skus'    => $SellerProduct->varients,
            'product_expiry'  => $product_expiry ?? $BBD ?? NULL,
            'brands'    => $brand_id,
            'status'=>'active',
            'user_id'    => $SellerProduct->user_id,
            'feature'    => $SellerProduct->feature, 
            'brix'    => $SellerProduct->brix, 
            'packaging'    => $SellerProduct->packaging, 
            'place_of_origin'    => $SellerProduct->place_of_origin, 
            'model_number'    => $SellerProduct->model_number, 
            'primary_ingredients'    => $SellerProduct->primary_ingredients, 
            'unlimited_stock'    => $SellerProduct->unlimited_stock,
            'price_on_request'    => $SellerProduct->price_on_request,
            'price_negotiable'    => $SellerProduct->price_negotiable,
            'additives'    => $SellerProduct->additives, 
            'flavor'    => $SellerProduct->flavor, 
            'certification'    => $SellerProduct->certification, 
            'volume'    => $SellerProduct->volume, 
            'BBD'    => $SellerProduct->BBD,
            'categories'    => $cats_path 
        ]);
        
        //category checking products        
        if($categoriesNew!=''){
            $parents = collect([]);
            $parent = Category::where('id', $categoriesNew)->first();

            while(!is_null($parent)) {
                $parents->push($parent);
                $parent = $parent->parent;
            }
            $have_product_category=$parents->pluck('id')->all();
            Category::whereIn('id', $have_product_category)->update(['have_product'=>'Yes']);
            
            $seller_OflnCats = SellerOfflineCategory::select('category_id')->where('user_id', $SellerProduct->user_id)->first();
            if ($seller_OflnCats) 
            {
                $seller_offine_categorylists = explode(",", $seller_OflnCats->category_id);
                if (($key = array_search($categoriesNew, $seller_offine_categorylists)) !== false) {
                unset($seller_offine_categorylists[$key]);
                $seller_offine_categorylists= implode(",", $seller_offine_categorylists);
                $data = [
                'category_id'=>$seller_offine_categorylists,
                ]; 
                SellerOfflineCategory::where('user_id', $SellerProduct->user_id)->update($data);
                
        }
            }
        } 
        //downloading live path image to server folder
        $product_images = 	$SellerProduct->SellerProductImageTemp;	
        if($product_images) {
                $ibr=0;
        		foreach ($product_images as $product_image) {
                	$file = "uploads/productImages/";
                	$url=$product_image->image_path;
                	$imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];        
                    $ibr++;
                	if (!empty($url)) 
                    { 
                        if(filter_var($product_image->image_path, FILTER_VALIDATE_URL) === FALSE)
                        {   
                            $validB64 = preg_match("/data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+).base64,.*/", $product_image->image_path); 
                            if($validB64)
                            {//if base64 image convert to image
                                $imageInfo = explode(";base64,", $product_image->image_path);
                                $imgExt = str_replace('data:image/', '', $imageInfo[0]);      
                                $image = str_replace(' ', '+', $imageInfo[1]);
                                $image_name= date("YmdhisU").$ret->id .'.'.$imgExt;

                                $destinationPath = public_path() . '/uploads/productImages/' .$image_name;
                                 \File::put($destinationPath, base64_decode($image));
                                $image_path=$image_name;
                            }
                            else
                                $image_path=$url;
                            
                        }
                        else
                        { 
                            $encode_path=rawurldecode($product_image->image_path);
                            
                            $handle = @fopen($encode_path, 'r');
                            // Check if file exists
                            if($handle) 
                            {
                                 //$ext = pathinfo(parse_url($encode_path)['path'], PATHINFO_EXTENSION);	
                                 //if(in_array($ext, $imageExtensions))
                                    $image_path =  $this->grab_image($encode_path,$file,$ibr);
                                 //else
                                 //$image_path='';
                            }
                            else
                                $image_path='';
                            
                        }
                        if($image_path!='')
                        {
                            $ret1 =  SellerProductImage::create([
                            'product_id'     => $ret->id,
                            'thumbnail'      => $product_image->thumbnail, 
                            'image_path'    => $image_path
                            ]);
                            
                        }
                        
                    }
          }
        }		
        $SellerProduct->delete();//temp product deleting
    }
			
        if($status=="rejected")
        {
            $SellerProduct->status ="rejected";
            $SellerProduct->save();
        }   
    }
    echo json_encode('Status Updated');
}    
  
//updaing temp product
public function updateProduct(Request $request) {
   
       
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
            
       /* if ($request->input("unlimited_stock") != "Unlimited") {
            $data = $request->validate([
                "stock_count" => "required|numeric",
            ]);
        }*/
        
         //data validation adding
        
        $productId = $request->get('id');
        //finding product data
        $product = SellerProductTemp::find($productId);
        $category_id = $variants = $currency_id = "";
            if(!empty($request->input('category_id'))) 
			$category_id = $request->input('category_id');
		
			if(!empty($request->input('subcategory_id'))) 
			$category_id = $request->input('subcategory_id');
		     
			if(!empty($request->input('subsubcategory_id'))) 
			$category_id = $request->input('subsubcategory_id');
			
	    $cats_path='';
        if($category_id!="")
        {
            $category=Category::find($category_id);
            //parent & subcategory fetching
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
        //varients
        if(!empty($request->input('variants'))) {
        $variants = $request->input('variants');
        $variants = implode(",",$variants);
        }
        //currency
        if(!empty($request->input('currency_id'))) {
        $input['currency_id'] =  $request->input('currency_id');
        }
        
        $input = $request->all();
        if(!array_key_exists("unlimited_stock",$input))
            $input['unlimited_stock']='No';
            /*if(!array_key_exists("stock_count",$input) || $input['stock_count']==null)
            $input['stock_count']=0;*/
        $product_price=null;
        if(!empty($request->input('product_price'))) {
        $product_price  = $request->input('product_price');
		$product_price = (double) str_replace(',', '', $product_price)   ;
		$product_price = round($product_price,3);
        } 
		$input['product_price']=$product_price;        
        //product price type
        $input['price_on_request']  = 'No';
        if($request->input('price_on_request')!='')
            $input['price_on_request'] = $request->input('price_on_request');
        $input['price_negotiable']  = null;
        if($request->input('price_negotiable')!='')
            $input['price_negotiable'] = $request->input('price_negotiable');
        $input['category_id']=$category_id;
        $input['categories']= $cats_path;
        $input['variants']=$variants;
        //product image saving
        // if($request->hasFile("product_image")){ 
        //     $file=$request->file("product_image");
        //     $fileName=time().'_'.$file->getClientOriginalName();
        //     $destinationPath = public_path().'/uploads/productImages' ;
        //     $file->move($destinationPath,$fileName);
        //     $img['image_path'] = $fileName;
        //     $img['thumbnail'] = "yes";
        //     $img['product_id'] = $productId ;
        //     // DB::table('seller_product_image_temps')->where([
        //     //     ['product_id', '=', $productId],
        //     //     ['thumbnail', '=', 'yes'],
        //     // ])->delete();
        //     SellerProductImageTemp::create($img);

        // }
        //product gallery image
            if($request->hasFile("product_gallery")){
                $files=$request->file("product_gallery");
                $sellerProductImageTemp =SellerProductImageTemp::where('product_id',$productId)
                    ->where('thumbnail','yes')->first();
                $i=0;
                foreach($files as $file){
                    $imageName=time().'_'.$file->getClientOriginalName();
                    $img['product_id']=$productId;
                    if($i==0 && empty($sellerProductImageTemp))
                    {    
                        $img["thumbnail"] = "yes";
                        $i=1;
                    }
                    else
                        $img["thumbnail"] = "no";
                    $img['image_path']=$imageName;
                    $destinationPath = public_path().'/uploads/productImages' ;
                    $file->move($destinationPath,$imageName);
                    SellerProductImageTemp::create($img);

                }
            }
       $input['product_description'] = $request->input('product_description');
   //    dd($input);
        $product->update($input);
        echo json_encode('Product Updated'); 
    }
    //deleting temporay product
    public function vendorproductdelete(Request $request)
    {
        $seller_products=$request->get('seller_products');
        $action_type=$request->get('action_type');
        if($action_type=='products')
          {  
              $allproducts=SellerProduct::whereIn('id',$seller_products)->get();
              SellerProduct::whereIn('id',$seller_products)->delete();
              foreach ($allproducts as $key => $value) {
                  //category checking products        
                    if($value->category_id!=''){
                        $parents = collect([]);
            $parent = Category::where('id', $value->category_id)->first();
            //array push
            while(!is_null($parent)) {
                $parents->push($parent);
                $parent = $parent->parent;
            }
            $old_category=$parents->pluck('id')->all();
            $commaarray=implode(",",$old_category);
            //check and fix value next level category products exist
            $result= $this->PublicMiddlewareController->getnextlevelproductexist($value->category_id,$commaarray);
             if($result==false)
             {
                $parent = Category::where('id', $value->category_id)->first();
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
              
              
          }
        else
            SellerProductTemp::whereIn('id',$seller_products)->delete();
        echo json_encode('Product Deleted');
    }
//deleting product and image
     public function deleteProduct($productId)
    {
        $product=  SellerProductTemp::find($productId);
        if(empty($product)){ 
            return redirect()->route('admin.listVendorProduct')->with('message', 'Product not Exists');
        }

        $productImages =  SellerProductImageTemp::where('product_id','=',$product->id)->get();      
        if(!empty($productImages)){
            foreach($productImages as $productImage)
            {
                $path = public_path()."/uploads/productImages/".$productImage->image_path;  
                if (file_exists($path))
                @unlink($path );               
            }
        
        }
        
        $product->delete();
        return redirect()->route('admin.listVendorProduct')->with('message', 'Product Deleted!');
    }
    
    //saving live path image own server
    public function grab_image($url,$saveto,$ibr){
		
    // Getting the name
    $name = pathinfo(parse_url($url)['path'], PATHINFO_BASENAME);
                           
    $image_path =$ibr.date("YmdhisU") . "_" . str_replace("'", '',$name);
    $saveto_path = $saveto.$image_path;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
    curl_setopt($ch, CURLOPT_URL, $url);
    
    
    $raw=curl_exec($ch); 
    curl_close ($ch);
    if(file_exists($saveto_path)){
        unlink($saveto_path);
    }
    $fp = fopen($saveto_path,'x');
	
    fwrite($fp, $raw);
    fclose($fp);
	return $image_path;
}
  

}
