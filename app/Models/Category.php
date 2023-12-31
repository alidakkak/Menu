<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function setImageAttribute($image){
        $newImageName = uniqid() . '_' . 'category_image' . '.' . $image->extension();
        $image->move(public_path('category_image') , $newImageName);
        return $this->attributes['image'] =  '/'.'category_image'.'/' . $newImageName;
    }

    public function subCategory() {
        return $this->hasMany(SubCategory::class);
    }

    public function feature() {
        return $this->hasMany(Feature::class);
    }
}
