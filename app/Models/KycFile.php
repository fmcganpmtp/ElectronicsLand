<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycFile extends Model
{
   // use HasFactory;
   protected $fillable = ['user_id','file_type', 'file_path','status','reason'];
}
