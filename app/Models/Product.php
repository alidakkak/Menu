<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function setImageAttribute($image){
        $newImageName = uniqid() . '_' . 'product_image' . '.' . $image->extension();
        $image->move(public_path('product_image') , $newImageName);
        return $this->attributes['image'] =  '/'.'product_image'.'/' . $newImageName;
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
