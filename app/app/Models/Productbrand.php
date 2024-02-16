<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productbrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'image'
       ];
       
    public function getNameAttribute($value)
{
    return ucfirst($value);
}
}
