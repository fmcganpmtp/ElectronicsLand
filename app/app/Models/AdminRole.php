<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    use HasFactory;
    protected $fillables = ['role_name', 'permission'];
    protected $guarded = [];

    public function user()
{
    return $this->belongsTo(User::class);
}
}
