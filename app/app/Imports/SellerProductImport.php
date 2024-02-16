<?php
namespace App\Imports;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\SellerProductTemp;
use App\Models\Currency;
use App\Models\SellerProductImageTemp;
use App\Models\Productbrand;
use App\Models\SellerProduct;
use Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;
use App\Models\Country;
use Illuminate\Validation\Rule;
use Storage;
use DB;
class SellerProductImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    function __construct($seller_id = null)
    {
        $this->seller_id = $seller_id;
    }


    public function collection(Collection $rows)
    {
                   
                   if ($this->seller_id == null) {
                if (Auth::guard("user")->user()->seller_type != "Master") {
                    $parent_id = Auth::guard("user")->user()->parent_id;
                } else {
                    $parent_id = Auth::guard("user")->user()->id;
                }
            } else {
                $parent_id = $this->seller_id;
            }
                  
                   /*$user=User::find($user_id);
                   // uploads count checking
                   if($user->seller_type=='Co-Seller')
                                                                   $parent_id=$user->parent_id;
                    else
                                                                   $parent_id=$user->id;    */
                                               
                    $package_data = DB::table('subscriptions')
                                                                   ->leftJoin('order_details', 'subscriptions.order_id', '=', 'order_details.id')
                                                                   ->leftJoin('packages', 'packages.id', '=', 'order_details.package_id')
                                                                   ->where('subscriptions.user_id', '=',$parent_id)
                                                                   ->where('subscriptions.status','active')
                                                                   ->whereDate('subscriptions.expairy_date', '>=', Carbon::now())
                                                                   ->orderBy('subscriptions.id','DESC')
                                                                   ->first(); 
                  $product_count_approved = SellerProduct::where("user_id", $parent_id)->where("status",'<>', "deleted")->count(); 
                  $product_count_pending = SellerProductTemp::where("user_id", $parent_id)->where("status",'<>', "deleted")->count(); 
                  $product_count = $product_count_approved + $product_count_pending;
                  //dd($product_count);
                  if(!empty($package_data)){
                                  $market_uploads = $package_data->market_uploads;
                                  if($package_data->market_uploads=='')
                                    	$prdts_to_uplod="Unlimited";
                                  elseif($market_uploads>0 && $market_uploads>$product_count)
                                        $prdts_to_uplod=$market_uploads-$product_count;
                                  else
                                        $prdts_to_uplod=0;   
                                        
                      
                  }
                  /// end upload count check
        
        foreach ($rows as $index=>$row) {
            if(isset($row['product_description']))
            {
             if($row['product_description']=='')
             unset($rows[$index]);
            }
            else{
             unset($rows[$index]);
            } 
         }
         if($market_uploads!="Unlimited" && $prdts_to_uplod<count($rows))
         {   $validator = "Product Limit Exceeds";
             return back()
                ->withErrors($validator)
                ->withInput();
         }
        
        $validator = Validator::make($rows->toArray(), [
             "*.product_description" => ["required"],
            // '*.price'=> ["required_unless:*.price_on_request,Price on request"],
             '*.price' => 'required_unless:*.price_on_request,Price on request,price_on_request,price on request',
              "*.categories" => ["required"],

         ]); 
        
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } 
             
  
        foreach ($rows as $row) {  
            $prd_ids = $vartnts = $ctrys = $cats = [];

            
            //check valid date 
            $product_expiry = null;
            if (isset($row["bbd_expiry_date"])) {
                $date = date_parse($row["bbd_expiry_date"]);
                if (
                    $date["error_count"] == 0 &&
                    checkdate($date["month"], $date["day"], $date["year"])
                ) {
                    $product_expiry = date(
                        "Y-m-d",
                        strtotime($row["bbd_expiry_date"])
                    );
                }
            }
              //set price on request default value as 'No'
              $price_on_request = "No";
               if (isset($row["price_on_request"])) {
                $price_on_request = strtolower(trim($row["price_on_request"]));
                $price_on_request = ucfirst($price_on_request);
            }
            
            
               $unlimited_stock = null;
               if (isset($row["unlimited_stock"])) {
                $unlimited_stock = strtolower(trim($row["unlimited_stock"]));
                $unlimited_stock = ucfirst($unlimited_stock);
            }
            
            
            $price_negotiable = null;
               if (isset($row["price_negotiable"])) {
                $price_negotiable = strtolower(trim($row["price_negotiable"]));
                $price_negotiable = ucfirst($price_negotiable);
            }
            
            
        $product_price=null;
        if (isset($row["price"])) {
            $product_price  = $row["price"];
		    $product_price = (float) str_replace(',', '', $product_price)   ;
		    
		    $product_price = round($product_price,3);
        } 
	    $currency = $row["currency"];
	    $currency = str_replace(';', '', $currency);
              
            $ret = SellerProductTemp::create([
                "name" => html_entity_decode($row["product_description"]),
                "product_price" => $product_price,
                "SKU" => $row["sku"],
                'EAN_GTIN'    => $row["ean_gtin"], 
                'batch'    => $row["batch"], 
                "stock_count" => $row["avialable_stock"],
               // "product_color" => $row["product_color"],
                "product_weight" => $row["product_weight_size"],
               // "product_size" => $row["product_size"],
                "label_language" => $row["label_language"],
               // "product_dimension" => $row["product_dimension"],
                "minimal_order" => $row["minimal_order_quantity"],
                "product_condition" => $row["product_condition"],
                "product_description" => $row["description"],
                "categories" => $row["categories"],
                "brands" => $row["brands"],
                "currency" => $currency,
               // "available_countries" => $row["countries"],
                "location" => $row["stock_location"],
               // "varients_skus" => $row["varients"],
                "product_expiry" => $product_expiry,
                "status" => "pending",
                "user_id" => $parent_id,
                // "feature" => $row["feature"],
                // "brix" => $row["brix"],
                // "packaging" => $row["packaging"],
                "place_of_origin" => $row["place_of_origin"],
                // "model_number" => $row["model_number"],
                // "primary_ingredients" => $row["primary_ingredients"],
                "unlimited_stock" => $unlimited_stock,
                "price_on_request" => $price_on_request,
                "price_negotiable" => $price_negotiable,
                // "additives" => $row["additives"],
                // "flavor" => $row["flavor"],
                // "certification" => $row["certification"],
                // "volume" => $row["volume"],
               // "BBD" => $row["bbd"],
               'pcs_box'    => $row['pcs_box'], 
                'pcs_pallet'    => $row['pcs_pallet'], 
                'box_pallet'    => $row['box_pallet'], 
                'leadtime'    => $row['leadtime'], 
            ]);

            $product_id = $ret->id; //lat inserted product id

           
            // if (!empty($row["thumb"])) {
                
            //     $ret1 = SellerProductImageTemp::create([
            //             "product_id" => $product_id,
            //             "thumbnail" => "yes",
            //             "image_path" => $row["thumb"],
            //         ]);
                
            // }

            $url = $row["gallery"];
            if (!empty($url)) {
                $urls = array_unique(explode(",", $url)) ;
                $i=0;
                foreach ($urls as $url) {
                    
                        if(filter_var($url, FILTER_VALIDATE_URL) === FALSE)
                        {   
                                $validB64 = preg_match("/data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+).base64/", $url); 
                                if($validB64)
                                    continue;
                                else
                                    $url="data:image/jpeg;base64,".$url;
                       }
                       
                       if($i==0)
                        {    
                            $thumbnail= "yes";
                            $i=1;
                        }
                        else
                            $thumbnail = "no";

                        $ret2 = SellerProductImageTemp::create([
                            "product_id" => $product_id,
                            "thumbnail" => $thumbnail,
                            "image_path" => $url,
                        ]);
                    
                }
            }
        }
    }
}
