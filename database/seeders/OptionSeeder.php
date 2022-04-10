<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Option::$default_options as $option_key => $option) {
            $option_value = $option['option_value'];

            if (isset($option['relation'])) {
                $model_class = "App\\Models\\".$option['relation'];
                $first_record = $model_class::first();

                $option_value = $first_record ? $first_record->id : '';
            }

            Option::create([
                'name' => $option['name'],
                'option_key' => $option_key,
                'option_value' => $option_value,
            ]);
        }
    }
}
