<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitação enviada - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-100 text-zinc-950 antialiased">
    <main class="grid min-h-screen place-items-center px-4 py-10">
        <section class="w-full max-w-xl rounded-lg border border-zinc-200 bg-white shadow-sm">
            <div class="border-b border-zinc-200 px-6 py-6">
                <p class="text-sm font-semibold text-teal-700">Central de Atendimento</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-normal text-zinc-950">Solicitação enviada</h1>
                <p class="mt-2 text-sm leading-6 text-zinc-600">Guarde o protocolo para identificar seu atendimento.</p>
            </div>

            <div class="px-6 py-7">
                <div class="rounded-lg border border-teal-200 bg-teal-50 p-5 text-center">
                    <p class="text-sm font-semibold text-teal-800">Protocolo</p>
                    <p class="mt-2 break-words text-3xl font-bold tracking-normal text-teal-950">{{ $protocol }}</p>
                </div>

                <div class="mt-6 rounded-md border border-zinc-200 bg-zinc-50 p-4">
                    <p class="text-sm leading-6 text-zinc-600">A solicitação já está disponível para a equipe na fila de atendimento.</p>
                </div>

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ url('/') }}" class="inline-flex h-11 items-center justify-center rounded-md border border-zinc-300 bg-white px-4 text-sm font-semibold text-zinc-800 shadow-sm transition hover:bg-zinc-50 focus:outline-none focus:ring-4 focus:ring-teal-100">
                        Voltar ao início
                    </a>
                    <a href="{{ route('solicitacoes.create') }}" class="inline-flex h-11 items-center justify-center rounded-md bg-teal-700 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-4 focus:ring-teal-100">
                        Abrir outra solicitação
                    </a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
