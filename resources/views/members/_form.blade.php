@csrf
<input type="hidden" name="_method" value="POST">
<input type="hidden" name="form_action" value="{{ route('members.store') }}">

@include('partials.errors')

<div class="grid gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="mb-1 block text-sm font-medium text-slate-700">Nama</label>
        <input name="name" value="{{ old('name') }}" required
               class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
        <input name="email" type="email" value="{{ old('email') }}"
               class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Telepon</label>
        <input name="phone" value="{{ old('phone') }}"
               class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>

    <div class="sm:col-span-2">
        <label class="mb-1 block text-sm font-medium text-slate-700">Alamat</label>
        <textarea name="address" rows="2"
                  class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">{{ old('address') }}</textarea>
    </div>
</div>

<div class="mt-6 flex justify-end gap-2">
    <button type="button" data-close class="rounded-md border border-slate-300 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Batal</button>
    <button class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Simpan</button>
</div>
