<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Permission extends Model
{
    //use HasFactory;
    protected $fillable = [ 'name','slug'] ;
    protected $guarded = [];
}
