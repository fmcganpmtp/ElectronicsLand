<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
	protected $guarded = [];
	public function SellerProduct()
    {
    return $this->belongsTo('App\Models\SellerProduct','id','currency_id');
    }
	public function Product()
    {
    return $this->belongsTo('App\Models\Product','id','currency_id');
    }
}
