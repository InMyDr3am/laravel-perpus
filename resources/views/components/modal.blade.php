@props(['id', 'title' => ''])

<div id="{{ $id }}" data-modal
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="w-full max-w-2xl rounded-lg bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <h2 class="font-semibold text-slate-900" data-modal-title>{{ $title }}</h2>
            <button type="button" data-close class="text-2xl leading-none text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>
