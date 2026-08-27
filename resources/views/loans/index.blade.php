@extends('layouts.app')

@section('title', 'Peminjaman')

@section('actions')
    @if ($books->isNotEmpty())
        <button type="button"
                data-open="loanModal" data-action="{{ route('loans.store') }}" data-method="POST"
                class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">+ Pinjam Buku</button>
    @endif
@endsection

@section('content')
    <div class="mb-4 flex gap-2 text-sm">
        @php($filters = ['' => 'Semua', 'active' => 'Aktif', 'overdue' => 'Terlambat'])
        @foreach ($filters as $value => $label)
            @php($active = request('filter', '') === $value)
            <a href="{{ route('loans.index', array_filter(['filter' => $value])) }}"
               class="rounded-md px-3 py-1.5 {{ $active ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-lg bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Buku</th>
                    <th class="px-4 py-3">Anggota</th>
                    <th class="px-4 py-3">Pinjam</th>
                    <th class="px-4 py-3">Jatuh Tempo</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($loans as $loan)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $loan->book->title }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $loan->member->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $loan->borrowed_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $loan->due_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            @if ($loan->isReturned())
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600">Kembali</span>
                                @if ($loan->fine > 0)
                                    <div class="mt-1 text-xs text-red-600">Denda Rp{{ number_format($loan->fine, 0, ',', '.') }}</div>
                                @endif
                            @elseif ($loan->due_at->isPast())
                                <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs text-red-700">Terlambat</span>
                            @else
                                <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs text-blue-700">Dipinjam</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            @unless ($loan->isReturned())
                                <form method="POST" action="{{ route('loans.return', $loan) }}"
                                      onsubmit="return confirm('Kembalikan buku ini?')">
                                    @csrf @method('PATCH')
                                    <button class="rounded-md border border-slate-300 px-3 py-1 text-slate-700 hover:bg-slate-50">Kembalikan</button>
                                </form>
                            @else
                                <span class="text-slate-300">—</span>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">Tidak ada data peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $loans->links() }}</div>
@endsection

@push('modals')
    <x-modal id="loanModal" title="Pinjam Buku">
        <form method="POST" action="{{ route('loans.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="_method" value="POST">

            @include('partials.errors')

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Buku</label>
                <select name="book_id" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <option value="">— Pilih Buku —</option>
                    @foreach ($books as $book)
                        <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>
                            {{ $book->title }} (tersedia: {{ $book->available }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Anggota</label>
                <select name="member_id" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <option value="">— Pilih Anggota —</option>
                    @foreach ($members as $member)
                        <option value="{{ $member->id }}" @selected(old('member_id') == $member->id)>
                            {{ $member->code }} — {{ $member->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <p class="text-xs text-slate-500">
                Jatuh tempo dalam {{ config('library.loan_days') }} hari.
                Denda keterlambatan Rp{{ number_format(config('library.fine_per_day'), 0, ',', '.') }}/hari.
            </p>

            <div class="flex justify-end gap-2">
                <button type="button" data-close class="rounded-md border border-slate-300 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Batal</button>
                <button class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Pinjam</button>
            </div>
        </form>
    </x-modal>

    @if ($errors->any())
        <script>window.__reopen = { id: 'loanModal', action: @json(route('loans.store')), method: 'POST', label: 'Pinjam Buku' };</script>
    @endif
@endpush
