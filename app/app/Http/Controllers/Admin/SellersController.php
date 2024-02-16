<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use File;
use Carbon\Carbon;
use App\User;
use App\Models\KycFile;
use App\Models\Product;
use App\Models\SellerProductTemp;
use App\Models\Package;
use App\Models\PackageAccount; 
use App\Models\Category;
use App\Models\SellerProduct;
use App\Models\Country;
use App\Models\SellerProductImage;
use App\Models\Currency;
use App\Models\SellerOfflineCategory;
use App\Models\BuyerCompany;
use App\Models\CompanyType;
use App\Models\SellerOpeningTime;
use App\Models\NewsletterSubscription;
use App\Models\OrderDetail;
use App\Models\Subscription;
use App\Models\Productbrand;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
use App\Exports\SellerExport;
use App\Exports\NewsletterExport;
use App\Exports\PromoNewsletterExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\FrontEnd\PublicMiddlewareController;

class SellersController extends Controller
{
    protected $PublicMiddlewareController;
    public function __construct(PublicMiddlewareController $PublicMiddlewareController) 
    {
         $this->middleware('auth:admin');
        $this->PublicMiddlewareController = $PublicMiddlewareController;
    }
   
    public function index(Request $request) {

        $search_key=$request->get('search_key');
        $status=$request->get('status');
        $filter=$request->get('filter');
        if($filter=='Excel') 
               return Excel::download(new SellerExport($search_key,$status), 'Sellers_List.xls');
        else
                return view('admin.seller.sellers-list');

    }
    public function create() {
        $countries =Country::select('id','name')->get();
		$currencies =Currency::select('id','shortcode','symbol')->get();
        $company_types =CompanyType::select('id','company_type')->get();
        $categories = Category::where('parent_id', null)->orderby('name', 'asc')->get(); //dd($categories);
        $seller_OflnCats = [];
        return view('admin.seller.seller-profile',compact('countries',"categories","seller_OflnCats",'currencies','company_types'));
    }
    public function sellerproductcreate($seller_id) {
        $categories = Category::where('parent_id', null)->orderBy('name','asc')->get();
		$currencies =Currency::select('id','shortcode','symbol')->get();
        $Productbrand = Productbrand::get();
        return view('admin.seller.add_seller_product',compact('categories','Productbrand','seller_id','currencies'));
    }
    public function savesellerProduct(Request $request){
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
        $input['available_countries'] = $available_countries;
        $input['category_id']=$category_id;
        $input['variants']=$variants;
        $input['user_id']=$request->input('seller_id');
        $input['status']='active';
        
        	
        	
        			
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
        $id = SellerProduct::create($input)->id;
        //category checking products
        if($category_id!=''){
            $parents = collect([]);
            $parent = Category::where('id', $category_id)->first();

            while(!is_null($parent)) {
                $parents->push($parent);
                $parent = $parent->parent;
            }
            $have_product_category=$parents->pluck('id')->all();
            Category::whereIn('id', $have_product_category)->update(['have_product'=>'Yes']);
            
            	
        	
        $seller_OflnCats = SellerOfflineCategory::select('category_id')->where('user_id', $request->input('seller_id'))->first();
            if ($seller_OflnCats) 
            {
                $seller_offine_categorylists = explode(",", $seller_OflnCats->category_id);
                if (($key = array_search($category_id, $seller_offine_categorylists)) !== false) {
                unset($seller_offine_categorylists[$key]);
                $seller_offine_categorylists= implode(",", $seller_offine_categorylists);
                $data = [
                'category_id'=>$seller_offine_categorylists,
                ]; 
                SellerOfflineCategory::where('user_id', $request->input('seller_id'))->update($data);
                
        }
            }	
            
            
            
        }

        // if($request->hasFile("product_image")){
        //     $validator = $request->validate([
        //     'product_image' => 'required|image|mimes:jpeg,png,bmp,gif,svg',
        //     ]);
        //     $file=$request->file("product_image");
        //     $fileName=time().'_'.$file->getClientOriginalName();
        //     $destinationPath = public_path().'/uploads/productImages' ;
        //     $file->move($destinationPath,$fileName);
        //     $img['image_path'] = $fileName;
        //     $img['thumbnail'] = "yes";
        //     $img['product_id'] = $id ;
        //     SellerProductImage::create($img);
        // }
            if($request->hasFile("product_gallery")){
                $files=$request->file("product_gallery");
                $i=0;
                foreach($files as $file){
                    if($i==0)
                    {    
                        $img["thumbnail"] = "yes";
                        $i=1;
                    }
                    else
                        $img["thumbnail"] = "no";
                    $imageName=time().'_'.$file->getClientOriginalName();
                    $img['product_id']=$id;
                    $img['image_path']=$imageName;
                    $destinationPath = public_path().'/uploads/productImages' ;
                    $file->move($destinationPath,$imageName);
                    SellerProductImage::create($img);

                }
            }
       
        echo json_encode("Seller Product created successfully");
    }

