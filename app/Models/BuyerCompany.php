<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerCompany extends Model 
{   
    
    protected $fillable = ['user_id','profile_keywords','company_name','registration_number','company_type','company_street','company_zip','tax_reg_no','about_company','registration_no','company_location','company_land','opening_hours', 'company_image','deliver_options','languages_speak','accepted_payments','gst_no','registration_no' ];
   
function SellerOpeningTime() {
            return $this->hasMany('App\Models\SellerOpeningTime','seller_id','user_id');
        }
    
}
