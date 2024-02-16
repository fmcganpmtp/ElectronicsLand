<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //use HasFactory;
    protected $fillable = ['name','BBD','volume','price_on_request','price_negotiable','label_language','unlimited_stock','certification','flavor','additives','primary_ingredients','model_number','place_of_origin','packaging','feature','brix','product_price', 'category_id','currency_id','SKU','stock_count','product_color','product_weight','product_size', 'product_dimension','variants','available_countries','product_description','company_name','location','varients_skus','brands','product_expiry','minimal_order','product_condition','leadtime','pcs_box','pcs_pallet','box_pallet','EAN_GTIN','batch'];
    function ProductImages() {
        return $this->hasMany('App\Models\ProductImage','product_id','id');

    }
    function Productbrand() {
        return $this->hasOne('App\Models\Productbrand','id','brands');
    }
    function BusinessInsight() {
        return $this->hasMany('App\Models\BusinessInsight','product_id','id');
    }
	function Currency() {
        return $this->hasOne('App\Models\Currency','id','currency_id');
    }
  /*  public function getProductPriceAttribute($value)
{
    
    number_format($value);
}*/
}
