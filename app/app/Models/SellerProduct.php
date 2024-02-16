<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerProduct extends Model
{
    protected $fillable = ['name','BBD','special_season','volume','country_ids','label_language','price_on_request','price_negotiable','unlimited_stock','certification','flavor','additives','primary_ingredients','model_number','place_of_origin','packaging','feature','brix','user_id','currency_id','product_price', 'category_id','admin_productId','SKU','status','stock_count','product_color','product_weight','product_size', 'product_dimension','variants','available_countries','product_description','varients_skus','company_name','location','brands','minimal_order','product_condition','product_expiry','featured_product','product_visibility','leadtime','pcs_box','pcs_pallet','box_pallet','EAN_GTIN','batch'];
    function SellerProductImage() {
        return $this->hasMany('App\Models\SellerProductImage','product_id','id');

    }
    public function getNameAttribute($value)
{
    return ucfirst($value);
}
    
    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    
    public function User1()
    {
        return $this->hasOne('App\Models\User','user_id','id');
    }

    function Productbrand() {
        return $this->hasOne('App\Models\Productbrand','id','brands');
    }
	function Currency() {
        return $this->hasOne('App\Models\Currency','id','currency_id');
    }
    
}
