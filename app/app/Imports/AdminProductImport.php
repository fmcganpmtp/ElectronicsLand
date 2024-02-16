<?php
namespace App\Imports;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Productbrand;
use App\Models\Currency;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;
use App\Models\Country;
use Illuminate\Validation\Rule;
use Storage;
use DB;

    class AdminProductImport implements ToCollection, WithHeadingRow
    {
        /**
        * @param array $row
        *
        * @return \Illuminate\Database\Eloquent\Model|null
        */
        
         public function create_slug($string)
        {
            $items = array("index", "create_slug", "show", "create", "store", "edit", "update", "destroy");
            $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
            if (in_array($slug, $items)) 
                $slug = $slug . date('ymd').time();
            return $slug;
        }
    public function grab_image($url,$saveto,$i){
		
	$info = pathinfo($url);
    $img = $info["basename"];
    $image_path = 'admin_prd'.$i.date("YmdhisU") . "_" . $img;
    $saveto_path = $saveto.$image_path;
		
	$ch = curl_init ($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
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
        public function collection(Collection $rows)
        {
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
            $validator= Validator::make($rows->toArray(), [
                "*.product_description" => ["required"],
                              "*.categories" => ["required"],

                //'*.name' =>  ['required', 'string',Rule::unique('products')],
             //    '*.product_price' => ['required', 'numeric'],
               //  '*.sku' => ['required', 'string',Rule::unique('products')],
              ]);

              if ($validator->fails()) { 
                return back()->withErrors($validator) 
                    ->withInput();
            }      


            
foreach ($rows as $row) {
    
    
    
    
$prd_ids = $vartnts= $ctrys=  $cats = array();   
// Categories Insert section below
if(isset($row['categories'])) { 
    $NewCategories = explode(',', $row['categories']); 
      
foreach($NewCategories as $NewCategory)
{
       
        if (str_contains($NewCategory, '>')) 
        {
                $parents = explode('>', $NewCategory);
                $prevperent=null;
                foreach($parents as $data)
                { 

                   $cats1 = Category::where(DB::raw('lower(name)'),strtolower($data))->pluck('id')->first();
        
                    if($cats1)  
                      $prevperent= $cats1;
                    else
                    {
                        $catg1['name'] = $data; 
                        $catg1['parent_id'] = $prevperent;
                        $seo_url = $this->create_slug($data);
                        $catg1['slug'] = $seo_url; 
                        $catCreate = Category::create($catg1); 
                        $prevperent=$catCreate->id;
                    }
                   
                }
                 array_push($cats,$prevperent);
                 
        }
        else
        {
                $cats1 = Category::where(DB::raw('lower(name)'),strtolower($row['categories']))->pluck('id')->first(); 
        
                if($cats1)  
                    array_push($cats,$cats1);
                else
                {
                    $seo_url = $this->create_slug($NewCategory);
                    $catg1['name'] = $NewCategory; 
                    $catg1['parent_id'] = null; 
                    $catg1['slug'] = $seo_url; 
                    $catCreate = Category::create($catg1); 
                    array_push($cats,$catCreate->id);
                }
        }
}
$CatList = implode(',', $cats); 
}  
if(!empty($CatList)) 
    $categoriesNew= $CatList;
else 
    $categoriesNew= "";
    
    
    
    
    // Brand Insert section below
if(isset($row['brands'])) { 
    $brand_data=Productbrand::where(DB::raw('lower(name)'),strtolower($row['brands']))->pluck('id')->first(); 
    if(!empty($brand_data))
      $brand_id=$brand_data;
    else{
        $newbrand=array('name'=>$row['brands']);
        $brand_data=Productbrand::create($newbrand);
        $brand_id=$brand_data->id;
    }
}  
else
     $brand_id= "";

      // Currency Insert section below
if(isset($row['currency'])) { 

    $currency_data=Currency::where(DB::raw('lower(symbol)'),strtolower($row['currency']))->pluck('id')->first(); 
	
    if(!empty($currency_data)) { 
	$currency=$currency_data; }
    else{
        $newcurrency=array('symbol'=>$row['currency']);
        $currency_data=Currency::create($newcurrency);
        $currency=$currency_data->id;
    }
}  
else
     $currency= "";

// Coutries Insert section below

// if(isset($row['countries'])) { 
//     $NewCountries = explode(',', $row['countries']); 
   
   
// foreach($NewCountries as $NewCountry){
    
// $ctrys1 = Country::where(DB::raw('lower(name)'),strtolower($NewCountry))->pluck('id')->first();

// if($ctrys1)  array_push($ctrys,$ctrys1);
// else{
//     $newCty['name'] = $NewCountry; 
//     $catCreate = Country::create($newCty); 
//     array_push($ctrys,$catCreate->id);
// }
// }
// $CountryList = implode(', ', $ctrys); 
// }  
// if(!empty($CountryList)) $NewCountries= $CountryList;
// else $NewCountries= "";

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

if(isset($row['bbd_expiry_date'])) {      
            $date = date_parse($row['bbd_expiry_date']);
            if ($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"]))
            $product_expiry = date('Y-m-d', strtotime($row['bbd_expiry_date']));
		    else
            $product_expiry =null; 
			 }
            else
            $product_expiry =null;  
            
   $product_price=null;
        if (isset($row["price"])) {
            $product_price  = $row["price"];
		    $product_price = (float) str_replace(',', '', $product_price)   ;
		    $product_price = round($product_price,3);
        }          
            
	   
       $ret =  Product::create([
            'name'     => $row['product_description'],
            'currency_id'    => $currency, 
            'product_price'    => $product_price, 
            'SKU'    => $row["sku"], 
            'EAN_GTIN'    => $row["ean_gtin"], 
            'batch'    => $row["batch"], 
            'stock_count'    => $row['avialable_stock'], 
            'category_id'   => $categoriesNew, 
            // 'available_countries'   => $NewCountries, 
            // 'product_color'    => $row['product_color'],
            "label_language" => $row["label_language"],
            'product_weight'    => $row['product_weight_size'], 
           // 'product_size'    => $row['product_size'], 
            // 'product_dimension'    => $row['product_dimension'],
            // 'feature'    => $row['feature'], 
            // 'brix'    => $row['brix'], 
            // 'packaging'    => $row['packaging'], 
            'place_of_origin'    => $row['place_of_origin'], 
            // 'model_number'    => $row['model_number'], 
            // 'primary_ingredients'    => $row['primary_ingredients'], 
            'unlimited_stock'    => $unlimited_stock,
            'price_on_request'    => $price_on_request,
            'price_negotiable'    => $price_negotiable,
            // 'additives'    => $row['additives'], 
            // 'flavor'    => $row['flavor'], 
            // 'certification'    => $row['certification'], 
            // 'volume'    => $row['volume'], 
            // 'BBD'    => $row['bbd'], 
            'product_description'    => $row['description'],
            'location'    => $row['stock_location'],
            'minimal_order'    => $row['minimal_order_quantity'], 
            'product_condition'    => $row['product_condition'], 
            'pcs_box'    => $row['pcs_box'], 
            'pcs_pallet'    => $row['pcs_pallet'], 
            'box_pallet'    => $row['box_pallet'], 
            'leadtime'    => $row['leadtime'], 
            //'varients_skus'    => $row['varients'],
            'product_expiry'  => $product_expiry,
            'brands'    => $brand_id,
            
        ]);


        $product_id = $ret->id; //lat inserted product id
        array_push($prd_ids,$product_id);

// Varients Insert section below
// if(isset($row['varients'])) {
//     $varients = explode(',', $row['varients']); 
    
   
//     foreach($varients as $varient){ 
//     $vartnts1 = Product::where('SKU', $varient)->pluck('id')->first(); 
    
//     if($vartnts1)  array_push($vartnts,$vartnts1);
    
//     }
//     $varientList = implode(', ', $vartnts); 
//     }  
//     if(!empty($varientList)) $NewVarients= $varientList;
//     else $NewVarients= ""; 
	
// 	Product::where('id', $product_id)
//       ->update([
//           'variants' => $NewVarients
//         ]);
    
       

            // $url = $row["thumb"];
            // if (!empty($row["thumb"])) {
            //     $file = "uploads/productImages/";
                
            //     if(filter_var($row["thumb"], FILTER_VALIDATE_URL) === FALSE)
            //         {   
            //             $validB64 = preg_match("/data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+).base64,.*/", $row["thumb"]); 
            //             if($validB64)
            //             {//if base64 image convert to image
            //                 $imageInfo = explode(";base64,", $row["thumb"]);
            //                 $imgExt = str_replace('data:image/', '', $imageInfo[0]);      
            //                 $image = str_replace(' ', '+', $imageInfo[1]);
            //                 $destinationPath = public_path() . '/uploads/productImages/' . time().$ret->id .'.'.$imgExt;
            //                  \File::put($destinationPath, base64_decode($image));
            //                 $image_path=time().$ret->id .'.'.$imgExt;
            //             }
            //             else
            //                 $image_path=$url;
                        
            //         }
            //         else
            //         { 
            //             $encode_path=rawurldecode($row["thumb"]);
                        
            //             $handle = @fopen($encode_path, 'r');
            //             // Check if file exists
            //             if($handle) 
            //             {
            //                  //$ext = pathinfo(parse_url($encode_path)['path'], PATHINFO_EXTENSION);	
            //                  //if(in_array($ext, $imageExtensions))
            //                     $image_path =  $this->grab_image($encode_path,$file);
            //                  //else
            //                  //$image_path='';
            //             }
            //             else
            //                 $image_path='';
                        
            //         }

            //         $ret1 = ProductImage::create([
            //             "product_id" => $product_id,
            //             "thumbnail" => "yes",
            //             "image_path" => $image_path,
            //         ]);
                
            // }
            
            $file = "uploads/productImages/";

            $url = $row["gallery"];
            if (!empty($url)) {
                $urls = array_unique(explode(",", $url)) ;

                $i=0;
                foreach ($urls as $url1) {
                    
                   if(filter_var($url1, FILTER_VALIDATE_URL) === FALSE)
                    {   
                        
                         $validB64 = preg_match("/data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+).base64/", $url1); 
                         if($validB64)
                                    continue;
                          else
                                    $url1="data:image/jpeg;base64,".$url1;
                            
                            //if base64 image convert to image
                            $imageInfo = explode(";base64,", $url1);
                            $imgExt = str_replace('data:image/', '', $imageInfo[0]);      
                            $image = str_replace(' ', '+', $imageInfo[1]);
                            $image_name='admin_prd'. date("YmdhisU").$ret->id .'.'.$imgExt;
                            $destinationPath = public_path() . '/uploads/productImages/' .$image_name;
                             \File::put($destinationPath, base64_decode($image));
                            $image_path=$image_name;
                        
                
                        
                    }
                    else
                    { 
                        $encode_path=rawurldecode($url1);
                        
                        $handle = @fopen($encode_path, 'r');
                        // Check if file exists
                        if($handle) 
                        {
                             //$ext = pathinfo(parse_url($encode_path)['path'], PATHINFO_EXTENSION);	
                             //if(in_array($ext, $imageExtensions))
                                $image_path =  $this->grab_image($encode_path,$file,$i);
                             //else
                             //$image_path='';
                        }
                        else
                            $image_path='';
                        
                    }
                    
                    if($i==0)
                        $thumbnail= "yes";
                    else
                        $thumbnail = "no";

                     $i++;
                        $ret2 = ProductImage::create([
                            "product_id" => $product_id,
                            "thumbnail" => $thumbnail,
                            "image_path" => $image_path,
                        ]);
                    
                }
            }

}
			
			
			
// foreach($prd_ids as $prd_id)	{ echo $prd_id;
// 	$vartntskus = Product::where('id', $prd_id)->pluck('varients_skus')->first(); 
// 	$varients = explode(',', $vartntskus); 
// 	$vartnts1 =  ""; $vartnts = [];		
// 	foreach($varients as $varient){ 
//     $vartnts1 = Product::where('SKU', $varient)->pluck('id')->first(); 
//     if($vartnts1)  array_push($vartnts,$vartnts1);
//      }
//     $varientList = implode(', ', $vartnts); 
// 	if(!empty($varientList)) $NewVarients= $varientList;
//     else $NewVarients= ""; 	
//     Product::where('id', $prd_id)
//         ->update([
//           'variants' => $NewVarients
//         ]);
//     } 
    
		

			
        }





}