    public function store(Request $request) {
        $rules = [
             'name'      => 'required',
             'surname'      =>'required',
             'email'      =>['required','email','regex:/(.+)@(.+)\.(.+)/i', Rule::unique('users')->where(function ($query)  {return $query->where('status','<>','Deleted');})], 
             'company_name'      => 'required',
             'company_zip'      =>'required',
             'company_location'      =>'required',
             'company_street'      =>'required',
             'company_type'      =>'required',
             'country_id'      => 'required',
             'offline_categories'      => 'required',
             
             'accepted_payments'      => 'required ',
             // 'deliver_options'      => 'required ',
             // 'languages_speak'      => 'required ',
             'default_password'      => 'required|min:8 ',
             'phone'      =>['required','regex:/^(^([+]+)(\d+)?$)$/', Rule::unique('users')->where(function ($query)  {return $query->where('status','<>','Deleted');})],
             'about_company' => 'required',
            'position' => 'required',
            'company_image' => 'required', 
            
        ];
        $request->validate($rules);

        $languages_speak = $deliver_options = $accepted_payments = "";
        if(!empty($request->get('languages_speak'))) {
            $languages_speak  = $request->get('languages_speak');
           }
        
        if(!empty($request->get('deliver_options'))) {
            $deliver_options  = $request->get('deliver_options');
            }
        if(!empty($request->get('accepted_payments'))) {
            $accepted_payments  = $request->input('accepted_payments');
          }
       
        $c_types = '';
        if(!empty($request->get("company_type"))){
        foreach($request->get("company_type") as $company_type) {
            $c_types = $c_types .$company_type . ","; }
        }
        $input = [
            'name' => $request->get('name'),
            'surname' => $request->get('surname'),
            'email' => $request->get('email'),
            'company_name' => $request->get('company_name'),
            'company_street' => $request->get('company_street'),
            'company_zip' => $request->get('company_zip'),
             'company_type' => $c_types,
             'company_location' => $request->get('company_location'),
            'registration_no' => $request->get('registration_number'),
            'about_company' => $request->get('about_company'),
            'deliver_options' => $deliver_options,
            'accepted_payments' => $accepted_payments,
            'languages_speak' => $languages_speak,
            'gst_no' => $request->get('gst_no'),
            'position' => $request->get('position'),
            'email_status' => 'Yes',
            
            'country_id' => $request->get('country_id'),
            'default_password' => $request->get('default_password'),
            ]; 

        $input['phone'] =  $request->get('phone');
        $input['usertype'] = 'seller';
        $input['company'] = 'Yes';
        $input['password'] =Hash::make($request->get('default_password'));
        $input['gst_no'] =  $request->get('gst_no');
        $input['varification_status'] =  'varified';
        $input['email_status'] =  'yes';
        if (request()->hasFile("profile_pic")) {
            $extension = request("profile_pic")->extension();
            $fileName = "user_pic" . time() . "." . $extension;
            $destinationPath = public_path() . "/uploads/userImages";
            request("profile_pic")->move($destinationPath, $fileName);
            $input["profile_pic"] = $fileName;
        }
         
        $user=User::create($input);
        $user->varification_status='varified';
        $user->email_status='yes';
        $update=$user->save(); 
        $userId = $input['user_id'] =  $user->id;
        if(request()->hasFile('company_image')) {
            $extension = request('company_image')->extension();
            $fileName = "company_pic".time().'.'.$extension;
            $destinationPath = public_path().'/uploads/BuyerCompany' ;
            request('company_image')->move($destinationPath,$fileName);
            $input['company_image'] = $fileName;
        } 
        
        $sellercmpy=BuyerCompany::create($input);
        
        
        $offline_categories = $request->get("offline_categories");
        $sOflinCats = SellerOfflineCategory::where("user_id", $userId)->first();
        $of_cats = '';
        if(!empty($offline_categories)){
        foreach($offline_categories as $offline_category) {
            $of_cats = $of_cats .$offline_category . ","; }
        }
        if (empty($sOflinCats)) {
         SellerOfflineCategory::create([
                "user_id" => $userId,
                "category_id" => $of_cats,
            ]);
       } else {
           $sOflinCats->update(["category_id" => $of_cats]);
        }

        $input['seller_id'] =  $user->id;

      
        $checkSun =  $request->get('chSunday'); 
        $input['day'] =  "Sunday";
        $input['opening_time'] = $request->get('opSundayhr');
        $input['closing_time'] = $request->get('clSundayhr');
        $input['Open_am_pm'] = $request->get('SunOpen_am_pm');
        $input['Close_am_pm'] = $request->get('WedClose_am_pm');
        
        if ($checkSun=="Yes")
        {    
            $input['closed'] = 'Yes';
            $input['opening_time'] ='';
            $input['closing_time'] = '';
            $input['Open_am_pm'] ='';
            $input['Close_am_pm'] = '';
        }
        else 
            $input['closed'] = 'No';
        $SellerOpeningTime=SellerOpeningTime::create($input);
       
        $chMonday =  $request->get('chMonday');
        $input['day'] =  "Monday";
        $input['opening_time'] = $request->get('opMondayhr');
        $input['closing_time'] = $request->get('clMondayhr');
        $input['Open_am_pm'] = $request->get('MonOpen_am_pm');
        $input['Close_am_pm'] = $request->get('MonClose_am_pm');
        if ($chMonday=="Yes")
        {    
            $input['closed'] = 'Yes';
            $input['opening_time'] ='';
            $input['closing_time'] ='';
            $input['Open_am_pm'] = '';
            $input['Close_am_pm'] = '';
        }
        else 
            $input['closed'] = 'No'; 

         $SellerOpeningTime=SellerOpeningTime::create($input); 

        $chTuesday =  $request->get('chTuesday');
        $input['day'] =  "Tuesday";
        $input['opening_time'] = $request->get('opTuesdayhr');
        $input['closing_time'] = $request->get('clTuesdayhr');
        $input['Open_am_pm'] = $request->get('TueOpen_am_pm');
        $input['Close_am_pm'] = $request->get('TueClose_am_pm');
        if ($chTuesday=="Yes")
        {    
            $input['closed'] = 'Yes';
            $input['opening_time'] ='';
            $input['closing_time'] ='';
            $input['Open_am_pm'] = '';
            $input['Open_am_pm'] = '';
        }
        else 
            $input['closed'] = 'No';
        $SellerOpeningTime=SellerOpeningTime::create($input);   
 
            $chWednesday =  $request->get('chWednesday');
            $input['day'] =  "Wednesday";
            $input['opening_time'] = $request->get('opWednesdayhr');
            $input['closing_time'] = $request->get('clWednesdayhr');
            $input['Open_am_pm'] = $request->get('WedOpen_am_pm');
            $input['Close_am_pm'] = $request->get('WedClose_am_pm');
            if ($chWednesday=="Yes")
            {    
                $input['closed'] = 'Yes';
                $input['opening_time'] ='';
                $input['closing_time'] ='';
                $input['Open_am_pm'] = '';
                $input['Close_am_pm'] = '';
            }
            else 
                $input['closed'] = 'No';

           $SellerOpeningTime=SellerOpeningTime::create($input);   
            $chThursday =  $request->get('chThursday'); 
            $input['day'] =  "Thursday";
            $input['opening_time'] = $request->get('opThursdayhr');
            $input['closing_time'] = $request->get('clThursdayhr');
            $input['Open_am_pm'] = $request->get('ThuOpen_am_pm');
            $input['Close_am_pm'] = $request->get('ThuClose_am_pm');
            if ($chThursday=="Yes")
            {    
                $input['closed'] = 'Yes';
                $input['opening_time'] ='';
                $input['closing_time'] ='';
                $input['Open_am_pm'] = '';
                $input['Close_am_pm'] = '';

            }
            else 
                $input['closed'] = 'No'; 
                
           $SellerOpeningTime=SellerOpeningTime::create($input); 

            $chFriday =  $request->get('chFriday'); 
            $input['day'] =  "Friday";
            $input['opening_time'] = $request->get('opFridayhr');
            $input['closing_time'] = $request->get('clFridayhr');
            $input['Open_am_pm'] = $request->get('FriOpen_am_pm');
            $input['Close_am_pm'] = $request->get('FriClose_am_pm');
            if ($chFriday=="Yes")
            {    
                $input['closed'] = 'Yes';
                $input['opening_time'] ='';
                $input['closing_time'] ='';
                $input['Open_am_pm'] = '';
                $input['Close_am_pm'] = '';
            }
            else 
                $input['closed'] = 'No';   

             $SellerOpeningTime=SellerOpeningTime::create($input); 

                $chSaturday =  $request->get('chSaturday'); 
                $input['day'] =  "Saturday";
                $input['opening_time'] = $request->get('opSaturdayhr');
                $input['closing_time'] = $request->get('clSaturdayhr');
                $input['Open_am_pm'] = $request->get('SatOpen_am_pm');
                $input['Close_am_pm'] = $request->get('SatClose_am_pm');
                if ($chSaturday=="Yes")
                {    
                    $input['closed'] = 'Yes';
                    $input['opening_time'] ='';
                    $input['closing_time'] ='';
                    $input['Open_am_pm'] = '';
                    $input['Close_am_pm'] = '';
                }
                else 
                    $input['closed'] = 'No';           
        
                $SellerOpeningTime=SellerOpeningTime::create($input);  
    
                


                Mail::send('admin/seller/email-template', ['name' => $input['name'], 'email' => 
                $input['email'], 'password' => $input['default_password']] ,function ($message) use ($input) {
                     $message->from("example@gmail.com",'Fmcg');
                     $message->to($input['email'],$input['name'])
                     ->subject('Fmcg Login Credential');
                 });


                 echo json_encode("Seller Added successfully!");
       
        
    }

