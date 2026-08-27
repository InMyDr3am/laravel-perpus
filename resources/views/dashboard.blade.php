@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @php($cards = [
            ['Total Buku', $stats['books'], 'text-slate-900'],
            ['Total Anggota', $stats['members'], 'text-slate-900'],
            ['Peminjaman Aktif', $stats['active_loans'], 'text-blue-600'],
            ['Terlambat', $stats['overdue_loans'], 'text-red-600'],
        ])
        @foreach ($cards as [$label, $value, $color])
            <div class="rounded-lg bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">{{ $label }}</div>
                <div class="mt-1 text-3xl font-semibold {{ $color }}">{{ $value }}</div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 rounded-lg bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="font-medium text-slate-900">Peminjaman Terbaru</h2>
        </div>
        @forelse ($recentLoans as $loan)
            <div class="flex items-center justify-between border-b border-slate-50 px-5 py-3 text-sm last:border-0">
                <div>
                    <div class="font-medium text-slate-800">{{ $loan->book->title }}</div>
                    <div class="text-slate-500">{{ $loan->member->name }}</div>
                </div>
                <div class="text-right">
                    @if ($loan->isReturned())
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600">Dikembalikan</span>
                    @elseif ($loan->due_at->isPast())
                        <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs text-red-700">Terlambat</span>
                    @else
                        <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs text-blue-700">Dipinjam</span>
                    @endif
                    <div class="mt-1 text-xs text-slate-400">{{ $loan->borrowed_at->format('d M Y') }}</div>
                </div>
            </div>
        @empty
            <div class="px-5 py-8 text-center text-sm text-slate-400">Belum ada peminjaman.</div>
        @endforelse
    </div>
@endsection
