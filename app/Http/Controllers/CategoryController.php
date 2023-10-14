<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Product;
use App\Models\PublicFeature;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('feature','subcategory.product')->get();
        return $categories;
    }

    public function isVisible() {
        $category = Category::with('feature','subcategory.product')->where('visibility' , true)->get();
        return $category;
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
        $all = $category::with('subCategory.product', 'feature')->orderBy('position')->find($category->id);
        return $all;
    }


    public function showsome(Category $categoryID)
    {
        $category = $categoryID->with([
            'subCategory' => function ($query) {
                $query->where('visibility', true)->orderBy('position');
            },
            'subCategory.product' => function ($query) {
                $query->where('visibility', true)->orderBy('position');
            },
            'feature'
        ])->where('visibility', true)->orderBy('position')->find($categoryID->id);

        return $category;
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

    public function numberAll()
    {
        $numberOfCategory = Category::count();
        $numberOfSubCategory = SubCategory::count();
        $numberOfProduct = Product::count();
        $numberOfFeature = Feature::count();
        $numberPublicFeature = PublicFeature::count();
        return [
            'NumberOfCategory' => $numberOfCategory,
            'NumberOfSubCategory' => $numberOfSubCategory,
            'NumberOfProduct' => $numberOfProduct,
            'NumberOfFeature' => $numberOfFeature,
            'NumberPublicFeature' => $numberPublicFeature
        ];
    }

    public function getAllByCategory($categoryName)
    {
        $Category = Category::where('name', $categoryName)
            ->where('visibility', true)
            ->first();

        if (!$Category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $Category->loadCount('subCategory');
        $Category->loadCount('product');

        $products = $Category->product->each(function ($product, $index) {
            $product->update(['position' => $index + 1]);
        });

        return response()->json($products);
    }


}
