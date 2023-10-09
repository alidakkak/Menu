<?php

namespace App\Http\Controllers;

use App\Http\Requests\StroeFeatureRequest;
use App\Http\Resources\FeatureResource;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Image;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index()
    {
        $feature = Feature::with('image')->get();
        return $feature;
//        return FeatureResource::collection($feature);
    }

    public function getFeatureByCategory($categoryID) {
        $category = Category::where('id', $categoryID)
            ->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $feature = $category->feature;
        return response()->json($feature);
    }


    public function store(StroeFeatureRequest $request) {
        $request->validated($request->all());
        $images = $request->image;
        $feature = Feature::create($request->all());
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                Image::create([
                    'feature_id' => $feature->id,
                    'image' => $image
                ]);
            }
        }
        return FeatureResource::make($feature);
    }

    public function destroy(Feature $feature){
        $feature->delete();
        return response()->json([
            'message' => 'Deleted Successfully From Our System',
        ], 201);
    }
}
