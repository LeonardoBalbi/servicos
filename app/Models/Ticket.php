<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'first_response_at' => 'datetime',
            'last_reply_at' => 'datetime',
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'new' => 'Novo',
            'open' => 'Aberto',
            'waiting_customer' => 'Aguardando cidadão',
            'waiting_staff' => 'Aguardando atendimento',
            'on_hold' => 'Em espera',
            'resolved' => 'Resolvido',
            'closed' => 'Fechado',
            'cancelled' => 'Cancelado',
            default => ucfirst((string) $this->status),
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'Baixa',
            'medium' => 'Média',
            'high' => 'Alta',
            'critical' => 'Crítica',
            default => ucfirst((string) $this->priority),
        };
    }
}
