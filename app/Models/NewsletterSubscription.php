<?php

namespace App\Models;  

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscription extends Model
{
   // use HasFactory;
   protected $fillable = ['id','email', 'newsletter_status', 'created_at', 'updated_at'];
}
