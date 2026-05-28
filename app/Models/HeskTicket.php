<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class HeskTicket extends Model
{
    protected $connection = 'hesk';

    protected $table = 'tickets';

    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'dt' => 'datetime',
            'lastchange' => 'datetime',
            'closedat' => 'datetime',
            'due_date' => 'datetime',
        ];
    }

    public function heskCategory(): BelongsTo
    {
        return $this->belongsTo(HeskCategory::class, 'category');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(HeskReply::class, 'replyto');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(HeskAttachment::class, 'ticket_id', 'trackid');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ((int) $this->status) {
            0 => 'Novo',
            1 => 'Aguardando atendimento',
            2 => 'Aguardando cidadão',
            3 => 'Resolvido',
            4 => 'Em andamento',
            5 => 'Em espera',
            default => 'Status ' . $this->status,
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ((int) $this->priority) {
            0 => 'Crítica',
            1 => 'Alta',
            2 => 'Média',
            3 => 'Baixa',
            default => 'Prioridade ' . $this->priority,
        };
    }
}
