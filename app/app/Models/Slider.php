<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','show_home'
    ];
function Sliderimage() {
        return $this->hasMany('App\Models\Sliderimage','slider_id','id');
}

}
