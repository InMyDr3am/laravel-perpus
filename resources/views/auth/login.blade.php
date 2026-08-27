<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk &middot; {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-full items-center justify-center bg-slate-100">
    <div class="w-full max-w-sm">
        <div class="mb-6 text-center">
            <div class="text-3xl">📚</div>
            <h1 class="mt-2 text-lg font-semibold text-slate-900">{{ config('app.name') }}</h1>
            <p class="text-sm text-slate-500">Masuk untuk mengelola perpustakaan</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4 rounded-lg bg-white p-6 shadow-sm">
            @csrf

            @error('email')
                <p class="rounded bg-red-50 px-3 py-2 text-sm text-red-700">{{ $message }}</p>
            @enderror

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                       class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:ring-slate-500">
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="password">Kata Sandi</label>
                <input id="password" name="password" type="password" required
                       class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:ring-slate-500">
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-slate-300"> Ingat saya
            </label>

            <button class="w-full rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
                Masuk
            </button>
        </form>
    </div>
</body>
</html>
