<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Template;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::create([
            'name' => __('Main page'),
            'template_id' => Template::first()->id,
            'published' => true,
            'alias' => 'main',
            'meta_title' => __('Main page'),
            'content' => '<h1 class="text-center mt-3">Laravel test project</h1>',
        ]);
    }
}
