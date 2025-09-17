<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $fillable = [
        'attribute_id',
        'value',
    ];

    public $timestamps = true;

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'variant_attribute_values', 'attribute_value_id', 'variant_id');
    }
}
