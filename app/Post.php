<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name', 'description','category_id','image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

}
