<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slide extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'image', 'link', 'position', 'is_active'];
    protected $dates = ['deleted_at'];
}
