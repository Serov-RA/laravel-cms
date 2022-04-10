<?php

namespace App\Models;

use App\Models\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageCss extends Model
{
    use BaseTrait;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'css_id',
        'page_id',
        'block_pos',
    ];

    public function css(): BelongsTo
    {
        return $this->belongsTo(Css::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
