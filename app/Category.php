<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name', 'parent_id'
    ];

//    public function categories()
//    {
//        return $this->hasMany(Category::class,'parent_id');
//    }

    public function subOptions()
    {
        return $this->hasMany(Category::class,'parent_id')->with('subOptions');
    }
//    public function childrenRecursive()
//    {
//        return $this->children()->with('childrenRecursive');
//    }

}
