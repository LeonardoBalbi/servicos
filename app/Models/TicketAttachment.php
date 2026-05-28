<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function reply(): BelongsTo
    {
        return $this->belongsTo(TicketReply::class, 'ticket_reply_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
