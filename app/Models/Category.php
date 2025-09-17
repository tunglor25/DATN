<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'slug', 'parent_id', 'created_at', 'updated_at'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // (Tùy chọn) Quan hệ con (children)
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
