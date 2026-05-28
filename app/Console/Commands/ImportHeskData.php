<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportHeskData extends Command
{
    protected $signature = 'hesk:import {--fresh : Limpa as tabelas migradas antes de importar}';

    protected $description = 'Importa dados do banco HESK legado para o modelo Laravel.';

    public function handle(): int
    {
        if ($this->option('fresh')) {
            $this->freshImport();
        }

        DB::disableQueryLog();
        DB::connection('hesk')->disableQueryLog();

        $categoryMap = $this->importCategories();
        $customerMap = $this->importCustomers();
        $userMap = $this->importUsers();
        $ticketCustomerMap = $this->ticketCustomerMap();
        $ticketMap = $this->importTickets($categoryMap, $customerMap, $userMap, $ticketCustomerMap);

        $this->importReplies($ticketMap, $customerMap, $userMap);
        $this->importAttachments($ticketMap, $customerMap);

        $this->newLine();
        $this->components->info('Importacao HESK concluida.');
        $this->table(['Tabela', 'Registros'], [
            ['categories', Category::count()],
            ['customers', Customer::count()],
            ['users', User::count()],
            ['tickets', Ticket::count()],
            ['ticket_replies', TicketReply::count()],
            ['ticket_attachments', TicketAttachment::count()],
        ]);

        return self::SUCCESS;
    }

    private function freshImport(): void
    {
        $this->components->warn('Limpando tabelas migradas...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        TicketAttachment::truncate();
        TicketReply::truncate();
        Ticket::truncate();
        Customer::truncate();
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * @return array<int, int>
     */
    private function importCategories(): array
    {
        $this->components->task('Importando categorias');

        $map = [];

        DB::connection('hesk')->table('categories')->orderBy('id')->each(function (object $legacy) use (&$map): void {
            $slug = $this->uniqueSlug($legacy->name ?: "categoria-{$legacy->id}", Category::class);
            $priority = $this->mapPriority($legacy->priority ?? 3);

            $category = Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $legacy->name ?: "Categoria {$legacy->id}",
                    'description' => null,
                    'color' => null,
                    'icon' => null,
                    'default_priority' => $priority,
                    'default_due_days' => $this->dueDays($legacy->default_due_date_amount, $legacy->default_due_date_unit),
                    'sort_order' => (int) $legacy->cat_order,
                    'is_private' => (string) $legacy->type === '1',
                    'is_active' => true,
                ]
            );

            $map[(int) $legacy->id] = (int) $category->id;
        });

        return $map;
    }

    /**
     * @return array<int, int>
     */
    private function importCustomers(): array
    {
        $this->components->task('Importando cidadaos');

        $map = [];

        DB::connection('hesk')->table('customers')->orderBy('id')->each(function (object $legacy) use (&$map): void {
            $email = $this->firstEmail($legacy->email);

            if ($email === null) {
                return;
            }

            $customer = Customer::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $legacy->name ?: $email,
                    'document' => null,
                    'phone' => null,
                    'google_id' => null,
                    'email_verified_at' => ((int) $legacy->verified === 1) ? now() : null,
                    'is_active' => true,
                    'metadata' => [
                        'legacy_customer_id' => (int) $legacy->id,
                        'legacy_language' => $legacy->language,
                        'legacy_verified' => (int) $legacy->verified,
                    ],
                ]
            );

            $map[(int) $legacy->id] = (int) $customer->id;
        });

        return $map;
    }

    /**
     * @return array<int, int>
     */
    private function importUsers(): array
    {
        $this->components->task('Importando atendentes');

        $map = [];

        DB::connection('hesk')->table('users')->orderBy('id')->each(function (object $legacy) use (&$map): void {
            $email = $this->firstEmail($legacy->email);

            if ($email === null) {
                return;
            }

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $legacy->name ?: ($legacy->nickname ?: $legacy->user),
                    'password' => Hash::make(Str::random(32)),
                    'email_verified_at' => now(),
                ]
            );

            $map[(int) $legacy->id] = (int) $user->id;
        });

        return $map;
    }

    /**
     * @param array<int, int> $categoryMap
     * @param array<int, int> $customerMap
     * @param array<int, int> $userMap
     * @param array<int, int> $ticketCustomerMap
     * @return array<int, int>
     */
    private function importTickets(array $categoryMap, array $customerMap, array $userMap, array $ticketCustomerMap): array
    {
        $this->components->task('Importando tickets');

        $map = [];

        DB::connection('hesk')->table('tickets')->orderBy('id')->each(function (object $legacy) use (&$map, $categoryMap, $customerMap, $userMap, $ticketCustomerMap): void {
            $email = $this->firstEmail($legacy->email);
            $legacyCustomerId = $ticketCustomerMap[(int) $legacy->id] ?? null;
            $customerId = $legacyCustomerId ? ($customerMap[$legacyCustomerId] ?? null) : null;
            $customerId ??= $this->customerIdForTicket($legacy, $email);
            $categoryId = $categoryMap[(int) $legacy->category] ?? Category::query()->value('id');

            if ($categoryId === null) {
                $categoryId = Category::create([
                    'name' => 'Sem categoria',
                    'slug' => 'sem-categoria',
                    'default_priority' => 'medium',
                    'is_active' => true,
                ])->id;
            }

            $ticket = Ticket::updateOrCreate(
                ['legacy_track_id' => $legacy->trackid],
                [
                    'protocol' => $legacy->trackid,
                    'customer_id' => $customerId,
                    'category_id' => $categoryId,
                    'assigned_user_id' => $userMap[(int) $legacy->owner] ?? null,
                    'customer_name' => $legacy->name ?: null,
                    'customer_email' => $email,
                    'customer_phone' => null,
                    'subject' => $legacy->subject ?: "Ticket {$legacy->trackid}",
                    'message' => $legacy->message,
                    'message_html' => $legacy->message_html,
                    'status' => $this->mapStatus($legacy->status),
                    'priority' => $this->mapPriority($legacy->priority),
                    'source' => 'migration',
                    'due_at' => $legacy->due_date,
                    'first_response_at' => $legacy->firstreply,
                    'last_reply_at' => $legacy->lastchange,
                    'resolved_at' => $legacy->closedat,
                    'closed_at' => $legacy->closedat,
                    'metadata' => $this->ticketMetadata($legacy, $legacyCustomerId),
                    'created_at' => $legacy->dt,
                    'updated_at' => $legacy->lastchange,
                ]
            );

            $map[(int) $legacy->id] = (int) $ticket->id;
        });

        return $map;
    }

    /**
     * @param array<int, int> $ticketMap
     * @param array<int, int> $customerMap
     * @param array<int, int> $userMap
     */
    private function importReplies(array $ticketMap, array $customerMap, array $userMap): void
    {
        $this->components->task('Importando respostas');

        DB::connection('hesk')->table('replies')->orderBy('id')->each(function (object $legacy) use ($ticketMap, $customerMap, $userMap): void {
            $ticketId = $ticketMap[(int) $legacy->replyto] ?? null;

            if ($ticketId === null) {
                return;
            }

            $authorType = ((int) $legacy->staffid > 0) ? 'staff' : 'customer';

            TicketReply::updateOrCreate(
                ['legacy_reply_id' => (int) $legacy->id],
                [
                    'ticket_id' => $ticketId,
                    'customer_id' => $customerMap[(int) $legacy->customer_id] ?? null,
                    'user_id' => $userMap[(int) $legacy->staffid] ?? null,
                    'author_type' => $authorType,
                    'author_name' => null,
                    'author_email' => null,
                    'message' => $legacy->message,
                    'message_html' => $legacy->message_html,
                    'is_internal' => false,
                    'is_read_by_customer' => (string) $legacy->read === '1',
                    'is_read_by_staff' => true,
                    'metadata' => [
                        'legacy_ticket_id' => (int) $legacy->replyto,
                        'legacy_staff_id' => (int) $legacy->staffid,
                        'legacy_customer_id' => $legacy->customer_id ? (int) $legacy->customer_id : null,
                        'legacy_rating' => $legacy->rating,
                        'legacy_attachments' => $legacy->attachments,
                    ],
                    'created_at' => $legacy->dt,
                    'updated_at' => $legacy->dt,
                ]
            );
        });
    }

    /**
     * @param array<int, int> $ticketMap
     * @param array<int, int> $customerMap
     */
    private function importAttachments(array $ticketMap, array $customerMap): void
    {
        $this->components->task('Importando anexos');

        $trackToTicket = Ticket::query()
            ->whereNotNull('legacy_track_id')
            ->pluck('id', 'legacy_track_id')
            ->mapWithKeys(fn ($id, $track) => [(string) $track => (int) $id])
            ->all();

        DB::connection('hesk')->table('attachments')->orderBy('att_id')->each(function (object $legacy) use ($trackToTicket, $customerMap): void {
            $ticketId = $trackToTicket[(string) $legacy->ticket_id] ?? null;

            if ($ticketId === null) {
                return;
            }

            TicketAttachment::updateOrCreate(
                ['legacy_attachment_id' => (int) $legacy->att_id],
                [
                    'ticket_id' => $ticketId,
                    'ticket_reply_id' => null,
                    'customer_id' => null,
                    'user_id' => null,
                    'original_name' => $legacy->real_name ?: $legacy->saved_name,
                    'stored_name' => $legacy->saved_name,
                    'disk' => 'legacy_hesk',
                    'path' => 'attachments/'.$legacy->saved_name,
                    'mime_type' => null,
                    'size' => (int) $legacy->size,
                    'hash' => null,
                    'uploaded_by_type' => ((string) $legacy->type === '1') ? 'staff' : 'customer',
                    'legacy_saved_name' => $legacy->saved_name,
                    'metadata' => [
                        'legacy_ticket_trackid' => $legacy->ticket_id,
                        'legacy_type' => $legacy->type,
                    ],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        });
    }

    private function uniqueSlug(string $name, string $model): string
    {
        $base = Str::slug($name) ?: 'item';
        $slug = $base;
        $i = 2;

        while ($model::query()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    private function firstEmail(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        foreach (preg_split('/[,;\s]+/', $value) ?: [] as $candidate) {
            $candidate = trim($candidate);

            if (filter_var($candidate, FILTER_VALIDATE_EMAIL)) {
                return Str::lower($candidate);
            }
        }

        return null;
    }

    /**
     * @return array<int, int>
     */
    private function ticketCustomerMap(): array
    {
        return DB::connection('hesk')
            ->table('ticket_to_customer')
            ->where('customer_type', 'REQUESTER')
            ->orderBy('id')
            ->pluck('customer_id', 'ticket_id')
            ->mapWithKeys(fn ($customerId, $ticketId) => [(int) $ticketId => (int) $customerId])
            ->all();
    }

    private function customerIdForTicket(object $ticket, ?string $email): ?int
    {
        if ($email === null) {
            return null;
        }

        $customer = Customer::firstOrCreate(
            ['email' => $email],
            [
                'name' => $ticket->name ?: $email,
                'is_active' => true,
                'metadata' => ['source' => 'hesk_ticket'],
            ]
        );

        return (int) $customer->id;
    }

    private function mapStatus(int|string|null $status): string
    {
        return match ((int) $status) {
            0 => 'new',
            1 => 'waiting_staff',
            2 => 'waiting_customer',
            3 => 'resolved',
            4 => 'open',
            5 => 'on_hold',
            default => 'open',
        };
    }

    private function mapPriority(int|string|null $priority): string
    {
        return match ((int) $priority) {
            0 => 'critical',
            1 => 'high',
            2 => 'medium',
            3 => 'low',
            default => 'medium',
        };
    }

    private function dueDays(int|string|null $amount, ?string $unit): ?int
    {
        if ($amount === null || (int) $amount <= 0) {
            return null;
        }

        return match ($unit) {
            'minute', 'minutes' => max(1, (int) ceil(((int) $amount) / 1440)),
            'hour', 'hours' => max(1, (int) ceil(((int) $amount) / 24)),
            'week', 'weeks' => (int) $amount * 7,
            'month', 'months' => (int) $amount * 30,
            default => (int) $amount,
        };
    }

    private function ticketMetadata(object $ticket, ?int $legacyCustomerId): array
    {
        $customFields = [];

        for ($i = 1; $i <= 100; $i++) {
            $field = "custom{$i}";

            if (isset($ticket->{$field}) && trim((string) $ticket->{$field}) !== '') {
                $customFields[$field] = $ticket->{$field};
            }
        }

        return [
            'legacy_ticket_id' => (int) $ticket->id,
            'legacy_customer_id' => $legacyCustomerId,
            'legacy_status' => (int) $ticket->status,
            'legacy_priority' => (int) $ticket->priority,
            'legacy_opened_by' => $ticket->openedby ? (int) $ticket->openedby : null,
            'legacy_owner' => $ticket->owner ? (int) $ticket->owner : null,
            'legacy_ip' => $ticket->ip,
            'legacy_language' => $ticket->language,
            'legacy_last_replier' => $ticket->lastreplier,
            'legacy_replies_count' => (int) $ticket->replies,
            'legacy_staff_replies_count' => (int) $ticket->staffreplies,
            'legacy_archive' => $ticket->archive,
            'legacy_locked' => $ticket->locked,
            'legacy_attachments' => $ticket->attachments,
            'legacy_merged' => $ticket->merged,
            'legacy_history' => $ticket->history,
            'custom_fields' => $customFields,
        ];
    }
}
