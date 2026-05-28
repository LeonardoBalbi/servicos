<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class HeskAttachment extends Model
{
    protected $connection = 'hesk';

    protected $table = 'attachments';

    protected $primaryKey = 'att_id';

    public $timestamps = false;

    protected $guarded = [];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(HeskTicket::class, 'ticket_id', 'trackid');
    }
}
