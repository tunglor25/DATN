<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VariantAttributeValuesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('variant_attribute_values')->delete();
        
        \DB::table('variant_attribute_values')->insert(array (
            0 => 
            array (
                'id' => 1,
                'variant_id' => 166,
                'attribute_value_id' => 33,
                'created_at' => '2025-08-28 00:03:33',
                'updated_at' => '2025-08-28 00:03:33',
            ),
            1 => 
            array (
                'id' => 2,
                'variant_id' => 167,
                'attribute_value_id' => 34,
                'created_at' => '2025-08-28 00:03:33',
                'updated_at' => '2025-08-28 00:03:33',
            ),
            2 => 
            array (
                'id' => 3,
                'variant_id' => 168,
                'attribute_value_id' => 35,
                'created_at' => '2025-08-28 00:03:33',
                'updated_at' => '2025-08-28 00:03:33',
            ),
            3 => 
            array (
                'id' => 4,
                'variant_id' => 169,
                'attribute_value_id' => 36,
                'created_at' => '2025-08-28 00:03:33',
                'updated_at' => '2025-08-28 00:03:33',
            ),
            4 => 
            array (
                'id' => 5,
                'variant_id' => 170,
                'attribute_value_id' => 37,
                'created_at' => '2025-08-28 00:03:33',
                'updated_at' => '2025-08-28 00:03:33',
            ),
            5 => 
            array (
                'id' => 6,
                'variant_id' => 171,
                'attribute_value_id' => 38,
                'created_at' => '2025-08-28 00:03:33',
                'updated_at' => '2025-08-28 00:03:33',
            ),
            6 => 
            array (
                'id' => 7,
                'variant_id' => 172,
                'attribute_value_id' => 33,
                'created_at' => '2025-08-28 00:05:22',
                'updated_at' => '2025-08-28 00:05:22',
            ),
            7 => 
            array (
                'id' => 8,
                'variant_id' => 173,
                'attribute_value_id' => 34,
                'created_at' => '2025-08-28 00:05:22',
                'updated_at' => '2025-08-28 00:05:22',
            ),
            8 => 
            array (
                'id' => 9,
                'variant_id' => 174,
                'attribute_value_id' => 35,
                'created_at' => '2025-08-28 00:05:22',
                'updated_at' => '2025-08-28 00:05:22',
            ),
            9 => 
            array (
                'id' => 10,
                'variant_id' => 175,
                'attribute_value_id' => 36,
                'created_at' => '2025-08-28 00:05:22',
                'updated_at' => '2025-08-28 00:05:22',
            ),
            10 => 
            array (
                'id' => 11,
                'variant_id' => 176,
                'attribute_value_id' => 37,
                'created_at' => '2025-08-28 00:05:22',
                'updated_at' => '2025-08-28 00:05:22',
            ),
            11 => 
            array (
                'id' => 12,
                'variant_id' => 177,
                'attribute_value_id' => 38,
                'created_at' => '2025-08-28 00:05:22',
                'updated_at' => '2025-08-28 00:05:22',
            ),
            12 => 
            array (
                'id' => 13,
                'variant_id' => 178,
                'attribute_value_id' => 39,
                'created_at' => '2025-08-28 00:05:22',
                'updated_at' => '2025-08-28 00:05:22',
            ),
        ));
        
        
    }
}