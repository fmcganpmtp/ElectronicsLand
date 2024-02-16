<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'parent_id', 'category_pic'];

    
    public function FrontEndMenu()
{
    return $this->hasMany('App\Models\FrontEndMenu','category_id','id');
}
public function getNameAttribute($value)
{
    return ucfirst($value);
}
    
    public function subcategory()
    {
        return $this->hasMany(\App\Models\Category::class, 'parent_id')->orderby('name','asc');
    }
    
    public function sub()
    {
        return $this->hasMany(Category::class, 'parent_id')->where('have_product','Yes')
        ->select('id','name', 'slug as link','parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(\App\Models\Category::class, 'parent_id');
    }
    public function parentCategory(){
        return $this->hasMany($this, 'parent_id');
    }
    public function getParentsAttribute()
{
    $parents = collect([]);

    $parent = $this->parent;

    while(!is_null($parent)) {
        $parents->push($parent);
        $parent = $parent->parent;
    }

    return $parents;
}


public function getParentsNames() {

    $parents = collect([]);

    if($this->parent) { 
        $parent = $this->parent;
        while(!is_null($parent)) {
            $parents->push($parent);
            $parent = $parent->parent;
        }
        return $parents;
    } else {
        //return $this->name;
    }

}



}