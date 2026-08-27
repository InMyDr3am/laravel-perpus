@extends('layouts.app')

@section('title', 'Buku')

@section('actions')
    <button type="button"
            data-open="bookModal" data-action="{{ route('books.store') }}" data-method="POST" data-label="Tambah Buku"
            class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">+ Tambah Buku</button>
@endsection

@section('content')
    <form method="GET" class="mb-4">
        <input name="search" value="{{ request('search') }}" placeholder="Cari judul, penulis, atau ISBN..."
               class="w-full max-w-sm rounded-md border border-slate-300 px-3 py-2 text-sm">
    </form>

    <div class="overflow-hidden rounded-lg bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Penulis</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3 text-center">Tersedia / Stok</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($books as $book)
                    @php($item = $book->only(['title', 'author', 'publisher', 'isbn', 'category_id', 'year', 'stock']))
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $book->title }}
                            @if ($book->isbn)<div class="text-xs text-slate-400">{{ $book->isbn }}</div>@endif
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $book->author }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $book->category->name }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="{{ $book->available > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">{{ $book->available }}</span>
                            <span class="text-slate-400"> / {{ $book->stock }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <button type="button" class="text-slate-500 hover:text-slate-900"
                                        data-open="bookModal" data-action="{{ route('books.update', $book) }}" data-method="PUT" data-label="Ubah Buku"
                                        data-item='@json($item)'>Ubah</button>
                                <form method="POST" action="{{ route('books.destroy', $book) }}"
                                      onsubmit="return confirm('Hapus buku ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">Belum ada buku.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $books->links() }}</div>
@endsection

@push('modals')
    <x-modal id="bookModal" title="Tambah Buku">
        <form method="POST" action="{{ route('books.store') }}">
            @include('books._form')
        </form>
    </x-modal>

    @if ($errors->any())
        <script>
            window.__reopen = {
                id: 'bookModal',
                action: @json(old('form_action', route('books.store'))),
                method: @json(old('_method', 'POST')),
                label: @json(old('_method') === 'PUT' ? 'Ubah Buku' : 'Tambah Buku'),
            };
        </script>
    @endif
@endpush
