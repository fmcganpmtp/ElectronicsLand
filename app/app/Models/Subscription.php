<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //use HasFactory;

    protected $fillable = [
        'user_id','package_id','type','date','expairy_date','order_id','suspend_start_date','suspend_end_date','staus','order_total','auto_renewal'
    ];
    public function Package(){
        return $this->belongsTo('App\Models\Package', 'package_id', 'id');
    }
    public function OrderDetail(){
        return $this->belongsTo('App\Models\OrderDetail', 'order_id', 'id');
    }

}
