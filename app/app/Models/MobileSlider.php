<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileSlider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','show_home'
    ];
function MobileSliderimage() {
        return $this->hasMany('App\Models\MobileSliderimage','slider_id','id');
}

}
