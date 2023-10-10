<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subcategories = SubCategory::with('product',)->get();
        return $subcategories;
    }
    public function isVisible() {
        $subcategories = SubCategory::where('visibility' , true)->get();
        return $subcategories;
    }
    public function store(StoreSubCategoryRequest $request)
    {
        $request->validated($request->all());
        $subcategory = SubCategory::create($request->all());
        $subcategory->update([
            'position' => $subcategory->id
        ]);
        return $subcategory;
    }

    public function update(UpdateSubCategoryRequest $request, SubCategory $subcategory)
    {
        $request->validated($request->all());
        $subcategory->update($request->all());
        return $subcategory;
    }

    public function show(SubCategory $subcategory)
    {
        return $subcategory;
    }

    public function destroy(SubCategory $subcategory)
    {
        $subcategory->delete();
        return 'One Category Deleted Successfully';
    }

    public function switchSubCategory(SubCategory $subcategory) {
        $subcategory->update([
            'visibility' => ! boolval($subcategory->visibility)
        ]);
        return 'Updated SuccessFully';
    }
}
