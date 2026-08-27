@csrf
<input type="hidden" name="_method" value="POST">
<input type="hidden" name="form_action" value="{{ route('books.store') }}">

@include('partials.errors')

<div class="grid gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="mb-1 block text-sm font-medium text-slate-700">Judul</label>
        <input name="title" value="{{ old('title') }}" required
               class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Penulis</label>
        <input name="author" value="{{ old('author') }}" required
               class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Penerbit</label>
        <input name="publisher" value="{{ old('publisher') }}"
               class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Kategori</label>
        <select name="category_id" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            <option value="">— Pilih —</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">ISBN</label>
        <input name="isbn" value="{{ old('isbn') }}"
               class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Tahun Terbit</label>
        <input name="year" type="number" value="{{ old('year') }}"
               class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Jumlah Stok</label>
        <input name="stock" type="number" min="1" value="{{ old('stock', 1) }}" required
               class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>
</div>

<div class="mt-6 flex justify-end gap-2">
    <button type="button" data-close class="rounded-md border border-slate-300 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Batal</button>
    <button class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Simpan</button>
</div>
