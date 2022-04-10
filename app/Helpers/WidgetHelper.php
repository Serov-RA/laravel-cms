<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

class WidgetHelper
{
    public static function get(): array
    {
        $widget_path = 'app/Widgets';
        $widget_ns   = "\\".ucfirst(str_replace('/', "\\", $widget_path));
        $widget_dir  = scandir(App::basePath().DIRECTORY_SEPARATOR.$widget_path);
        $widgets = [];

        foreach ($widget_dir AS $one_file) {
            if (!str_contains($one_file, 'Widget.php')) {
                continue;
            }

            $widget_name = str_replace('.php', '', $one_file);
            $widget_classname = $widget_ns."\\".$widget_name;

            $widgets[$widget_name] = $widget_classname;
        }

        return $widgets;
    }
}
