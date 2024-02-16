<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
   // use HasFactory;

   protected $fillable = ['id','star_rating','user_id', 'product_id','name','email','review_title','review','status' ];
   function User() {
    return $this->belongsTo('App\User','user_id','id');
  }

}
