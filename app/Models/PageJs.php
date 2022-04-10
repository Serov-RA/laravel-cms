<?php

namespace App\Models;

use App\Models\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageJs extends Model
{
    use BaseTrait;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'js_id',
        'page_id',
        'view_pos',
        'block_pos',
    ];

    public function js(): BelongsTo
    {
        return $this->belongsTo(Js::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
