<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\DB;

trait BaseTrait
{
    public static function getModelName(): string
    {
        return basename(str_replace("\\", '/', static::class));
    }

    public static function getModelNameIdx(): string
    {
        return strtolower(self::getModelName());
    }

    public static function getEntityName(): string
    {
        return isset(self::$entityName) ? __(self::$entityName) : __(self::getModelName());
    }

    public static function getEntitiesName(): string
    {
        return isset(self::$entitiesName) ? __(self::$entitiesName) : '';
    }

    public static function getFieldNames(): array
    {
        return [];
    }

    public static function getFieldName(string $field): string
    {
        $fieldNames = self::getFieldNames();
        return $fieldNames[$field] ?? $field;
    }

    public function getViewFields(string $view): array
    {
        return $this->admin_fields[$view] ?? [];
    }

    public function getTableFields()
    {
        $fields = [];
        $constraints = [];

        $c_items = DB::table(DB::raw("pg_attribute af, pg_attribute a, (
            SELECT conrelid, confrelid, conkey[i] AS conkey, confkey[i] AS confkey, conname
		    FROM (
                SELECT conrelid, confrelid, conkey, confkey, generate_series(1,array_upper(conkey,1)) AS i, conname
			    FROM pg_constraint WHERE contype = 'f'
            ) ss
		) ss2"))
            ->selectRaw('confrelid::regclass as table_source, a.attname as dest_key')
            ->whereRaw("af.attnum = confkey AND af.attrelid = confrelid AND
                a.attnum = conkey AND a.attrelid = conrelid
                AND conrelid::regclass = 'public.".$this->getTable()."'::regclass")->get();

        foreach ($c_items as $c_item) {
            $constraints[$c_item->dest_key] = $c_item->table_source;
        }

        $items = DB::table('information_schema.columns')
            ->select('column_name', 'data_type', 'column_default')
            ->where('table_name', $this->getTable())
            ->get();

        foreach ($items as $item) {
            $fields[$item->column_name] = [
                'type' => $item->data_type,
                'default' => $item->column_default,
                'rel'  => $constraints[$item->column_name] ?? false,
            ];
        }

        return $fields;
    }

    public function getPageFields(string $page): array
    {
        $fields = [];
        $page_fields = $this->getViewFields($page);

        if ($page_fields) {
            $attributes = $this->getTableFields();

            foreach ($page_fields as $index_field) {
                $select_method = $index_field.'Select';
                $rel_method    = rtrim($index_field, '_id');
                $custom_method = $index_field.'Custom';
                $tableViewMethod = $index_field.'TableView';


                if (!isset($attributes[$index_field])) {
                    if (!method_exists($this, $custom_method) && !method_exists($this, $tableViewMethod)) {
                        continue;
                    }

                    $attributes[$index_field] = [
                        'type' => 'custom',
                        'default' => '',
                        'rel' => false,
                    ];
                }

                $fields[$index_field] = [
                    'name' => self::getFieldName($index_field),
                    'type' => $attributes[$index_field]['type'],
                    'default' => $attributes[$index_field]['default'],
                    'rel' => $attributes[$index_field]['rel'] ? [
                        'table' => $attributes[$index_field]['rel'],
                        'value' => method_exists($this, $rel_method) && $this->$rel_method ?
                            $this->$rel_method->name : '',
                    ] : false,
                    'select' => method_exists($this, $select_method) ? $this->$select_method() : false,
                    'datetime' => str_contains(strtolower($attributes[$index_field]['type']), 'datetime') ||
                        str_contains(strtolower($attributes[$index_field]['type']), 'timestamp'),
                    'date' => str_contains($attributes[$index_field]['type'], 'date'),
                    'html' => isset($this->html, $this->html[$index_field]) ? $this->html[$index_field] : false,
                    'code' => isset($this->code, $this->code[$index_field]) ? true : false,
                    'custom' => method_exists($this, $custom_method) ? $this->$custom_method() : false,
                    'customTableView' => method_exists($this, $tableViewMethod),
                ];
            }
        }

        return $fields;
    }

    public function getRules(): array
    {
        return $this->rules ?? [];
    }

    public function tableValue(string $field, $fields): string
    {
        $method = $field.'TableView';

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        $field_data = $fields[$field];

        if ($field_data['select']) {
            $values = $field_data['select'];
            return isset($values[$this->$field]) ? htmlspecialchars($values[$this->$field], ENT_QUOTES) : '';
        }

        if ($field_data['rel']) {
            $rel_method  = rtrim($field, '_id');
            return method_exists($this, $rel_method) && $this->$rel_method ?
                htmlspecialchars($this->$rel_method->name, ENT_QUOTES) : '';
        }

        if ($field_data['type'] === 'boolean') {
            return $this->$field ? __('Yes') : __('No');
        }

        return htmlspecialchars($this->$field, ENT_QUOTES) ?? '';
    }

    public static function hasSoftDelete(): bool
    {
        $model = new self;
        return method_exists($model, 'runSoftDelete');
    }
}
