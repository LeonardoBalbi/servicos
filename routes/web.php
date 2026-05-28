<?php

use App\Http\Controllers\PublicTicketController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/solicitacoes/nova');

Route::get('/solicitacoes/nova', [PublicTicketController::class, 'create'])
    ->name('solicitacoes.create');

Route::post('/solicitacoes', [PublicTicketController::class, 'store'])
    ->name('solicitacoes.store');

Route::get('/solicitacoes/enviada', [PublicTicketController::class, 'success'])
    ->name('solicitacoes.success');
