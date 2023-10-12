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
        $products = Product::orderBy('position')->get();
        return ProductResource::collection($products);
    }

    public function isVisible() {
        $product = Product::where('visibility' , true)->orderBy('position')->get();
        return ProductResource::collection($product);
    }
    public function orderByPosition() {
        $products = Product::orderBy('position', 'asc')->get();
        return ProductResource::collection($products);
    }


    public function store(StoreProductRequest $request)
    {
        $request->validated($request->all());
        $maxPositionInCategory = Product::where('sub_category_id' , $request->sub_category_id)->max('position');
        if (!$maxPositionInCategory){
            $product = Product::create(array_merge($request->all() , ['position' => 1]));
            return ProductResource::make($product);
        }
        if (!$request->position) {
            $product = Product::create(array_merge($request->all() , ['position' => $maxPositionInCategory + 1]));
            return ProductResource::make($product);
        }else {
            if ($request->position == $maxPositionInCategory + 1){
                $product = Product::create($request->all());
                return ProductResource::make($product);
            }

            if ($request->position > $maxPositionInCategory + 1){
                $product = Product::create(array_merge($request->all() , ['position' => $maxPositionInCategory + 1]));
                return ProductResource::make($product);
            }

            if ($request->position == $maxPositionInCategory){
                $maxProduct = Product::where('position' , $maxPositionInCategory)->first();
                $product = Product::create(array_merge($request->all() , ['position' => $maxPositionInCategory]));
                $maxProduct->update([
                    'position' => $maxPositionInCategory + 1
                ]);
                return ProductResource::make($product);
            }

            if ($request->position < $maxPositionInCategory){
                $shouldShiftProducts = Product::where('position' , '>=' , $request->position)->get();
                foreach ($shouldShiftProducts as $shouldShiftProduct){
                    $shouldShiftProduct->update([
                        'position' => $shouldShiftProduct['position'] + 1
                    ]);
                }
                $product = Product::create($request->all());
                return ProductResource::make($product);
            }
        }
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $request->validated($request->all());
        $maxPositionInCategory = Product::where('sub_category_id' , $product->sub_category_id)->max('position');
        if (!$request->position) { // checked
            $product->update($request->all());
            return ProductResource::make($product);
        }
        else {
            if ($request->position == $product->position){ // checked
                $product->update($request->all());
                return ProductResource::make($product);
            }
            else if ($request->position >= $maxPositionInCategory + 1) { // checked
                $productsShouldShift = Product::where('position' , '>' ,$product->position)->get();
                foreach ($productsShouldShift as $productShould) {
                    $productShould->update([
                       'position' => $productShould['position'] - 1
                    ]);
                }
                $product->update(array_merge($request->except('position') , ['position' => $maxPositionInCategory]));
                return ProductResource::make($product);
            }
            else if ($request->position == $maxPositionInCategory){ //checked

                $productsShouldShift = Product::where('position' , '>' ,$product->position)->get();
                foreach ($productsShouldShift as $productShould) {
                    $productShould->update([
                        'position' => $productShould['position'] - 1
                    ]);
                }
                $product->update($request->all());
                return ProductResource::make($product);
            }

            else if ($request->position < $maxPositionInCategory){

                if ($request->position < $product->position){
                    if ($request->position == $product->position - 1){
                        $productShouldReplace = Product::where('position' , $request->position)->first();
                        $productShouldReplace->update([
                            'position' => $product->position
                        ]);
                        $product->update([
                           'position' => $request->position
                        ]);
                        return ProductResource::make($product);
                    }
                    else { //checked
                        $productsShouldShift = Product::whereBetween('position', [$request->position, $product->position - 1])->get();
                        foreach ($productsShouldShift as $productShouldShift) {
                            $productShouldShift->update([
                                'position' => $productShouldShift['position'] + 1
                            ]);
                        }
                        $product->update([
                            'position' => $request->position
                        ]);
                        return ProductResource::make($product);
                    }
                }
                else {
                    if ($request->position == $product->position + 1){ //checked
                        $productShouldReplace = Product::where('position' , $request->position)->first();
                        $productShouldReplace->update([
                            'position' => $product->position
                        ]);
                        $product->update([
                            'position' => $request->position
                        ]);
                        return ProductResource::make($product);
                    }
                    else{
                        $indexToMove = $request->position;
                        $indexMoved = $product->position;
                        $productsShouldShift = Product::where('position' , '>=' ,  $indexToMove)->get();

                        foreach ($productsShouldShift as $poductShould) {
                            $poductShould->update([
                               'position' => $poductShould['position'] + 1
                            ]);
                        }
                        $product->update([
                           'position' => $request->position
                        ]);

                        $productsShouldGoBackShift = Product::where('position' , '>' , $indexMoved)->get();

                        foreach ($productsShouldGoBackShift as $productShouldGoBackShift){
                            $productShouldGoBackShift->update([
                               'position' => $productShouldGoBackShift['position'] - 1
                            ]);
                        }
                        return ProductResource::make($product);
                    }
                }
            }
        }
    }

    public function show(Product $product)
    {
        return ProductResource::make($product);
    }

    public function destroy(Product $product)
    {
        $shouldShiftProducts = Product::where('position' , '>' , $product->position)->get();
        foreach ($shouldShiftProducts as $shouldShiftProduct){
            $shouldShiftProduct->update([
                'position' => $shouldShiftProduct['position'] - 1
            ]);
        }
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