    public function deleteuser($userId)
    {
        $user =  User::find($userId);
        if (File::exists("/uploads/userImages/".$user->profile_pic)) 
           File::delete("/uploads/userImages/".$user->profile_pic);
           
        $user->status ='Deleted';
        $update=$user->save();   
        DB::table('users')->where('parent_id',$userId)->update(['status'=>'Deleted']);
        DB::table('seller_products')->where('user_id',$userId)->update(['status'=>'deleted']);
        $seller_active_products=SellerProduct::where('user_id',$userId)->get();
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

        echo json_encode($update); 
    }
    public function update(Request $request) {
        $userId=$request->get('user_id');
        $user=User::find($userId);
        $profile_pic = $user->profile_pic??'';
        $company_image = $user->BuyerCompany->company_image;
        $rules=[
             'name'      => 'required',
             'surname'      =>'required',
             'company_name'=>'required',
             'company_zip'      =>'required',
             'company_location'      =>'required',
             'company_street'      =>'required',
             'offline_categories'      => 'required',
             'company_type'      =>'required',
             'email'      =>['required','email','regex:/(.+)@(.+)\.(.+)/i',Rule::unique('users')->ignore($userId)->where(function ($query)  {return $query->where('status','<>','Deleted');})], 
             'phone'      => ['required','regex:/^(^([+]+)(\d+)?$)$/', Rule::unique('users')->ignore($userId)->where(function ($query)  {return $query->where('status','<>','Deleted');})],
             'country_id'      => 'required',
             'accepted_payments'      => 'required',
             'about_company' => 'required',
            'position' => 'required',
          ];
          if($company_image=='')
             $rules['company_image'] = "required";
             
             if($profile_pic=='')
             $rules['profile_pic'] = "required";
        $request->validate($rules);
    $input = [
        'name' => $request->get('name'),
        'surname' => $request->get('surname'),
        'email' => $request->get('email'),
        'phone' => $request->get('phone'),
        'address' => $request->get('address'),
        'about' => $request->get('about'),
        'country_id' => $request->get('country_id'),
        'position' => $request->get('position'),
    ]; 
     if (request()->hasFile("profile_pic")) {
            $extension = request("profile_pic")->extension();
            $fileName = "user_pic" . time() . "." . $extension;
            $destinationPath = public_path() . "/uploads/userImages";
            request("profile_pic")->move($destinationPath, $fileName);
            $input["profile_pic"] = $fileName;
        }
    DB::table('users')->where('id', $userId)->update($input);

    $languages_speak = $deliver_options = $accepted_payments = "";
    if(!empty($request->get('languages_speak'))) {
        $languages_speak  = $request->get('languages_speak');
      }
    if(!empty($request->get('deliver_options'))) {
        $deliver_options  = $request->get('deliver_options');
      }
    if(!empty($request->get('accepted_payments'))) {
        $accepted_payments  = $request->input('accepted_payments');
    }
 $c_types = '';
        if(!empty($request->get("company_type"))){
        foreach($request->get("company_type") as $company_type) {
            $c_types = $c_types .$company_type . ","; }
        } 
    $input = [
        'company_name' => $request->get('company_name'),
        'company_street' => $request->get('company_street'),
        'company_type' => $c_types,
        'company_zip' => $request->get('company_zip'),
        'company_location' => $request->get('company_location'),
        'company_land' => $request->get('company_land'),
        'opening_hours' => $request->get('opening_hours'),
        'about_company' => $request->get('about_company'),
        'accepted_payments' => $accepted_payments,   
        'user_id' => $userId,
        'languages_speak' => $languages_speak,
        'registration_no' => $request->get('registration_number'),
        'deliver_options' => $deliver_options,
        'gst_no' => $request->get('gst_no'),
    ];

    if($request->hasFile('company_image') ){ 
        $validator = $request->validate([
            'company_image' => 'required|image|mimes:jpeg,png,bmp,gif,svg',
        ]);
        $extension = request('company_image')->extension(); 
        $fileName = "company_pic".time().'.'.$extension;
        $destinationPath = public_path().'/uploads/BuyerCompany' ;
        request('company_image')->move($destinationPath,$fileName);
        $input['company_image'] = $fileName;  
     } 
     //  Company already exists or not
     $user =  BuyerCompany::where('user_id','=',$userId)->first();
    
     if(!empty($user)){
        DB::table('buyer_companies')
        ->where('user_id',$userId)
        ->update($input);
        
     } 
    else  
        BuyerCompany::create( $input );
        $offline_categories = $request->get("offline_categories");
        $sOflinCats = SellerOfflineCategory::where("user_id", $userId)->first();
        $of_cats = '';
        if(!empty($offline_categories)){
        foreach($offline_categories as $offline_category) {
            $of_cats = $of_cats .$offline_category . ","; }
        }
        if (empty($sOflinCats)) {
         SellerOfflineCategory::create([
                "user_id" => $userId,
                "category_id" => $of_cats,
            ]);
       } else {
           $sOflinCats->update(["category_id" => $of_cats]);
        }

    $input = [
        'seller_id' => $userId,
    ];

    $checkSun =  $request->get('chSunday'); 
        $input['day'] =  "Sunday";
        $input['opening_time'] = $request->get('opSundayhr');
        $input['closing_time'] = $request->get('clSundayhr');
        $input['Open_am_pm'] = $request->get('SunOpen_am_pm');
        $input['Close_am_pm'] = $request->get('WedClose_am_pm');
        
        if ($checkSun=="Yes")
        {    
            $input['closed'] = 'Yes';
            $input['opening_time'] ='';
            $input['closing_time'] = '';
            $input['Open_am_pm'] ='';
            $input['Close_am_pm'] = '';
        }
        else 
            $input['closed'] = 'No';
         $sunday =  SellerOpeningTime::where('seller_id','=',$userId)->where('day','=','Sunday')->first();  
         if(isset($sunday->id)){
               $SellerOpeningTime = DB::table('seller_opening_times')
                ->where('id',$sunday->id)
                ->update($input);
             } 
            else $SellerOpeningTime=SellerOpeningTime::create($input);
       
        $chMonday =  $request->get('chMonday');
        $input['day'] =  "Monday";
        $input['opening_time'] = $request->get('opMondayhr');
        $input['closing_time'] = $request->get('clMondayhr');
        $input['Open_am_pm'] = $request->get('MonOpen_am_pm');
        $input['Close_am_pm'] = $request->get('MonClose_am_pm');
        if ($chMonday=="Yes")
        {    
            $input['closed'] = 'Yes';
            $input['opening_time'] ='';
            $input['closing_time'] ='';
            $input['Open_am_pm'] = '';
            $input['Close_am_pm'] = '';
        }
        else 
            $input['closed'] = 'No'; 

        $monday =  SellerOpeningTime::where('seller_id','=',$userId)->where('day','=','Monday')->first();  
         if(isset($monday->id)){
               $SellerOpeningTime = DB::table('seller_opening_times')
                ->where('id',$monday->id)
                ->update($input);
             } 
            else $SellerOpeningTime=SellerOpeningTime::create($input); 

        $chTuesday =  $request->get('chTuesday');
        $input['day'] =  "Tuesday";
        $input['opening_time'] = $request->get('opTuesdayhr');
        $input['closing_time'] = $request->get('clTuesdayhr');
        $input['Open_am_pm'] = $request->get('TueOpen_am_pm');
        $input['Close_am_pm'] = $request->get('TueClose_am_pm');
        if ($chTuesday=="Yes")
        {    
            $input['closed'] = 'Yes';
            $input['opening_time'] ='';
            $input['closing_time'] ='';
            $input['Open_am_pm'] = '';
            $input['Open_am_pm'] = '';
        }
        else 
            $input['closed'] = 'No';
        $tuesday =  SellerOpeningTime::where('seller_id','=',$userId)->where('day','=','Tuesday')->first();  
         if(isset($tuesday->id)){
               $SellerOpeningTime = DB::table('seller_opening_times')
                ->where('id',$tuesday->id)
                ->update($input);
             } 
            else $SellerOpeningTime=SellerOpeningTime::create($input);   
 
            $chWednesday =  $request->get('chWednesday');
            $input['day'] =  "Wednesday";
            $input['opening_time'] = $request->get('opWednesdayhr');
            $input['closing_time'] = $request->get('clWednesdayhr');
            $input['Open_am_pm'] = $request->get('WedOpen_am_pm');
            $input['Close_am_pm'] = $request->get('WedClose_am_pm');
            if ($chWednesday=="Yes")
            {    
                $input['closed'] = 'Yes';
                $input['opening_time'] ='';
                $input['closing_time'] ='';
                $input['Open_am_pm'] = '';
                $input['Close_am_pm'] = '';
            }
            else 
                $input['closed'] = 'No';

            $wednesday =  SellerOpeningTime::where('seller_id','=',$userId)->where('day','=','Wednesday')->first();  
         if(isset($wednesday->id)){
               $SellerOpeningTime = DB::table('seller_opening_times')
                ->where('id',$wednesday->id)
                ->update($input);
             } 
            else $SellerOpeningTime=SellerOpeningTime::create($input);   
            $chThursday =  $request->get('chThursday'); 
            $input['day'] =  "Thursday";
            $input['opening_time'] = $request->get('opThursdayhr');
            $input['closing_time'] = $request->get('clThursdayhr');
            $input['Open_am_pm'] = $request->get('ThuOpen_am_pm');
            $input['Close_am_pm'] = $request->get('ThuClose_am_pm');
            if ($chThursday=="Yes")
            {    
                $input['closed'] = 'Yes';
                $input['opening_time'] ='';
                $input['closing_time'] ='';
                $input['Open_am_pm'] = '';
                $input['Close_am_pm'] = '';

            }
            else 
                $input['closed'] = 'No'; 
                
           $thursday =  SellerOpeningTime::where('seller_id','=',$userId)->where('day','=','Thursday')->first();  
         if(isset($thursday->id)){
               $SellerOpeningTime = DB::table('seller_opening_times')
                ->where('id',$thursday->id)
                ->update($input);
             } 
            else $SellerOpeningTime=SellerOpeningTime::create($input); 

            $chFriday =  $request->get('chFriday'); 
            $input['day'] =  "Friday";
            $input['opening_time'] = $request->get('opFridayhr');
            $input['closing_time'] = $request->get('clFridayhr');
            $input['Open_am_pm'] = $request->get('FriOpen_am_pm');
            $input['Close_am_pm'] = $request->get('FriClose_am_pm');
            if ($chFriday=="Yes")
            {    
                $input['closed'] = 'Yes';
                $input['opening_time'] ='';
                $input['closing_time'] ='';
                $input['Open_am_pm'] = '';
                $input['Close_am_pm'] = '';
            }
            else 
                $input['closed'] = 'No';   

            $friday =  SellerOpeningTime::where('seller_id','=',$userId)->where('day','=','Friday')->first();  
         if(isset($friday->id)){
               $SellerOpeningTime = DB::table('seller_opening_times')
                ->where('id',$friday->id)
                ->update($input);
             } 
            else $SellerOpeningTime=SellerOpeningTime::create($input); 

                $chSaturday =  $request->get('chSaturday'); 
                $input['day'] =  "Saturday";
                $input['opening_time'] = $request->get('opSaturdayhr');
                $input['closing_time'] = $request->get('clSaturdayhr');
                $input['Open_am_pm'] = $request->get('SatOpen_am_pm');
                $input['Close_am_pm'] = $request->get('SatClose_am_pm');
                if ($chSaturday=="Yes")
                {    
                    $input['closed'] = 'Yes';
                    $input['opening_time'] ='';
                    $input['closing_time'] ='';
                    $input['Open_am_pm'] = '';
                    $input['Close_am_pm'] = '';
                }
                else 
                    $input['closed'] = 'No';           
        
                $satday =  SellerOpeningTime::where('seller_id','=',$userId)->where('day','=','Saturday')->first();  
        if(isset($satday->id)){
               $SellerOpeningTime = DB::table('seller_opening_times')
                ->where('id',$satday->id)
                ->update($input);
        } 
        else $SellerOpeningTime=SellerOpeningTime::create($input);   
         echo json_encode("Seller Updated successfully!");   
    }

public function getpackagenamelist (Request $request)
{
   /* $user_type=$request->get('user_type');
    if($user_type!='')
        $Package=  Package::select('id','name')->where('user_type',$user_type)->where('status','active')->get();
    else*/
        $Package=  Package::select('id','name')->where('status','active')->get();
        //dd($Package);
    echo json_encode($Package);
} 

public function fetchUserhaveactiveplan (Request $request)
{
    $user_id=$request->get('user_id');

    $package_data = DB::table('subscriptions')
                        ->where('subscriptions.user_id', '=',$user_id)
                        ->where('subscriptions.status','Active')
                        ->whereDate('subscriptions.expairy_date', '>=', DB::raw('CURDATE()'))
                        ->orderBy('subscriptions.id','DESC')
                        ->count();
    
    echo json_encode($package_data);
} 


public function getpackageselected_details (Request $request)
{
    $id=$request->get('id');
    $Package_details=  Package::find($id);
    $PackageAccount=  PackageAccount::where('package_id', $id)->get();
    $return_data=['details'=>$Package_details,'PackageAccount'=>$PackageAccount];
    echo json_encode($return_data);
} 


public function adminassignpackagetoadmin (Request $request)
{
    $hdnuser_id=$request->get('hdnuser_id');
    $package_id=$request->get('package_id');
    $subitem=$request->get('subitem');

    $user_details=  User::find($hdnuser_id);
    $accounts = PackageAccount::where('id',$subitem)->first();
    $no_of_accounts = 0;
    if(!empty($accounts))
        $no_of_accounts = $accounts->no_of_accounts;    

    $order_data=array('user_id'=>$hdnuser_id,
        'package_id'=> $package_id,
        'order_type'=>'',
        'accounts_id'=> $subitem,
        'name'=>$user_details->name,
        'email'=>$user_details->email,
        'phone'=>$user_details->phone,
        'address'=>$user_details->address,
        'country'=>$user_details->country_id,
        'zip'=>$user_details->store_zip,
        'city'=>$user_details->store_city,
        'no_of_accounts'=>$no_of_accounts,
        );


    $Package=Package::find($package_id);


    if($Package->package_validity=="3 months") 
         $EndDate = Carbon::now()->addMonths(3);
    elseif($Package->package_validity=="6 months") 
         $EndDate = Carbon::now()->addMonths(6);
    elseif($Package->package_validity=="One year") 
         $EndDate = Carbon::now()->addMonths(12);
             
     $order=OrderDetail::create($order_data);

     DB::table('subscriptions')->where('subscriptions.user_id', '=',$hdnuser_id)
                               ->where('subscriptions.status','Active')->update(['status'=>'In-Active']);

     $Subscription_data=array('user_id'=>$hdnuser_id,
            'package_id'=> $package_id,
            'auto_renewal'=>1,
            'type'=> 'seller',
            'date'=>date('Y-m-d'),
            'expairy_date'=>$EndDate->toDateString(),
            'order_id'=>$order->id,
            'status'=>'Active',
           );
     
     $Subscription=Subscription::create( $Subscription_data);
    // $user_details->update(["usertype"=>strtolower($Package->user_type)]);
     $user_details->update(["usertype"=>'seller']);
     echo json_encode("Package Assigned");
}


public function adminusersellersstatusupdates (Request $request)
{
    $status=$request->get('status');
    $id=$request->get('id');

    $User=  User::find($id);
    $User->status =$status;
    $update=$User->save();
    if($update)
        echo json_encode('User '.$status);   
    else
        echo json_encode("Status Not Changed");
} 
 
