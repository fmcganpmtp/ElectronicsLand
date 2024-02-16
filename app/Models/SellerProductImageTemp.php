<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerProductImageTemp extends Model
{
    use HasFactory;protected $fillable = ['product_id','thumbnail', 'image_path','created_at','updated_at' ];
    public function SellerProductTemp()
  {
      return $this->belongsTo('App\Models\SellerProductTemp','id','product_id');
  }
 
}
