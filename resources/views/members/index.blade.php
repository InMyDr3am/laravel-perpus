@extends('layouts.app')

@section('title', 'Anggota')

@section('actions')
    <button type="button"
            data-open="memberModal" data-action="{{ route('members.store') }}" data-method="POST" data-label="Tambah Anggota"
            class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">+ Tambah Anggota</button>
@endsection

@section('content')
    <form method="GET" class="mb-4">
        <input name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode anggota..."
               class="w-full max-w-sm rounded-md border border-slate-300 px-3 py-2 text-sm">
    </form>

    <div class="overflow-hidden rounded-lg bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Kontak</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($members as $member)
                    @php($item = $member->only(['name', 'email', 'phone', 'address']))
                    <tr>
                        <td class="px-4 py-3 font-mono text-slate-600">{{ $member->code }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $member->name }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            {{ $member->phone ?: '—' }}
                            @if ($member->email)<div class="text-xs text-slate-400">{{ $member->email }}</div>@endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <button type="button" class="text-slate-500 hover:text-slate-900"
                                        data-open="memberModal" data-action="{{ route('members.update', $member) }}" data-method="PUT" data-label="Ubah Anggota"
                                        data-item='@json($item)'>Ubah</button>
                                <form method="POST" action="{{ route('members.destroy', $member) }}"
                                      onsubmit="return confirm('Hapus anggota ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400">Belum ada anggota.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $members->links() }}</div>
@endsection

@push('modals')
    <x-modal id="memberModal" title="Tambah Anggota">
        <form method="POST" action="{{ route('members.store') }}">
            @include('members._form')
        </form>
    </x-modal>

    @if ($errors->any())
        <script>
            window.__reopen = {
                id: 'memberModal',
                action: @json(old('form_action', route('members.store'))),
                method: @json(old('_method', 'POST')),
                label: @json(old('_method') === 'PUT' ? 'Ubah Anggota' : 'Tambah Anggota'),
            };
        </script>
    @endif
@endpush
