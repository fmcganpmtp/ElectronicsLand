<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerProductImage extends Model
{
    use HasFactory;protected $fillable = ['product_id','thumbnail', 'image_path','created_at','updated_at' ];
    public function SellerProduct()
  {
      return $this->belongsTo('App\Models\SellerProduct','id','product_id');
  }
 
}
