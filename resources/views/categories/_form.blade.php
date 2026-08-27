@csrf
<input type="hidden" name="_method" value="POST">
<input type="hidden" name="form_action" value="{{ route('categories.store') }}">

@include('partials.errors')

<div>
    <label class="mb-1 block text-sm font-medium text-slate-700">Nama Kategori</label>
    <input name="name" value="{{ old('name') }}" required
           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
</div>

<div class="mt-6 flex justify-end gap-2">
    <button type="button" data-close class="rounded-md border border-slate-300 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Batal</button>
    <button class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Simpan</button>
</div>
