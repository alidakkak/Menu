<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return ProductResource::collection($products);
    }

    public function isVisible() {
        $product = Product::where('visibility' , true)->get();
        return ProductResource::collection($product);
    }
    public function orderByPosition() {
        $products = Product::orderBy('position', 'asc')->get();
        return ProductResource::collection($products);
    }


    public function store(StoreProductRequest $request)
    {
        $request->validated($request->all());
        $product = Product::create($request->all());
        $product->update([
            'position' => $product->id
        ]);
        return ProductResource::make($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $request->validated($request->all());
        $product->update($request->all());
        return ProductResource::make($product);
    }

    public function show(Product $product)
    {
        return ProductResource::make($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return 'One Product Deleted Successfully';
    }

    public function switchProduct(Product $product) {
        $product->update([
            'visibility' => ! boolval($product ->visibility)
        ]);
        return 'Updated SuccessFully';
    }


    public function getProduct($subcategoryName)
    {
        $subCategory = SubCategory::where('name', $subcategoryName)
            ->where('visibility', true)
            ->first();

        if (!$subCategory) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $subCategory->loadCount('product');

        $products = $subCategory->product->each(function ($product, $index) {
            $product->update(['position' => $index + 1]);
        });

        return response()->json($products);
    }


}
