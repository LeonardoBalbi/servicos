<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class HeskReply extends Model
{
    protected $connection = 'hesk';

    protected $table = 'replies';

    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'dt' => 'datetime',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(HeskTicket::class, 'replyto');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(HeskCustomer::class, 'customer_id');
    }
}
