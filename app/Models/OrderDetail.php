<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
  //  use HasFactory;
  //protected $fillables = ['name', 'email', 'phone', 'address', 'city', 'zip', 'country'];
  protected $guarded = []; 
  function Countries() {
    return $this->hasOne('App\Models\Country','id','country');
}
function Package() {
    return $this->hasOne('App\Models\Package','id','package_id');
}
function Subscription() {
  return $this->hasOne('App\Models\Subscription','order_id','id');
}
function User() {
  return $this->belongsTo('App\User','id','user_id');
}




}
