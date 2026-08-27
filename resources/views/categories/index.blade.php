@extends('layouts.app')

@section('title', 'Kategori')

@section('actions')
    <button type="button"
            data-open="categoryModal" data-action="{{ route('categories.store') }}" data-method="POST" data-label="Tambah Kategori"
            class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">+ Tambah Kategori</button>
@endsection

@section('content')
    <div class="overflow-hidden rounded-lg bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3 text-center">Jumlah Buku</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-center text-slate-600">{{ $category->books_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <button type="button" class="text-slate-500 hover:text-slate-900"
                                        data-open="categoryModal" data-action="{{ route('categories.update', $category) }}" data-method="PUT" data-label="Ubah Kategori"
                                        data-item='@json($category->only(['name']))'>Ubah</button>
                                <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                      onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-8 text-center text-slate-400">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>
@endsection

@push('modals')
    <x-modal id="categoryModal" title="Tambah Kategori">
        <form method="POST" action="{{ route('categories.store') }}">
            @include('categories._form')
        </form>
    </x-modal>

    @if ($errors->any())
        <script>
            window.__reopen = {
                id: 'categoryModal',
                action: @json(old('form_action', route('categories.store'))),
                method: @json(old('_method', 'POST')),
                label: @json(old('_method') === 'PUT' ? 'Ubah Kategori' : 'Tambah Kategori'),
            };
        </script>
    @endif
@endpush
