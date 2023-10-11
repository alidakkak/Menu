<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function feature() {
        return $this->belongsTo(Feature::class);
    }

//    public function setImageAttribute ($image){
//        $newImageName = uniqid() . '_' . 'feature_image' . '.' . $image->extension();
//        $image->move(public_path('feature_image') , $newImageName);
//        return $this->attributes['image'] =  '/'.'feature_image'.'/' . $newImageName;
//    }
}
