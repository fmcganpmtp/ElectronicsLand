<?php
namespace App\Http\Controllers\FrontEnd;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Product;
use App\Models\Country;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\SellerMessage;
use App\Models\SellerProduct;
use App\Models\Wishlist;
use App\Models\Productbrand;
use App\Models\SearchCondition;
use DB;
use Mail;
use App\Models\ReviewSubmit;
use App\User;
use URL;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\FrontEnd\PublicMiddlewareController;

class ProductController extends Controller
{
    protected $PublicMiddlewareController;
    public function __construct(PublicMiddlewareController $PublicMiddlewareController) 
    {
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
    public function ReviewSubmit(Request $request)
    {
        request()->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required"],
            "review_title" => ["required"],
            "review" => ["required"],
        ]);
        $user_id = Auth::guard("user")->user()->id;
        $input = [
            "user_id" => $user_id,
            "product_id" => $request->get("product_id"),
            "name" => $request->get("name"),
            "email" => $request->get("email"),
            "review_title" => $request->get("review_title"),
            "review" => $request->get("review"),
            "star_rating" => $request->get("star_rating"),
        ];
        $product_review = ProductReview::create($input);
        return back()->with("message", "Thank you for your review.");
    }

    public function autocompleteSproductFrontend(request $request)
    {
        $active_sellers = $this->PublicMiddlewareController->getexpiredsellerslist();
        $user_id = Auth::guard("user")->user()->id;
        $products = SellerProduct::where("status", "active")
            ->whereIn("seller_products.user_id", $active_sellers)
            ->where("name", "Like", $request->term . "%")
            ->where('seller_products.product_visibility','Yes')
            ->where("user_id", $user_id)
            ->select("id", "name")
            ->limit(10)
            ->get();
        return $products;
    }

    public function SelleMessage(Request $request)
    {
        request()->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required"],
            "phone" => ["required"],
            "message" => ["required"],
        ]);

        $input["name"] = $name = $request->input("name");
        $input["email"] = $email = $request->input("email");
        $input["phone"] = $phone = $request->input("phone");
        $input["message"] = $msg = $request->input("message");
        $input["product_id"] = $product_id = $request->input("product_id");
        $input["seller_id"] = $seller_id = $request->input("seller_id");
        $input["user_id"] = $user_id = Auth::guard("user")->user()->id;
        $seller_email = $request->input("seller_email");
        SellerMessage::create($input);
        $productname = SellerProduct::where("id", $product_id)
            ->pluck("name")
            ->first();
        Mail::send(
            "emails.SellerMessage",
            [
                "name" => $name,
                "product_id" => $product_id,
                "productname" => $productname,
                "email" => $email,
                "phone" => $phone,
                "msg" => $msg,
            ],
            function ($message) use ($request) {
                $message->to($request->input("seller_email"));
                $message->subject("New Message - FMCG");
            }
        );
        return response()->json([
            "success" =>
                '<div class="alert alert-success" >Message has been send successfully.</dv>',
        ]);
    }

    public function searchcat()
    {
        $cat = \Input::get("cat");
        $cat = (int) $cat;
        $vacancies = \Vacancy::where("category_id", "=", $cat)->get();
        return \View::make("vacancies.empty")->with("vacancies", $vacancies);
    }
    public function extendedidSearch(Request $request)
    {
        $active_sellers = $this->PublicMiddlewareController->getexpiredsellerslist();
        $validity = $this->PublicMiddlewareController->checkUserContactValidity();
        $start_from = $request->input("start_from");
        $per_page = $request->input("per_page");
        $central_listing = [];
        $category_id = [];
        $price_range = explode("-", $request->input("price_range"));
        $start_range = $price_range[0];
        $end_range = $price_range[1];
        $search_key = $request->input("search"); 
        $type_searchby_text = $request->input("type_searchby_text");
        $topcategorysearch = $request->input("topcategorysearch");
        $priceOnRequest = $request->input("priceOnRequest");
        if ($request->input("category_id") != "") 
		{
            $category_id = explode(",", $request->input("category_id"));
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
            
		
	}
        elseif (!empty($request->input("topcategorysearch"))) {

            if($type_searchby_text=='full')
            {    
                    $category = Category::where("slug", "LIKE",$request->input("topcategorysearch"))->where('have_product','Yes')->first(); //get one parent category
                    $categorylist = Category::where("parent_id", $category->id)->where('have_product','Yes')
                            ->pluck("id")->all(); // all subcategories
                    array_push($category_id, $category->id); //parent category to array}

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
            }
            else
            {
                $sellerProducts = SellerProduct::select("seller_products.*")
                    ->leftJoin("users", "users.id", "=", "seller_products.user_id")
                    ->where("users.status", "Active")
                    ->where('seller_products.product_visibility','Yes')
                    ->whereIn("seller_products.user_id", $active_sellers)
                    ->where(DB::raw("lower(seller_products.name)"),"LIKE", "%" .strtolower($topcategorysearch)."%")
                    ->where("seller_products.status", "active")
                    // ->latest("seller_products.created_at")
                    ->orderBy("seller_products.price_on_request",'ASC')
                    ->orderBy("seller_products.created_at",'DESC')
                    ->get();

                foreach ($sellerProducts as $sproduct) 
                {
                    foreach (explode(",", $sproduct->category_id) as $value) 
                        $category_id[] = trim($value);    
                }
               
            }
           
        }   
            $products = SellerProduct::leftJoin("users","users.id", "=", "seller_products.user_id")
                ->select(["seller_products.name AS prd_name", "seller_products.*",])
                ->where("users.status", "Active")
                ->where('seller_products.product_visibility','Yes')
                ->whereIn("seller_products.user_id", $active_sellers)
                ->where(function ($query) use (
                    $category_id,
                    $start_range,
                    $end_range,
                    $search_key
                ) {
                    foreach ($category_id as $id) {
                        $query->orWhere("category_id", $id);
                        $query->where(function ($query) use ($start_range,$end_range) {
                             $query->whereBetween( "seller_products.product_price",[$start_range, $end_range])
                                    ->where('seller_products.price_on_request','<>','Price on request')
                                    ->orWhere('seller_products.price_on_request','Price on request');
                            });
                        
                        $query->where("seller_products.status", "active");
                    }
                });

            if ($search_key != "") {
                $products = $products->where(
                    DB::raw(
                        'UPPER(CONCAT(seller_products.name,COALESCE(seller_products.product_description,"")))'
                    ), "LIKE", "%" . strtoupper($search_key) . "%"
                );
            }
             if ($topcategorysearch != "" && $type_searchby_text!='full') {
                $products = $products->where(
                    DB::raw(
                        'UPPER(CONCAT(seller_products.name,COALESCE(seller_products.product_description,"")))'
                    ), "LIKE", "%" . strtoupper($topcategorysearch) . "%"
                );
            }

            

            if ($request->input("country_id") != "") {
                $country_id = explode(",", $request->input("country_id"));
                $products = $products->where(function ($query) use (
                    $country_id
                ) {
                    foreach ($country_id as $c_id) {
                        $query->orWhereRaw( 'find_in_set("' . $c_id . '",country_id)');
                    }
                });
            }

            if ($request->input("brand_id") != "") {
                $brand_id = explode(",", $request->input("brand_id"));
                $products = $products->whereIn("brands", $brand_id);
            }
           
        //     $products = $products ->where(function ($query) use ($start_range,$end_range) {
        //         $query->whereBetween( "seller_products.product_price",[$start_range, $end_range])
        //            ->where('seller_products.price_on_request','<>','Price on request')
        //            ->orWhere('seller_products.price_on_request','Price on request');
        //    });
            if($priceOnRequest==1)
            {
                $products = $products->where('seller_products.price_on_request','Price on request');
            }
            elseif($priceOnRequest==2)
            {
                $products = $products->where('seller_products.price_on_request','<>','Price on request');
            }
            else
            {
                $products = $products ->where(function ($query) use ($start_range,$end_range) {
                    $query->whereBetween( "seller_products.product_price",[$start_range, $end_range])
                       ->where('seller_products.price_on_request','<>','Price on request')
                       ->orWhere('seller_products.price_on_request','Price on request');
               });
            }
           
           

            $count = $products->count();
            $products = $products
                            //->orderBy('seller_products.id','Desc')
                            ->orderBy("seller_products.price_on_request",'ASC')
                            ->orderBy("seller_products.created_at",'DESC')
                            ->skip($start_from)
                            ->take($per_page)
                            ->get();

            $data_return = [];
            foreach ($products as $data) {
                $prd_img = URL::asset("/images/no-image.jpg");
                foreach ($data->SellerProductImage as $prod_img) {
                    if ($prod_img->thumbnail == "yes") {
                        $prd_img = URL::asset(
                            "/uploads/productImages/" . $prod_img->image_path
                        );
                    }
                }

                $seller_id = $data->user_id;
                $seller = User::find($seller_id);
                $seller_country_id = DB::table("users")
                    ->where("id", $seller_id)
                    ->pluck("country_id")
                    ->first();
                $seller_country_name = DB::table("countries")
                    ->where("id", $seller_country_id)
                    ->pluck("name")
                    ->first();
                $curreny_symbol = '$';
                if (!empty($data->Currency->symbol)) {
                    $curreny_symbol = $data->Currency->symbol;
                }

                if ($validity == false) {
                    $product_price = $country_name = "*****";
                } else {
                    if(strtolower(trim($data->price_on_request)) != strtolower('Price on request')) {
                        if (strtolower(trim($data->price_negotiable)) == strtolower('Negotiable')) {
                            $product_price =$curreny_symbol.'&nbsp;'.$data->product_price ." (Price Negotiable)";
                        } else {
                            
                    $prod_price = $data->product_price;
                    $no_of_dec =0;
                   if (strpos($prod_price, ".") !== false) {
                         $v_fileFloatb = explode(".", $prod_price);
                         $no_of_dec = strlen($v_fileFloatb[1]);   
                   }

                  
                   if($no_of_dec==1)
                   $product_price = $curreny_symbol.'&nbsp;'.rtrim(rtrim(number_format($data->product_price,3), '0'), '.').'0';
                   else 
                   $product_price = $curreny_symbol.'&nbsp;'.rtrim(rtrim(number_format($data->product_price,3), '0'), '.');
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                        }
                    } else {
                        $product_price = "Price on Request";
                    }
                }

                $country_name = $seller_country_name;
                $data_return[] = [
                    "product_name" => $data->prd_name,
                    "currency" => $curreny_symbol,
                    "product_thumbnail" => $prd_img,
                    "available_countries" => $country_name,
                    "price" => $product_price,
                    "stock_count" => $data->stock_count,
                    "product_id" => $data->id,
                ];
            }

            $return_array = [
                "count" => $count,
                "product_data" => $data_return,
                "central_listing" => $central_listing,
            ];
        return json_encode($return_array);
    }

    public function ProductListing($search_key = null)
    {
        DB::enableQueryLog();
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

        $user_id = $user = $wish_listed = "";
        if (Auth::guard("user")->check()) {
            $user_id = Auth::guard("user")->user()->id;
            $user = User::find($user_id);
            $usertype = Auth::guard("user")->user()->usertype;
            // $wish_listed=  Wishlist::where('product_id','=',$productId)->where('user_id','=',$user_id)->pluck('wishlist')->first();
        }

            $search_conditions = SearchCondition::pluck("search_name")->all();
       
            $category_id = Category::where("slug", "LIKE", $search_key)->where('have_product','Yes')
                ->pluck("id")
                ->all(); 
               
            if(empty($category_id))
            {
                $type_searchby_text='partial';
                $category_id=[];
                $sellerProducts = SellerProduct::select("seller_products.*")
                    ->leftJoin("users", "users.id", "=", "seller_products.user_id")
                    ->where("users.status", "Active")
                    ->where('seller_products.product_visibility','Yes')
                    ->whereIn("seller_products.user_id", $active_sellers)
                    ->where(
                        DB::raw("lower(seller_products.name)"),
                        "LIKE",
                        "%" . strtolower($search_key) . "%"
                    )
                    ->where("seller_products.status", "active")
                    // ->latest("seller_products.created_at")
                    ->orderBy("seller_products.price_on_request",'ASC')
                    ->orderBy("seller_products.created_at",'DESC')
                    ->get();
                foreach ($sellerProducts as $sproduct) 
                {
                    foreach (explode(",", $sproduct->category_id) as $value) 
                    {
                        $category_id[] = trim($value);
                    }
                }
               $categorylist = Category::whereIn("id", $category_id)->where('have_product','Yes')->get();

            } 
            else
            { 
                $type_searchby_text='full';
                $categorylist = Category::whereIn("parent_id", $category_id)->where('have_product','Yes')->get();
               
            }

            $category_id_all = $central_listing = [];
            foreach ($categorylist as $value) {
                if (!in_array($value->id, $category_id_all)) 
                    array_push($category_id_all, $value->id);
               
                if($type_searchby_text=='full')
                {   
                    array_push($central_listing, [
                                        "id" => $value->id,
                                        "name" => $value->name,
                                    ]);
                

                    for ($i = 0; $i < count($category_id_all); $i++) {
                        $category1 = Category::where("parent_id", $category_id_all[$i])->where('have_product','Yes')->get();
                        foreach ($category1 as $value2) {
                            if (!in_array($value2->id, $category_id_all)) 
                                array_push($category_id_all, $value2->id);               
                        }
                    }
                }    
            }

            $left_menu_list = [];
            for ( $i = 0; $i < count($category_id_all) && count($categorylist) > 0;  $i++ ) {
                $categorylist = Category::where( "id",  $category_id_all[$i] )->where('have_product','Yes')->get();
                foreach ($categorylist as $value2) {
                    array_push($left_menu_list, [
                        "id" => $value2->id,
                        "name" => $value2->name,
                    ]);
                }
            }

            if(count($category_id)>0)
                $category_id_all[] = $category_id[0];
            if($type_searchby_text=='full')
            {  
                $brand_lists = SellerProduct::leftJoin( "productbrands",  "productbrands.id",  "=",  "seller_products.brands"  )
                    ->leftJoin("users", "users.id", "=", "seller_products.user_id")
                    ->orWhere(function ($query) use ($category_id_all) {
                        foreach ($category_id_all as $term) {
                            $query
                                ->orWhereRaw(
                                    'find_in_set("' . $term . '",category_id)'
                                )
                                ->where("seller_products.status", "active");
                        }
                    })
                    ->where("users.status", "Active")
                    ->where('seller_products.product_visibility','Yes')
                    ->where("productbrands.name", "<>", "")
                    ->whereIn("seller_products.user_id", $active_sellers)
                    ->select(
                        "productbrands.id",
                        "productbrands.name",
                        DB::raw("count(*) as count")
                    )
                    ->groupBy("seller_products.brands")
                    ->get();
            }
            else
                 $brand_lists =[];

            $search_conditions = SearchCondition::pluck("search_name")->all();

            $Trendingproducts = SellerProduct::select("seller_products.*")
                ->with("SellerProductImage")
                ->leftJoin("users", "users.id", "=", "seller_products.user_id")
                ->whereIn("seller_products.user_id", $active_sellers)
                ->where("users.status", "Active")
                ->where('seller_products.product_visibility','Yes')
                ->Where(function ($query) use ($category_id_all) {
                    foreach ($category_id_all as $term) {
                        $query
                            ->orWhereRaw(
                                'find_in_set("' . $term . '",category_id)'
                            )
                            ->where("seller_products.status", "active");
                    }
                })
                ->where("seller_products.status", "active")
                ->latest("seller_products.created_at")
                ->take(5)
                ->get();

            $max_product_price = SellerProduct::leftJoin(
                "users",
                "users.id",
                "=",
                "seller_products.user_id"
            )
                ->orWhere(function ($query) use ($category_id_all) {
                    foreach ($category_id_all as $term) {
                        $query
                            ->orWhereRaw(
                                'find_in_set("' . $term . '",category_id)'
                            )
                            ->where("seller_products.status", "active");
                    }
                })
                ->where("users.status", "Active")
                ->where('seller_products.product_visibility','Yes')
                ->whereIn("seller_products.user_id", $active_sellers)
                ->max("seller_products.product_price");

            $max_product_price = ceil($max_product_price ?? 0 );
            $category_Name_fr_slug = Category::where("slug", "LIKE", $search_key)->pluck("name")->first(); 
        
            if($this->isMobile()) 
                return view("frontEnd.products.product-listing_mobile",compact("user","validity","brand_lists","search_conditions","search_key","Trendingproducts","left_menu_list","central_listing", "max_product_price","type_searchby_text","category_Name_fr_slug"));
            else 
                return view("frontEnd.products.product-listing",compact("user","validity","brand_lists","search_conditions","search_key","Trendingproducts","left_menu_list","central_listing", "max_product_price","type_searchby_text","category_Name_fr_slug"));

            
           
        

    }
}
