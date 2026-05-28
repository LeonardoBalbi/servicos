<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }
}
