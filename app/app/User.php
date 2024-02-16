<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
//use Illuminate\Auth\Passwords\CanResetPassword;
use App\Http\Middleware\TrustHosts;
    use Illuminate\Foundation\Auth\User as Authenticatable;

    class User extends Authenticatable
    {
        use Notifiable;

        protected $guard = 'user';

        protected $fillable = [
            'name', 'email','order_total', 'password','username','phone','profile_pic','about','hide_promo_email','surname','position','country_id','usertype','company','address','seller_type','parent_id','token_number'
        ];

        protected $hidden = [
            'password', 'remember_token',
        ];
        function BuyerCompany() {
            return $this->hasOne('App\Models\BuyerCompany')->withDefault();
        }
        function OrderDetail() {
            return $this->hasMany('App\Models\OrderDetail','user_id','id');
        }
        function SellerMessageUser() {
            return $this->hasMany('App\Models\SellerMessage','user_id','id');
        }
        function SellerMessageSeller() {
            return $this->hasMany('App\Models\SellerMessage','seller_id','id');
        }
        function Subscription() {
            return $this->hasMany('App\Models\Subscription','user_id','id');
        }
        function KycFile() {
            return $this->hasMany('App\Models\KycFile','user_id','id');
        }
        function SellerProduct() {
            return $this->hasMany('App\Models\SellerProduct','user_id','id');
        }
        function SellerOpeningTime() {
            return $this->hasMany('App\Models\SellerOpeningTime','seller_id','id');
        }

        function ProductReview() {
            return $this->hasMany('App\Models\ProductReview','user_id','id');
        }
        function Country() {
        return $this->hasOne('App\Models\Country','id','country_id');
}
        
    }