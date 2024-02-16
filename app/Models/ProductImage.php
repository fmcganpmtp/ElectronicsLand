<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
  //  use HasFactory;
  protected $fillable = ['product_id','thumbnail', 'image_path','created_at','updated_at' ];
  public function products()
{
    return $this->belongsTo('App\Models\Product','id','product_id');
}
}
