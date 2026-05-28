<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nova solicitação - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-100 text-zinc-950 antialiased">
    <div class="border-b border-zinc-200 bg-white">
        <header class="mx-auto flex min-h-20 w-full max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
            <div>
                <p class="text-sm font-medium text-teal-700">Central de Atendimento</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-normal text-zinc-950">{{ config('app.name') }}</h1>
            </div>

            <a href="{{ url('/admin') }}" class="inline-flex h-10 items-center justify-center rounded-md border border-zinc-300 bg-white px-4 text-sm font-semibold text-zinc-800 shadow-sm transition hover:bg-zinc-50 focus:outline-none focus:ring-4 focus:ring-teal-100">
                Área administrativa
            </a>
        </header>
    </div>

    <main class="mx-auto grid w-full max-w-7xl gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[minmax(0,1fr)_360px] lg:px-8">
        <section class="rounded-lg border border-zinc-200 bg-white shadow-sm">
            <div class="border-b border-zinc-200 px-5 py-5 sm:px-7">
                <p class="text-sm font-semibold text-teal-700">Solicitação pública</p>
                <h2 class="mt-1 text-2xl font-semibold tracking-normal text-zinc-950">Abrir uma nova solicitação</h2>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-zinc-600">Informe os dados do atendimento para receber um protocolo.</p>
            </div>

            <form action="{{ route('solicitacoes.store') }}" method="post" enctype="multipart/form-data" class="px-5 py-6 sm:px-7">
                @csrf

                @if ($errors->any())
                    <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">
                        Verifique os campos destacados antes de enviar.
                    </div>
                @endif

                <div class="grid gap-6">
                    <div>
                        <label for="category_id" class="mb-2 block text-sm font-semibold text-zinc-800">Categoria de serviço</label>
                        <select id="category_id" name="category_id" required class="block h-12 w-full rounded-md border border-zinc-300 bg-white px-3 text-zinc-950 shadow-sm outline-none transition focus:border-teal-600 focus:ring-4 focus:ring-teal-100">
                            <option value="">Selecione</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="name" class="mb-2 block text-sm font-semibold text-zinc-800">Nome</label>
                            <input id="name" name="name" value="{{ old('name') }}" autocomplete="name" required class="block h-12 w-full rounded-md border border-zinc-300 bg-white px-3 text-zinc-950 shadow-sm outline-none transition placeholder:text-zinc-400 focus:border-teal-600 focus:ring-4 focus:ring-teal-100">
                            @error('name')
                                <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-zinc-800">E-mail</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required class="block h-12 w-full rounded-md border border-zinc-300 bg-white px-3 text-zinc-950 shadow-sm outline-none transition placeholder:text-zinc-400 focus:border-teal-600 focus:ring-4 focus:ring-teal-100">
                            @error('email')
                                <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)]">
                        <div>
                            <label for="phone" class="mb-2 block text-sm font-semibold text-zinc-800">Telefone</label>
                            <input id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" class="block h-12 w-full rounded-md border border-zinc-300 bg-white px-3 text-zinc-950 shadow-sm outline-none transition placeholder:text-zinc-400 focus:border-teal-600 focus:ring-4 focus:ring-teal-100">
                            @error('phone')
                                <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subject" class="mb-2 block text-sm font-semibold text-zinc-800">Assunto</label>
                            <input id="subject" name="subject" value="{{ old('subject') }}" required class="block h-12 w-full rounded-md border border-zinc-300 bg-white px-3 text-zinc-950 shadow-sm outline-none transition placeholder:text-zinc-400 focus:border-teal-600 focus:ring-4 focus:ring-teal-100">
                            @error('subject')
                                <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="message" class="mb-2 block text-sm font-semibold text-zinc-800">Descrição</label>
                        <textarea id="message" name="message" required rows="7" class="block w-full resize-y rounded-md border border-zinc-300 bg-white px-3 py-3 text-zinc-950 shadow-sm outline-none transition placeholder:text-zinc-400 focus:border-teal-600 focus:ring-4 focus:ring-teal-100">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="attachments" class="mb-2 block text-sm font-semibold text-zinc-800">Anexos</label>
                        <div class="rounded-md border border-dashed border-zinc-300 bg-zinc-50 px-4 py-4">
                            <input id="attachments" name="attachments[]" type="file" multiple class="block w-full text-sm text-zinc-700 file:mr-4 file:rounded-md file:border-0 file:bg-teal-700 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-teal-800">
                            <p class="mt-3 text-xs text-zinc-500">Até 5 arquivos, 10 MB por arquivo.</p>
                        </div>
                        @error('attachments')
                            <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>
                        @enderror
                        @error('attachments.*')
                            <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-7 flex flex-col-reverse gap-3 border-t border-zinc-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-zinc-500">O protocolo será exibido após o envio.</p>
                    <button type="submit" class="inline-flex h-12 items-center justify-center rounded-md bg-teal-700 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-4 focus:ring-teal-100">
                        Enviar solicitação
                    </button>
                </div>
            </form>
        </section>

        <aside class="space-y-5">
            <section class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold tracking-normal text-zinc-950">Acompanhamento</h2>
                <div class="mt-5 space-y-4">
                    <div class="flex gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-teal-50 text-sm font-bold text-teal-700">1</div>
                        <div>
                            <p class="font-semibold text-zinc-900">Protocolo</p>
                            <p class="mt-1 text-sm leading-5 text-zinc-600">Guarde o número gerado na confirmação.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-amber-50 text-sm font-bold text-amber-700">2</div>
                        <div>
                            <p class="font-semibold text-zinc-900">Triagem</p>
                            <p class="mt-1 text-sm leading-5 text-zinc-600">A equipe recebe a solicitação como novo atendimento.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-sky-50 text-sm font-bold text-sky-700">3</div>
                        <div>
                            <p class="font-semibold text-zinc-900">Retorno</p>
                            <p class="mt-1 text-sm leading-5 text-zinc-600">As respostas ficam no histórico do atendimento.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-lg border border-zinc-200 bg-zinc-950 p-5 text-white shadow-sm">
                <p class="text-sm font-semibold text-teal-200">Atendimento digital</p>
                <p class="mt-2 text-sm leading-6 text-zinc-300">Solicitações enviadas por este formulário entram diretamente na fila da central.</p>
            </section>
        </aside>
    </main>
</body>
</html>
