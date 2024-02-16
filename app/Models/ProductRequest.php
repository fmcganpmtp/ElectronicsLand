<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    use HasFactory;
    
    
    
    protected $fillable = ['user_id','category_id' ,'country_id','product_description','parent_id','seller_type','quantity','language','extend_by_date','prod_img','req_date','req_end_date'];
        function Country() {
        return $this->hasOne('App\Models\Country','id','country_id'); }
        function User() {
        return $this->hasOne('App\Models\User','id','user_id'); }
        function Category() {
        return $this->hasOne('App\Models\Category','id','category_id'); }
}
