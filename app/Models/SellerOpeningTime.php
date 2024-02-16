<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerOpeningTime extends Model
{
    
    protected $fillable = ['seller_id','day','opening_time','closing_time','Open_am_pm','Close_am_pm','closed'];
    //use HasFactory;
}