    public function sellerdetails($id) {
        $seller = User::find($id);
        $kycdocs_varified = DB::table("kyc_files")
            ->select(DB::raw("count('*') as status_cnt"))
            ->where("user_id", $id)->where("status", "Active")->pluck("status_cnt")
            ->first(); 
        $varification_status=($kycdocs_varified==3)?"varified":"Not";
        $categories = Category::where('parent_id', null)->orderby('name', 'asc')->get(); //dd($categories);
        $seller_OflnCats = SellerOfflineCategory::select('category_id')->where('user_id', $id)->first();
        $company_types = CompanyType::select("id", "company_type")->get();
        return view('admin.seller.seller-view',compact('seller','company_types',"categories","seller_OflnCats",'id','varification_status'));
    }

    public function selleredit($id) {
        $seller = User::find($id);  
        $countries =Country::select('id','name')->get(); 
        $SellerOpeningTimes =SellerOpeningTime::where('seller_id',$id)->get(); 
        $company_types =CompanyType::select('id','company_type')->get();
        $categories = Category::where('parent_id', null)->orderby('name', 'asc')->get(); //dd($categories);
        $seller_OflnCats = SellerOfflineCategory::select('category_id')->where('user_id', $id)->first();
        return view('admin.seller.seller-profile',compact('countries',"categories","seller_OflnCats",'seller','SellerOpeningTimes','company_types'));
    }

