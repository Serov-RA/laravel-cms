<?php

namespace App\Models;

use App\Models\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;

class Incut extends Model
{
    use BaseTrait;

    private static string $entityName  = 'Incut';

    private static string $entitiesName = 'Incuts';

    private array $admin_fields = [
        'index' => [
            'id',
            'name',
        ],
        'edit' => [
            'name',
            'content',
        ]
    ];

    private $rules = [
        'name' => 'required',
    ];

    public static function getFieldNames(): array
    {
        return [
            'name' => __('Name'),
            'content' => __('Content'),
        ];
    }

    public function idTableView()
    {
        return '{{{in|'.$this->id.'}}}';
    }
}
