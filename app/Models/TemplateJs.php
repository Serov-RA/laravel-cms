<?php

namespace App\Models;

use App\Models\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateJs extends Model
{
    use BaseTrait;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'js_id',
        'template_id',
        'view_pos',
        'block_pos',
    ];

    public function js(): BelongsTo
    {
        return $this->belongsTo(Js::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}
