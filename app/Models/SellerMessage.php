<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerMessage extends Model
{
   // use HasFactory;
   protected $fillable = ['id','product_id','user_id', 'seller_id','name','email','phone','message' ];
   function User() {
    return $this->belongsTo('App\User','user_id','id');
  }
  function Seller() {
    return $this->belongsTo('App\User','seller_id','id');
  }
}
