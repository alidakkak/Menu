<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('feature.image')->get();
        return $categories;
    }

    public function isVisible() {
        $category = Category::where('visibility' , true)->get();
        return CategoryResource::collection($category);
    }

    public function store(StoreCategoryRequest $request)
    {
        $request->validated($request->all());
        $category = Category::create($request->all());
        $category->update([
            'position' => $category->id
        ]);
        return CategoryResource::make($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $request->validated($request->all());
        $category->update($request->all());
        return CategoryResource::make($category);
    }

    public function show(Category $category)
    {
        return $category->feature()->with('image')->get();
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return 'One Category Deleted Successfully';
    }

    public function switchCategory(Category $category) {
        $category->update([
           'visibility' => ! boolval($category->visibility)
        ]);
        return 'Updated SuccessFully';
    }

    public function numberOfCategory()
    {
        $number = Category::count();
        return $number;
    }


}
