<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageAccount extends Model
{
    protected $fillable = ['no_of_accounts','cost_per_account','status','per_account_offer_price','package_id' ];
}
