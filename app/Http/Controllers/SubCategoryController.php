<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subcategories = SubCategory::with('product')->orderBy('position')->get();
        return SubCategoryResource::collection($subcategories);
    }
    public function isVisible() {
        $subcategories = SubCategory::where('visibility' , true)->get();
        return $subcategories;
    }
    public function store(StoreSubCategoryRequest $request)
    {
            $request->validated($request->all());
            $maxPositionInCategory = SubCategory::where('category_id', $request->category_id)->max('position');
            if (!$maxPositionInCategory) {
                $product = SubCategory::create(array_merge($request->all(), ['position' => 1]));
                return SubCategoryResource::make($product);
            }
            if (!$request->position) {
                $product = SubCategory::create(array_merge($request->all(), ['position' => $maxPositionInCategory + 1]));
                return SubCategoryResource::make($product);
            }
            else {
                if ($request->position == $maxPositionInCategory + 1) {
                    $product = SubCategory::create($request->all());
                    return SubCategoryResource::make($product);
                }

                if ($request->position > $maxPositionInCategory + 1) {
                    $product = SubCategory::create(array_merge($request->all(), ['position' => $maxPositionInCategory + 1]));
                    return SubCategoryResource::make($product);
                }

                if ($request->position == $maxPositionInCategory) {
                    $maxProduct = SubCategory::where('category_id' , $request->category_id)->where('position', $maxPositionInCategory)->first();
                    $product = SubCategory::create(array_merge($request->all(), ['position' => $maxPositionInCategory]));
                    $maxProduct->update([
                        'position' => $maxPositionInCategory + 1
                    ]);
                    return SubCategoryResource::make($product);
                }

                if ($request->position < $maxPositionInCategory) {
                    $shouldShiftProducts = SubCategory::where('category_id' , $request->category_id)->where('position', '>=', $request->position)
                        ->get();
                    foreach ($shouldShiftProducts as $shouldShiftProduct) {
                        $shouldShiftProduct->update([
                            'position' => $shouldShiftProduct['position'] + 1
                        ]);
                    }
                    $product = SubCategory::create($request->all());
                    return SubCategoryResource::make($product);
                }
            }
    }

    public function update(UpdateSubCategoryRequest $request, SubCategory $subcategory)
    {
        $request->validated($request->all());
        $maxPositionInCategory = SubCategory::where('category_id' , $subcategory->category_id)->max('position');
        if (!$request->position) { // checked
            $subcategory->update($request->all());
            return SubCategoryResource::make($subcategory);
        }
        else {
            if ($request->position == $subcategory->position){ // checked
                $subcategory->update($request->all());
                return SubCategoryResource::make($subcategory);
            }
            else if ($request->position >= $maxPositionInCategory + 1) { // checked
                $productsShouldShift = SubCategory::where('category_id' , $subcategory->category_id)->where('position' , '>' ,$subcategory->position)->get();
                foreach ($productsShouldShift as $productShould) {
                    $productShould->update([
                        'position' => $productShould['position'] - 1
                    ]);
                }
                $subcategory->update(array_merge($request->except('position') , ['position' => $maxPositionInCategory]));
                return SubCategoryResource::make($subcategory);
            }
            else if ($request->position == $maxPositionInCategory){ //checked

                $productsShouldShift = SubCategory::where('category_id' , $subcategory->category_id)->where('position' , '>' ,$subcategory->position)->get();
                foreach ($productsShouldShift as $productShould) {
                    $productShould->update([
                        'position' => $productShould['position'] - 1
                    ]);
                }
                $subcategory->update($request->all());
                return SubCategoryResource::make($subcategory);
            }

            else if ($request->position < $maxPositionInCategory){

                if ($request->position < $subcategory->position){
                    if ($request->position == $subcategory->position - 1){
                        $productShouldReplace = SubCategory::where('category_id' , $subcategory->category_id)->where('position' , $request->position)->first();
                        $productShouldReplace->update([
                            'position' => $subcategory->position
                        ]);
                        $subcategory->update([
                            'position' => $request->position
                        ]);
                        return SubCategoryResource::make($subcategory);
                    }
                    else { //checked
                        $productsShouldShift = SubCategory::where('category_id' , $subcategory->category_id)->whereBetween('position', [$request->position, $subcategory->position - 1])->get();
                        foreach ($productsShouldShift as $productShouldShift) {
                            $productShouldShift->update([
                                'position' => $productShouldShift['position'] + 1
                            ]);
                        }
                        $subcategory->update([
                            'position' => $request->position
                        ]);
                        return SubCategoryResource::make($subcategory);
                    }
                }
                else {
                    if ($request->position == $subcategory->position + 1){ //checked
                        $productShouldReplace = SubCategory::where('category_id' , $subcategory->category_id)->where('position' , $request->position)->first();
                        $productShouldReplace->update([
                            'position' => $subcategory->position
                        ]);
                        $subcategory->update([
                            'position' => $request->position
                        ]);
                        return SubCategoryResource::make($subcategory);
                    }
                    else{
                        $indexToMove = $request->position;
                        $indexMoved = $subcategory->position;
                        $productsShouldShift = SubCategory::where('category_id' , $subcategory->category_id)->where('position' , '>=' ,  $indexToMove)->get();

                        foreach ($productsShouldShift as $poductShould) {
                            $poductShould->update([
                                'position' => $poductShould['position'] + 1
                            ]);
                        }
                        $subcategory->update([
                            'position' => $request->position
                        ]);

                        $productsShouldGoBackShift = SubCategory::where('category_id' , $subcategory->category_id)->where('position' , '>' , $indexMoved)->get();

                        foreach ($productsShouldGoBackShift as $productShouldGoBackShift){
                            $productShouldGoBackShift->update([
                                'position' => $productShouldGoBackShift['position'] - 1
                            ]);
                        }
                        return SubCategoryResource::make($subcategory);
                    }
                }
            }
        }
    }

    public function show(SubCategory $subcategory)
    {
        return $subcategory;
    }

    public function destroy(SubCategory $subcategory)
    {
        $shouldShiftSubCategory = SubCategory::where('category_id' , $subcategory->category_id)->where('position' , '>' , $subcategory->position)->get();
        foreach ($shouldShiftSubCategory as $shouldShiftSubCategor){
            $shouldShiftSubCategor->update([
                'position' => $shouldShiftSubCategor['position'] - 1
            ]);
        }
        $subcategory->delete();
        return 'One SubCategory Deleted Successfully';
    }

    public function switchSubCategory(SubCategory $subcategory) {
        $subcategory->update([
            'visibility' => ! boolval($subcategory->visibility)
        ]);
        return 'Updated SuccessFully';
    }


}
