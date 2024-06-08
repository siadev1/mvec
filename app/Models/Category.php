<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class category extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
