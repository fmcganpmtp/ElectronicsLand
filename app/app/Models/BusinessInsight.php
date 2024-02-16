<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessInsight extends Model
{
    protected $fillable = [
        'user_id', 'ip_address', 'product_id','profile_id','visited_at','category_id'
    ];
}
