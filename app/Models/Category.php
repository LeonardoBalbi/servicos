<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'default_due_days' => 'integer',
            'sort_order' => 'integer',
            'is_private' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
