@extends('layouts.admin')
@section('title', (isset($category) ? 'Edit' : 'Tambah') . ' Kategori')

@section('content')
<div style="max-width:640px;">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
    <a href="{{ route('admin.categories.index') }}" class="adm-btn adm-btn-ghost adm-btn-sm"><i class="fa fa-arrow-left"></i></a>
    <div>
      <h1 style="font-size:1.3rem;font-weight:800;color:#f1f5f9;margin:0;">
        {{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
      </h1>
      <p style="color:#64748b;margin:2px 0 0;font-size:0.85rem;">{{ isset($category) ? $category->name : 'Buat kategori forum baru' }}</p>
    </div>
  </div>

  <div class="adm-card">
    <div class="adm-card-header">
      <h3><i class="fa fa-folder" style="color:var(--adm-yellow);margin-right:8px;"></i>Detail Kategori</h3>
    </div>
    <div class="adm-card-body">
      <form method="POST" action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
        @csrf
        @if(isset($category)) @method('PUT') @endif

        <div class="adm-form-group">
          <label class="adm-label">Nama Kategori <span style="color:var(--adm-red);">*</span></label>
          <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" class="adm-input" required placeholder="cth: Diskusi Umum">
          @error('name')<div class="adm-error">{{ $message }}</div>@enderror
        </div>

        <div class="adm-form-group">
          <label class="adm-label">Deskripsi</label>
          <textarea name="description" rows="3" class="adm-input" placeholder="Deskripsi singkat kategori...">{{ old('description', $category->description ?? '') }}</textarea>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div class="adm-form-group">
            <label class="adm-label">Icon (Font Awesome)</label>
            <input type="text" name="icon" value="{{ old('icon', $category->icon ?? 'fa-comments') }}" class="adm-input" placeholder="fa-comments">
            <div style="margin-top:6px;font-size:0.75rem;color:#64748b;"><i class="fa {{ old('icon', $category->icon ?? 'fa-comments') }}" id="iconPreview" style="color:var(--adm-yellow);"></i> Preview</div>
          </div>
          <div class="adm-form-group">
            <label class="adm-label">Warna</label>
            <div style="display:flex;gap:8px;align-items:center;">
              <input type="color" name="color" id="colorPicker" value="{{ old('color', $category->color ?? '#2563EB') }}" style="width:46px;height:40px;border:none;border-radius:8px;cursor:pointer;background:transparent;padding:2px;">
              <input type="text" id="colorText" value="{{ old('color', $category->color ?? '#2563EB') }}" class="adm-input" style="flex:1;" readonly>
            </div>
          </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div class="adm-form-group">
            <label class="adm-label">Urutan Tampil</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" class="adm-input" min="0">
          </div>
          <div class="adm-form-group" style="display:flex;align-items:flex-end;padding-bottom:4px;">
            <label class="adm-check" style="margin:0;">
              <input type="hidden" name="is_active" value="0">
              <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--adm-blue);cursor:pointer;">
              <span style="color:#e2e8f0;font-size:0.9rem;font-weight:600;">Aktif (tampil di forum)</span>
            </label>
          </div>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px;">
          <button type="submit" class="adm-btn adm-btn-primary">
            <i class="fa fa-save"></i> {{ isset($category) ? 'Perbarui Kategori' : 'Simpan Kategori' }}
          </button>
          <a href="{{ route('admin.categories.index') }}" class="adm-btn adm-btn-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
const picker = document.getElementById('colorPicker');
const text = document.getElementById('colorText');
picker.addEventListener('input', () => text.value = picker.value);

const iconInput = document.querySelector('input[name="icon"]');
iconInput.addEventListener('input', () => {
  document.getElementById('iconPreview').className = 'fa ' + iconInput.value;
});
</script>
@endsection
