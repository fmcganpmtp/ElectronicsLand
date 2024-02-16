<?php

namespace App\Http\Controllers\FrontEnd;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\User;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function listCategories(Request $request) {
           $categories = Category::where('parent_id', null)->latest();
           return view('frontEnd.categories.list-categories',compact('categories'));
    }
}