     public function getsellerslist(Request $request)
    {  
        $company_types = CompanyType::select("id", "company_type")->get();
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
        $totalRecords =User::select('count(*) as allcount')
            ->when($request->get('status')!='', function ($query) use ($request) {
                $query->where('status',$request->get('status'));
            })
            //->where('usertype','seller')
			->where('seller_type','Master')->where('users.status','<>','Deleted')->count();
        
        $totalRecordswithFilter = User::leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
            ->leftJoin('countries', 'countries.id', '=', 'users.country_id')
            ->when($request->get('status')!='', function ($query) use ($request) {
                $query->where('users.status',$request->get('status'));
            })

            ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where(DB::raw('CONCAT_WS(users.name,email,phone,buyer_companies.company_name,countries.name)'), 'LIKE','%'.$searchValue.'%');
            })
            //->where('usertype','seller') 
			->where('seller_type','Master') 
            ->where('users.status','<>','Deleted')        
            ->count();       

        // Get records, also we have included search filter as well
        $records = User::leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
            ->leftJoin('countries', 'countries.id', '=', 'users.country_id')
            //->leftJoin('subscriptions', 'subscriptions.user_id', '=', 'users.id')
            ->select('users.*','buyer_companies.company_name','buyer_companies.company_type as cmp_type','buyer_companies.company_location','buyer_companies.company_street','buyer_companies.company_zip',
            DB::raw("countries.name as country_name"),DB::raw("(SELECT expairy_date FROM subscriptions WHERE subscriptions.user_id = users.id order BY subscriptions.id DESC limit 1) as expairy_date"))  
            ->when($request->get('status')!='', function ($query) use ($request) {
                $query->where('users.status',$request->get('status'));
            })
            ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where(DB::raw('CONCAT_WS(users.name,email,phone,buyer_companies.company_name,countries.name)'), 'LIKE','%'.$searchValue.'%');
            })
            //->where('usertype','seller')
			->where('seller_type','Master') ->where('users.status','<>','Deleted')       
            ->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();       
        foreach ($records as $record) {
            $status = (
                ( $record->status=='Active') ? '<span style="color:white;background-color:green;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.$record->status.'</span>':
                (($record->status=='Blocked') ? '<span style="color:white;background-color:red;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.$record->status.'</span>' :
                (($record->status=='Pending') ?'<span style="color:white;background-color:orange;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.$record->status.'</span>' :
                (($record->status=='Rejected') ? '<span style="color:white;background-color:purple;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.$record->status.'</span>' : ""
                ))));
			$userId = $record->id;	
			
			$c_types = $c_types_names =[]; 
                  if($record->cmp_type) { 
                  
                   foreach ($company_types as $company_type)
				   {
                              $c_types = explode(",",$record->cmp_type);
                               if(in_array($company_type->id, $c_types))
                             $c_types_names[] = $company_type->company_type ;
				  } 
				  }
                  $c_types_names =  implode( ', ', $c_types_names );
			
			
			
			
			
            $user = User::find($userId);
			$sellerProducts = $user->SellerProduct;
            $parent_cat_id=[];
            $values = [];
        foreach ($sellerProducts as $sproduct) {
            $values[] = trim($sproduct->category_id);
            if($sproduct->status=="active" && $sproduct->product_visibility=="Yes"){
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
        $parent_cat_id = array_unique($parent_cat_id);
       
        $values = array_unique($values);

        $parent_categorylists = Category::whereIn("id", $parent_cat_id)->orderBy('name',"ASC")->get();

        $category_product_count = [];
        foreach ($values as $row1) {
            $prdt_count = SellerProduct::where("status", "active")
                ->where("user_id", $userId)
                ->WhereRaw('find_in_set("' . $row1 . '",category_id)')
                ->count();
            $category_name = Category::find($row1, ["name", "category_pic"]);
            $category_product_count[] = [
                "product_count" => $prdt_count,
                "category" => $category_name,
            ];
        }
        arsort($category_product_count);
        $category_product_count = array_splice($category_product_count, 0, 3);

        $seller_Ofln_Cats = SellerOfflineCategory::select('category_id')->where('user_id', $userId)->first();
        if ($seller_Ofln_Cats) 
            $seller_offine_categorylists = explode(",", $seller_Ofln_Cats->category_id);
        else 
            $seller_offine_categorylists = []; 
        
        $categorylists = Category::whereIn("id", $parent_cat_id)
            ->orwhereIn("id", $seller_offine_categorylists)
            ->where('parent_id',null)
            ->whereNotNull('name')
            ->where('name','<>','')
            ->distinct() 
            ->orderBy('name','asc')
            ->pluck("name")
            ->all();
        $cats = implode( ', ', $categorylists ); 
			
            $name=$record->name.'<br/> '.$status;
            $address="";
            
            if($record->company_location)
                $address.=$record->company_location." ,";
            if($record->company_street)
                $address.=$record->company_street." ,";
            if($record->company_zip)
                $address.=$record->company_zip;
                
            $package_data = DB::table('subscriptions')
                                                                   ->leftJoin('order_details', 'subscriptions.order_id', '=', 'order_details.id')
                                                                   ->leftJoin('packages', 'packages.id', '=', 'order_details.package_id')
                                                                   ->where('subscriptions.user_id', '=',$userId)
                                                                   ->where('subscriptions.status','active')
                                                                   ->whereDate('subscriptions.expairy_date', '>=', Carbon::now())
                                                                   ->orderBy('subscriptions.id','DESC')
                                                                   ->first(); 
                  $product_count_approved = SellerProduct::where("user_id", $userId)->where("status",'<>', "deleted")->count(); 
                  $product_count_pending = SellerProductTemp::where("user_id", $userId)->where("status",'<>', "deleted")->count(); 
                  $product_count = $product_count_approved + $product_count_pending; 
                
                  $prdts_to_uplod=0;
                  if(!empty($package_data))
                    {
                    $market_uploads = $package_data->market_uploads;    
	                if($package_data->market_uploads=='')
                                   $prdts_to_uplod="Unlimited";
                    elseif($market_uploads>0 && $market_uploads>$product_count)
                                    $prdts_to_uplod=$market_uploads-$product_count;
                    else
                                    $prdts_to_uplod=0;	
              }
            $data_arr[] = array(
                "id" => $record->id,
                "name" =>$name,
                "c_types" =>$c_types_names,
                "prdts_to_uplod" =>$prdts_to_uplod,
				"categories" =>$cats,				
                "company_name" => $record->company_name,
                "status" => $record->status,
                "phone" => $record->phone,
                "email" => $record->email,
                "address" => $address,
                "created_at" => date('d-m-Y', strtotime($record->created_at)),
                "country_name" => $record->country_name, 
                "subscription" => $record->expairy_date==''? 'Nill': date('d-m-Y', strtotime($record->expairy_date)),);    
         }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);       
    }



    public function getsellerdocslist(Request $request)
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
        $user_id=$request->get('user_id');
        $status=$request->get('status');
        $totalRecords =KycFile::select('count(*) as allcount')->where('user_id',$user_id)->count();
        
        $totalRecordswithFilter = KycFile::where('user_id',$user_id)
           ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where('file_type','Like','%'.$searchValue.'%');
            })->count();       

        // Get records, also we have included search filter as well
        $records =KycFile::where('user_id',$user_id)
            ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where('file_type','Like','%'.$searchValue.'%');
            })
            ->orderBy($columnName,$columnSortOrder) 
            ->select('kyc_files.*')       
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
           
        foreach ($records as $record) {
            $arry= explode(".", $record->file_path );
            $type=$arry[count($arry)-1];
            $imgpath=$type=='pdf'?asset("/images/ic-pdf.jpg"):(($type=='doc'||$type=='docx')?asset("/images/ic-doc.jpg"):asset("/uploads/KYCFiles/")."/".$record->file_path);
            $imgpath1="'".asset("/uploads/KYCFiles/")."/".$record->file_path."'";
            $strimg='<div class="table-prof" onclick="fnviewdocs('.$imgpath1.')"><img style=" width:60px !important;" class="pr_img" src="'. $imgpath.' "></div>';
           

            $status = (
                ( $record->status=='Active') ? '<span style="color:white;background-color:green;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.$record->status.'</span>':
                (($record->status=='In-Active') ? '<span style="color:white;background-color:red;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.$record->status.'</span>' :
                (($record->status=='Rejected') ? '<span style="color:white;background-color:orange;padding:5px;line-height:12px;border-radius:2px;margin-top:5px;display:inline-block;">'.$record->status.'</span>' :
                '')));


            $data_arr[] = array(
                "id" => $record->id,
                "file_type" => $record->file_type. '<br/>'. $status,
                "file_path" => $strimg,
                "status" => $status,
                "status1" => $record->status,
                "reason" => $record->reason
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



     public function getcosellerslist(Request $request)
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
        $user_id=$request->get('user_id');
        $totalRecords =User::select('count(*) as allcount')->where('parent_id',$user_id)->where('status','!=','Deleted')->count();
        
        $totalRecordswithFilter = User::where('parent_id',$user_id)
            ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where(DB::raw('CONCAT_WS(users.name,email,phone)'), 'LIKE','%'.$searchValue.'%');
            })
            ->where('status','!=','Deleted')->count();       

        // Get records, also we have included search filter as well
        $records =User::where('parent_id',$user_id)
            ->when($searchValue!='', function ($query) use ($searchValue) {
                 $query->where(DB::raw('CONCAT_WS(users.name,email,phone)'), 'LIKE','%'.$searchValue.'%');
            })
            ->where('status','!=','Deleted')
            ->orderBy($columnName,$columnSortOrder) 
            ->select('*')       
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
           
        foreach ($records as $record) {
            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "phone" => $record->phone,
                "email" => $record->email,
                "status" => $record->status,
                "username" => $record->username,
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


    public function getsellerproductslist(Request $request)
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
        $user_id=$request->get('user_id');
        $status=$request->get('status');
        $totalRecords =SellerProduct::select('count(*) as allcount')
            ->where('user_id',$user_id)
            ->where('status','<>','deleted') 
            ->count();
        
        $totalRecordswithFilter = SellerProduct::when($searchValue!='', function ($query) use ($searchValue) {
                $query->where('name','Like','%'.$searchValue.'%');
            })
            ->where('user_id',$user_id)
            ->where('status','<>','deleted') 
            ->count();       

        // Get records, also we have included search filter as well
        $records =SellerProduct::when($searchValue!='', function ($query) use ($searchValue) {
            $query->where(DB::raw('lower(name)'),'Like','%'.strtolower($searchValue).'%');
           // $query->where('lower(name)','Like','%'.strtolower($searchValue).'%');
            //$query->where('name','Like','%'.$searchValue.'%');
            })
            ->where('user_id',$user_id)
            ->where('status','<>','deleted') 
            ->orderBy($columnName,$columnSortOrder) 
            ->select('*')       
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
           
        foreach ($records as $record) {
            if(!empty($record->SellerProductImage))
            { 
                $imagepath= asset("/uploads/defaultImages/no_image.jpg");
                foreach( $record->SellerProductImage as $productimage)
               {     
                        if($productimage->thumbnail == 'yes') 
                        {    
                            $imagepath= asset("/uploads/productImages/")."/".$productimage->image_path;
                            break;
                        }
                          
               }  
               
            }
            else
                     $imagepath= asset("/uploads/defaultImages/no_image.jpg"); 
            $str='<div class="table-prof"><img class="pr_img" src="'.$imagepath.'"/></div>';    
            
            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "file_path" =>$str,
                "product_visibility"=>$record->product_visibility

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


    public function viewProduct($productId){
        $product = SellerProduct::find($productId); 
        if(empty($product)) 
            return redirect()->route('admin.sellerslist')->with('message','No Product Found');
        $categories = Category::where('parent_id', null)->get();
        $country_ids = explode(',',$product->available_countries);
        $countries = Country::whereIn('id',$country_ids)->pluck('name');
        
   
             
        return view('admin/products/view-product',compact('product','countries','categories'));  
    }
    
    public function deleteProduct($productId)
    {
        $product=  SellerProduct::find($productId);
        
        
        $productImages = SellerProductImage::where(
            "product_id",
            "=",
            $productId
        )->get();

        

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
        
    
        
        
        if(empty($product)){ 
            return redirect()->route('admin.sellerslist')->with('message', 'Product not Exists');
        }
        
        return redirect()->back()->with('message', 'Product Deleted!');
    }

    public function sellersstatusupdates(Request $request)
    {
        $status=$request->get('status');
        $id=$request->get('id');
        $User=  User::find($id);
        $User->status =$status;
        $update=$User->save();


        if($status=='Blocked')
            DB::table('seller_products')->where('user_id',$id)->where('status','active')
                ->update(['status'=>'pending']);
        else
            DB::table('seller_products')->where('user_id',$id)->where('status','pending')->update(['status'=>'active']);

            $seller_active_products=SellerProduct::where('user_id',$id)->get();
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
                
        
        
        if($update)
            echo json_encode('User '.$status);   
        else
            echo json_encode("Status Not Changed");
    }  

    public function editProduct($productId) {
        
        $product = SellerProduct::find($productId);
        if(empty($productId )) return redirect() ->route('admin.sellerslist')->with('message','Product not exists');
        $varients = SellerProduct::where('user_id','=',$product->user_id)->get();
        $countries = Country::all();
       // $product_images= SellerProductImage::find($productId); 
        $product_images = SellerProductImage::where('product_id','=',$productId)->get();
        $categories = Category::where('parent_id', null)->orderby('name','asc')->get();
        $cat_selected =Category::where('id', $product->category_id)->first();
		$currencies =Currency::select('id','shortcode','symbol')->get();
		$Productbrand = Productbrand::get();
        $product_brand = '';
        if(!empty($product->brands)) 
        $product_brand = DB::table("productbrands")
                ->where("id", $product->brands)
                ->pluck("name")
                ->first();
        return view('admin.seller.seller-edit-product' ,compact('product','cat_selected','categories','varients','Productbrand','countries','currencies','product_brand','product_images'));
    
    }

     public function updateProduct(Request $request) {
        
        request()->validate([
            'name' => ['required', 'string'],
                        'category_id' => ['required'],
         //   'SKU' =>  ['required','string',  Rule::unique('seller_products')->ignore($request->id)->where(function ($query)  {return $query->where('status','<>','deleted');})], 
         ],['name.required' => ' The product description field is required.','category_id.required' => ' The category field is required.']);
       /* if ($request->input("unlimited_stock") != "Unlimited") {
            $data = $request->validate([
                "stock_count" => "required|numeric",
            ]);
        }*/
        
        if($request->input('price_on_request')!='Price on request')
                $data = $request->validate([
                    'product_price'      =>'required|numeric',
                ],['product_price.required' => ' The price field is required.','product_price.numeric' => 'The price must be a number.']);
                
        $productId = $request->get('id');
        $product = SellerProduct::find($productId);
       
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
        
        
         $input['price_on_request']  = 'No';
        if($request->input('price_on_request')!='')
        $input['price_on_request'] = $request->input('price_on_request');
        
        $input['available_countries'] = $available_countries;
        $input['category_id']=$category_id;
        $input['variants']=$variants;
        
        // if($request->hasFile("product_image")){ 
        //     $file=$request->file("product_image");
        //     $fileName=time().'_'.$file->getClientOriginalName();
        //     $destinationPath = public_path().'/uploads/productImages' ;
        //     $file->move($destinationPath,$fileName);
        //     $img['image_path'] = $fileName;
        //     $img['thumbnail'] = "yes";
        //     $img['product_id'] = $productId ;
        //     DB::table('seller_product_images')->where([
        //         ['product_id', '=', $productId],
        //         ['thumbnail', '=', 'yes'],
        //     ])->delete();
        //     SellerProductImage::create($img);

        // }

            if($request->hasFile("product_gallery")){
                $files=$request->file("product_gallery");
                $i=0;
                $SellerProductImage =SellerProductImage::where('product_id',$productId)
                         ->where('thumbnail','yes')->first();
                foreach($files as $file){
                    if($i==0 && empty($SellerProductImage))
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
                    SellerProductImage::create($img);

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
	
	    $old_category_id=$product->category_id;
	    $input['brands'] = $brand_id;
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
        \Session::flash('message',$msg);
        \Session::reflash(); 
        return json_encode($msg);
    }
	
	
	    public function getnewsletterslist(Request $request)
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
        $user_types=$request->get('user_types');
        $totalRecords =NewsletterSubscription::select('count(*) as allcount')->count();
        $p1 = DB::table('newsletter_subscriptions')
            ->select('id','email', 'newsletter_status',DB::raw("NULL as surname"),DB::raw("NULL as name"),DB::raw("NULL as company_name"),DB::raw("NULL as usertype"));

        $p2 = DB::table('users')
                ->leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
                ->where('newsletter_status', 'Yes')
                ->where('users.status','<>','Deleted')
                ->select('users.id','email', 'newsletter_status','surname','name','company_name','usertype');

        $mergeTbl = $p1->unionAll($p2);

        $totalRecords = DB::table(DB::raw("({$mergeTbl->toSql()}) AS p"))
            ->mergeBindings($mergeTbl)
            ->select('count(*) as allcount')->count();
        $totalRecordswithFilter = DB::table(DB::raw("({$mergeTbl->toSql()}) AS mg"))->mergeBindings($mergeTbl)
            ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where('email','Like','%'.$searchValue.'%') ;
            })
            ->when($user_types =='registered_users', function ($query) use ($user_types) {
                $query ->whereNotNull('usertype'); 
                
              })
             ->when($user_types =='guests', function ($query) use ($user_types) {
                $query ->whereNull('usertype');
            })
            ->count();       

        // Get records, also we have included search filter as well

        $records = DB::table(DB::raw("({$mergeTbl->toSql()}) AS mg"))->mergeBindings($mergeTbl)
            ->orderBy($columnName,$columnSortOrder) 
            ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where('email','Like','%'.$searchValue.'%');
            }) 
            ->when($user_types!='', function ($query) use ($user_types) {
                $query->where('newsletter_status','Yes') ;
            }) 
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        if($user_types=='registered_users')
             $records = User::orderBy($columnName,$columnSortOrder) 
             ->leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
            ->when($searchValue!='', function ($query) use ($searchValue) {
                $query->where('email','Like','%'.$searchValue.'%');
            }) 
            ->when($user_types!='', function ($query) use ($user_types) {
            //    $query->where('usertype','<>','guest') ;
                $query->where('newsletter_status','Yes') ;
            }) ->where('users.status','<>','Deleted')
            ->select('users.id','email', 'newsletter_status','surname','name','company_name','usertype')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        if($user_types=='guests')
            $records = NewsletterSubscription::orderBy($columnName,$columnSortOrder) 
                ->when($searchValue!='', function ($query) use ($searchValue) {
                    $query->where('email','Like','%'.$searchValue.'%');
                }) 
                ->when($user_types!='', function ($query) use ($user_types) {
                   // $query->where('usertype','guest') ;
                    $query->where('newsletter_status','Yes') ;
                }) 
                ->select('*')
                ->skip($start)
                ->take($rowperpage)
                ->get();

        $data_arr = array();
           
        foreach ($records as $record) {
            $data_arr[] = array(
                "id" => $record->id,
                "email" => $record->email,
                "surname" => $record->surname,
                "name" => $record->name,
                "user_types" => $record->newsletter_status,
                "company_name" => $record->company_name,
                
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
    public function NewsLettersLists(Request $request) {   
        $user_types=$request->get('user_types');
        $filter=$request->get('filter');
        if($filter=='Excel') 
            return Excel::download(new NewsletterExport($user_types), 'News_letter_Subcribers_List.xls');
        else
            return view('admin.newsletter.newsletter-lists');

    }

    public function promotionalNewsLettersLists(Request $request) {  

        $filter=$request->get('filter');
        if($filter=='Excel') 
            return Excel::download(new PromoNewsletterExport($request->get('search_key')), 'Promo_Newsletter_List.xls');
        else
            return view('admin.newsletter.promotional_news_letter');

    }


     public function getpromotionalnewsletterslist(Request $request)
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
        
        $totalRecords =DB::table('users') ->where('hide_promo_email','No')
            ->where('users.status','<>','Deleted')
            ->select('count(*) as allcount')->count();
        $totalRecordswithFilter = DB::table('users') 
             ->when( $searchValue!='', function ($query) use ( $searchValue) {
                    $query->where('email', 'like', '%' . $searchValue . '%');
                })
            ->where('hide_promo_email','No')->where('users.status','<>','Deleted')
            ->count();

        $records = DB::table('users') ->where('hide_promo_email','No')
            ->leftJoin('buyer_companies', 'buyer_companies.user_id', '=', 'users.id')
            ->where('users.status','<>','Deleted')        
            ->when( $searchValue!='', function ($query) use ( $searchValue) {
                    $query->where('email', 'like', '%' . $searchValue . '%');
                })
            ->orderBy($columnName,$columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->select('users.id','email', 'newsletter_status','surname','name','company_name')
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
           
            $data_arr[] = array(
                "id" => $record->id,
                "email" => $record->email,
                "surname" => $record->surname,
                "name" => $record->name,
                "company_name" => $record->company_name,
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

public function userkycupload(Request $request) {
        $request->validate([
             'file_type'      => 'required',
             'file_docupload'      =>'required',
             'hdnuserid'      =>'required',
         ]);

        $extension = request("file_docupload")->extension();
        $fileName = "kyc_doc" . time() . "." . $extension;
        $destinationPath = public_path() . "/uploads/KYCFiles";
        request("file_docupload")->move($destinationPath, $fileName);
        $data = [
            "user_id" => $request->get('hdnuserid'),
            "file_path" => $fileName,
            "reason" => "",
            "file_type" => $request->get('file_type'),
        ];

        $kycfile_data=KycFile::where('user_id',$request->get('hdnuserid'))
                         ->where("status",'<>', "Rejected")
                        ->where('file_type',$request->get('file_type'))->first();
        if(empty($kycfile_data))                
            $update = KycFile::create($data);
        else
            $kycfile_data->update($data);
        $id=$request->get('hdnuserid'); 
        if($request->get('frompage')=='sellerside') 
            return redirect()->route('admin.sellerview',$id)->with('message','Kyc Document Uploaded.'); 
        else if($request->get('frompage')=='buyerside') 
            return redirect()->route('admin.buyerview',$id)->with('message','Kyc Document Uploaded.'); 
        else if($request->get('frompage')=="kycside")
            return redirect()->route('admin.kyclist')->with('message','Kyc Document Uploaded.'); 
	}
	//admin user password reset
    public function adminresetpassword (Request $request)
    {
        $hdnuser_id=$request->get('hdnuser_id');
        $Password=$request->get('Password');
        $hash_pass = Hash::make($request->get("Password"));
        $user = User::find($hdnuser_id);
        
        Mail::send(
                "emails/admin_reset_user_password",
                [
                    "name" => $user->name,
                    "password" => $Password,
                ],
                function ($message) use ($user) {
                    $message->from("example@gmail.com", "Fmcg");
                    $message
                        ->to($user->email)
                        ->subject("Fmcg Login Credential");
                }
            );
            
         $user->update(['password'=>$hash_pass]);
         echo json_encode("Password Changed");
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
