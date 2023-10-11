<?php

namespace App\Http\Controllers;

use App\Http\Requests\StroeFeatureRequest;
use App\Http\Resources\FeatureResource;
use App\Models\Feature;
use App\Models\Image;
use App\Models\PublicFeature;
use App\Models\PublicImage;
use Illuminate\Http\Request;

class PublicFeatureController extends Controller
{
    public function index()
    {
        $feature = PublicFeature::all();
        return $feature;
    }

//    public function store(Request $request) {
//       // $request->validated($request->all());
//        $images = $request->image;
//        $feature = PublicFeature::create($request->all());
//        if ($request->hasFile('images')) {
//            $images = $request->file('images');
//            foreach ($images as $image) {
//                PublicImage::create([
//                    'public_feature_id' => $feature->id,
//                    'image' => $image
//                ]);
//            }
//        }
//        return $feature;
//    }

    public function store(Request $request) {
        $feature = PublicFeature::create($request->all());
        return $feature;
    }
    public function destroy(PublicFeature $feature){
        $feature->delete();
        return response()->json([
            'message' => 'Deleted Successfully From Our System',
        ], 201);
    }

}
