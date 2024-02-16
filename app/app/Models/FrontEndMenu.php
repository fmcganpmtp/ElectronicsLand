<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontEndMenu extends Model
{
    //use HasFactory; 
     protected $fillable = ['category_id'];


     function Category() {
        return $this->belongsTo('App\Models\Category','category_id','id');
      }

}
