<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

class ModelsHelper
{
    public static function get(): array
    {
        $models_path = '/app/Models';
        $models_namespace   = "App\\Models";
        $models_dir  = scandir(App::basePath().$models_path);
        $models = [];

        foreach ($models_dir AS $one_file) {
            if (!str_contains($one_file, '.php')
                || str_contains($one_file, 'Abstract')
                || str_contains($one_file, 'Interface')
                || str_contains($one_file, 'Trait')) {
                continue;
            }

            $model_name = str_replace('.php', '', $one_file);
            $model_classname = $models_namespace."\\".$model_name;
            $model_idx = $model_classname::getModelNameIdx();
            $model = new $model_classname;

            $models[$model_idx] = [
                'model_idx'     => $model_idx,
                'class_name'    => $model_classname,
                'table'         => $model->getTable(),
                'model_name'    => $model_classname::getModelName(),
                'entity_name'   => $model_classname::getEntityName(),
                'entities_name' => $model_classname::getEntitiesName(),
            ];
        }

        return $models;
    }

    public static function getModelByTable(string $table): ?array
    {
        $models = self::get();

        foreach ($models as $model_idx => $model) {
            if ($model['table'] === $table) {
                return $model;
            }
        }

        return null;
    }
}
