<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantAttributeValue extends Model
{
    protected $fillable = [
        'variant_id',
        'attribute_value_id',
    ];

    public $timestamps = true;

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class, 'variant_attribute_values', 'attribute_value_id');
    }
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }
}
