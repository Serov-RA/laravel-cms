<?php

namespace Database\Seeders;

use App\Models\Js;
use App\Models\Css;
use App\Models\Incut;
use App\Models\Template;
use App\Models\TemplateJs;
use App\Models\TemplateCss;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = scandir(App::basePath().Template::$layout_path);

        foreach ($templates as $template) {
            if (!str_contains($template, '.blade.php')) {
                continue;
            }

            $tpl = str_replace('.blade.php', '', $template);

            $template_db = Template::create([
                'name' => ucfirst($tpl),
                'template_file' => $tpl,
                'template_content' => file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'template.html'),
            ]);

            $js = Js::create([
                'name' => 'Bootsrap',
                'type' => Js::TYPE_EXTERNAL,
                'path' => '/js/bootstrap.bundle.min.js',
            ]);

            $css = Css::create([
                'name' => 'Bootsrap',
                'type' => Css::TYPE_EXTERNAL,
                'path' => '/css/bootstrap.min.css',
            ]);

            TemplateJs::create([
                'name' => 'TemplateJs',
                'template_id' => $template_db->id,
                'js_id' => $js->id,
                'view_pos' => Js::POS_END,
                'block_pos' => 1,
            ]);

            TemplateCss::create([
                'name' => 'TemplateJs',
                'template_id' => $template_db->id,
                'css_id' => $css->id,
                'block_pos' => 1,
            ]);

            Incut::create([
                'name' => __('Year'),
                'content' => date('Y'),
            ]);
        }
    }
}
