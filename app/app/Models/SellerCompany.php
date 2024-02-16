<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerCompany extends Model
{   
    protected $fillable = ['name','user_id', 'company_name','company_street','company_zip','company_location','company_land','opening_hours', 'company_image','deliver_options','languages_speak','accepted_payments','gst_no','registration_number' ];
   
}
