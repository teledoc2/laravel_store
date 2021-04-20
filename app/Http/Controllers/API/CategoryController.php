<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;


class CategoryController extends Controller
{

    //
    public function index(Request $request){
        return CategoryResource::collection(Category::active()->get());
    }



}
