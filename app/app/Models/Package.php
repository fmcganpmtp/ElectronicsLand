<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    // use HasFactory;
    //protected $fillable = ['name','subscription_type', 'no_of_accounts','status','cost_per_account','per_account_offer_price','account_offer_price','subcription_type','user_type', 'package_basic_price','package_offer_price','package_validity','package_description' ];
    protected $fillable = ['name', 'subscription_type', 'user_type', 'package_basic_price', 'package_offer_price', 'package_validity', 'package_description', 'general_companyProfile', 'general_companyDashboard', 'general_profileInsights', 'general_accesToMedia', 'market_Search', 'market_uploads', 'market_productRequests', 'market_createProductRequests', 'network_expand', 'network_chat', 'status'];
    

    function IncludedFeatures() {
        return $this->hasMany('App\Models\IncludedFeature');

    }
    function ExcludedFeatures() {
        return $this->hasMany('App\Models\ExcludedFeatures');
    }

    function PackageAccount() {
        return $this->hasMany('App\Models\PackageAccount');
    }

    

    
    

}
