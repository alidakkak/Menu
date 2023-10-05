<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
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
        $category = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $request->image,
            'price' => $request->price,
            'calories' => $request->calories,
            'visibility' => $request->visibility,
            'position' => $request->position,
            'category_id' => $request->category_id
        ]);
        return ProductResource::make($category);
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

}
