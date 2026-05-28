<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicTicketController extends Controller
{
    public function create(): View
    {
        return view('tickets.create', [
            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:32'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:10000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:10240'],
        ], [
            'category_id.required' => 'Selecione uma categoria de serviço.',
            'category_id.exists' => 'A categoria selecionada não está disponível.',
            'name.required' => 'Informe seu nome.',
            'email.required' => 'Informe seu e-mail.',
            'email.email' => 'Informe um e-mail válido.',
            'subject.required' => 'Informe o assunto.',
            'message.required' => 'Descreva a solicitação.',
            'attachments.max' => 'Envie no máximo 5 anexos.',
            'attachments.*.max' => 'Cada anexo pode ter no máximo 10 MB.',
        ]);

        $ticket = DB::transaction(function () use ($request, $data): Ticket {
            $email = Str::lower($data['email']);

            $customer = Customer::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'] ?? null,
                    'is_active' => true,
                ],
            );

            $ticket = Ticket::create([
                'protocol' => $this->newProtocol(),
                'customer_id' => $customer->id,
                'category_id' => $data['category_id'],
                'customer_name' => $data['name'],
                'customer_email' => $email,
                'customer_phone' => $data['phone'] ?? null,
                'subject' => $data['subject'],
                'message' => $data['message'],
                'status' => 'new',
                'priority' => 'medium',
                'source' => 'web',
                'metadata' => [
                    'created_from' => 'public_form',
                    'ip' => $request->ip(),
                    'user_agent' => Str::limit((string) $request->userAgent(), 500, ''),
                ],
            ]);

            foreach ($request->file('attachments', []) as $file) {
                $path = $file->store("ticket-attachments/{$ticket->protocol}", 'public');

                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'customer_id' => $customer->id,
                    'original_name' => $file->getClientOriginalName(),
                    'stored_name' => basename($path),
                    'disk' => 'public',
                    'path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize() ?: 0,
                    'hash' => hash_file('sha256', $file->getRealPath()),
                    'uploaded_by_type' => 'customer',
                    'metadata' => [
                        'created_from' => 'public_form',
                    ],
                ]);
            }

            return $ticket;
        });

        return redirect()
            ->route('solicitacoes.success')
            ->with('protocol', $ticket->protocol);
    }

    public function success(): View|RedirectResponse
    {
        if (! session()->has('protocol')) {
            return redirect()->route('solicitacoes.create');
        }

        return view('tickets.success', [
            'protocol' => session('protocol'),
        ]);
    }

    private function newProtocol(): string
    {
        do {
            $protocol = 'GD-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        } while (Ticket::query()->where('protocol', $protocol)->exists());

        return $protocol;
    }
}
