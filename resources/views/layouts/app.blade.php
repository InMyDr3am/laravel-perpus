<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Perpustakaan') &middot; {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full bg-slate-100 text-slate-800">
<div class="min-h-full">
    <nav class="bg-slate-900 text-slate-100">
        <div class="mx-auto max-w-6xl px-4">
            <div class="flex h-14 items-center justify-between">
                <div class="flex items-center gap-6">
                    <a href="{{ route('dashboard') }}" class="font-semibold tracking-tight">📚 Perpustakaan</a>
                    <div class="hidden gap-1 sm:flex">
                        @php($nav = ['dashboard' => 'Dashboard', 'books.index' => 'Buku', 'categories.index' => 'Kategori', 'members.index' => 'Anggota', 'loans.index' => 'Peminjaman'])
                        @foreach ($nav as $route => $label)
                            @php($active = request()->routeIs(Str::before($route, '.').'*') || request()->routeIs($route))
                            <a href="{{ route($route) }}"
                               class="rounded px-3 py-1.5 text-sm {{ $active ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-800' }}">{{ $label }}</a>
                        @endforeach
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded px-3 py-1.5 text-sm text-slate-300 hover:bg-slate-800">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-6xl px-4 py-8">
        <div class="mb-6 flex items-center justify-between gap-4">
            <h1 class="text-xl font-semibold text-slate-900">@yield('title')</h1>
            @yield('actions')
        </div>

        @include('partials.flash')

        @yield('content')
    </main>
</div>

@stack('modals')

<script>
    (function () {
        const show = (m) => { m.classList.remove('hidden'); m.classList.add('flex'); };
        const hide = (m) => { m.classList.add('hidden'); m.classList.remove('flex'); };

        // Buka modal dan isi form sesuai tombol (tambah = kosong, ubah = data baris).
        function openFromTrigger(trigger) {
            const modal = document.getElementById(trigger.dataset.open);
            if (!modal) return;

            const form = modal.querySelector('form');
            form.reset();
            form.setAttribute('action', trigger.dataset.action);
            form.querySelector('input[name="_method"]').value = trigger.dataset.method || 'POST';
            if (form.elements['form_action']) form.elements['form_action'].value = trigger.dataset.action;

            let item = {};
            try { item = JSON.parse(trigger.dataset.item || '{}'); } catch (e) {}
            for (const [key, value] of Object.entries(item)) {
                if (form.elements[key]) form.elements[key].value = value ?? '';
            }

            const title = modal.querySelector('[data-modal-title]');
            if (title && trigger.dataset.label) title.textContent = trigger.dataset.label;

            show(modal);
        }

        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-open]');
            if (trigger) { openFromTrigger(trigger); return; }

            const closer = e.target.closest('[data-close]');
            if (closer) { hide(closer.closest('[data-modal]')); return; }

            if (e.target.matches('[data-modal]')) hide(e.target); // klik latar
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') document.querySelectorAll('[data-modal]').forEach(hide);
        });

        // Buka kembali modal saat validasi gagal (server menandai lewat window.__reopen).
        if (window.__reopen) {
            const modal = document.getElementById(window.__reopen.id);
            if (modal) {
                const form = modal.querySelector('form');
                form.setAttribute('action', window.__reopen.action);
                form.querySelector('input[name="_method"]').value = window.__reopen.method;
                const title = modal.querySelector('[data-modal-title]');
                if (title && window.__reopen.label) title.textContent = window.__reopen.label;
                show(modal);
            }
        }
    })();
</script>
</body>
</html>
