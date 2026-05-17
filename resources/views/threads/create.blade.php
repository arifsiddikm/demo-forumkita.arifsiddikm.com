@extends('layouts.app')
@section('title', isset($thread) ? 'Edit Thread' : 'Buat Thread Baru')

@push('head')
{{-- CKEditor 5 Classic CDN - loaded in head --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
@endpush

@section('content')
<div style="max-width:960px;margin:0 auto;">

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
        <a href="{{ url()->previous() }}" class="btn btn-outline btn-sm"><i class="fa fa-arrow-left"></i></a>
        <div>
            <h1 style="font-size:1.4rem;font-weight:800;color:var(--fk-gray-900);margin:0;">
                {{ isset($thread) ? 'Edit Thread' : 'Buat Thread Baru' }}
            </h1>
            <p style="font-size:0.85rem;color:var(--fk-gray-500);margin:0;">
                {{ isset($thread) ? 'Ubah isi thread kamu' : 'Bagikan topik diskusi ke komunitas ForumKita' }}
            </p>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

        {{-- Main Form --}}
        <div class="card">
            <div class="card-body" style="padding:28px;">

                @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fa fa-circle-exclamation" style="flex-shrink:0;"></i>
                    <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
                </div>
                @endif

                <form action="{{ isset($thread) ? route('threads.update', $thread->slug) : route('threads.store') }}"
                      method="POST" enctype="multipart/form-data" id="threadForm">
                    @csrf
                    @if(isset($thread)) @method('PUT') @endif

                    {{-- Title --}}
                    <div class="form-group">
                        <label class="form-label" for="title">
                            Judul Thread <span style="color:#EF4444;">*</span>
                        </label>
                        <input type="text" name="title" id="title"
                               class="form-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
                               placeholder="Tulis judul yang jelas dan menarik..."
                               value="{{ old('title', $thread->title ?? '') }}"
                               required maxlength="200" autocomplete="off">
                        <div style="display:flex;justify-content:flex-end;font-size:0.72rem;color:var(--fk-gray-400);margin-top:3px;">
                            <span id="titleCount">0</span>/200 karakter
                        </div>
                        @error('title') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    {{-- Category + Tags --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div class="form-group">
                            <label class="form-label" for="category_id">
                                Kategori <span style="color:#EF4444;">*</span>
                            </label>
                            <select name="category_id" id="category_id"
                                    class="form-select {{ $errors->has('category_id') ? 'is-invalid' : '' }}" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $thread->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="tags">
                                Tag <span style="color:var(--fk-gray-400);font-weight:400;">(pisahkan koma)</span>
                            </label>
                            <input type="text" name="tags" id="tags" class="form-input"
                                   placeholder="teknologi, tips, android"
                                   value="{{ old('tags', isset($thread) ? $thread->tags->pluck('name')->implode(', ') : '') }}">
                        </div>
                    </div>

                    {{-- Thumbnail Upload --}}
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fa fa-image" style="color:var(--fk-blue);"></i>
                            Gambar Thumbnail <span style="color:var(--fk-gray-400);font-weight:400;">(opsional, max 3MB)</span>
                        </label>

                        {{-- Existing thumbnail --}}
                        @if(isset($thread) && $thread->thumbnail)
                        <div style="margin-bottom:10px;">
                            <img src="{{ Storage::url($thread->thumbnail) }}" style="max-height:160px;border-radius:10px;border:2px solid var(--fk-gray-200);">
                            <label style="display:flex;align-items:center;gap:6px;margin-top:6px;cursor:pointer;font-size:0.82rem;color:#EF4444;">
                                <input type="checkbox" name="remove_thumbnail" value="1"> Hapus thumbnail
                            </label>
                        </div>
                        @endif

                        <div id="thumbnailDropZone"
                             style="border:2px dashed var(--fk-gray-200);border-radius:12px;padding:24px;text-align:center;cursor:pointer;transition:all 0.2s;background:var(--fk-gray-50);"
                             ondragover="event.preventDefault();this.style.borderColor='var(--fk-blue)';this.style.background='#eff6ff'"
                             ondragleave="this.style.borderColor='var(--fk-gray-200)';this.style.background='var(--fk-gray-50)'"
                             ondrop="handleThumbDrop(event)"
                             onclick="document.getElementById('thumbnailInput').click()">
                            <div id="thumbPreviewWrap" style="display:none;margin-bottom:10px;">
                                <img id="thumbPreview" src="" style="max-height:180px;border-radius:8px;max-width:100%;">
                            </div>
                            <div id="thumbPlaceholder">
                                <i class="fa fa-cloud-arrow-up" style="font-size:2rem;color:var(--fk-gray-300);margin-bottom:8px;display:block;"></i>
                                <div style="font-weight:600;color:var(--fk-gray-600);font-size:0.875rem;">Drag & drop gambar atau klik untuk upload</div>
                                <div style="font-size:0.78rem;color:var(--fk-gray-400);margin-top:4px;">JPG, PNG, GIF, WebP — maks 3MB</div>
                            </div>
                        </div>
                        <input type="file" id="thumbnailInput" name="thumbnail"
                               accept="image/jpeg,image/png,image/gif,image/webp"
                               style="display:none;" onchange="previewThumbnail(this)">
                        @error('thumbnail') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    {{-- Body / CKEditor --}}
                    <div class="form-group">
                        <label class="form-label">
                            Isi Thread <span style="color:#EF4444;">*</span>
                        </label>
                        <div id="editorToolbar"></div>
                        <textarea name="body" id="bodyEditor"
                                  class="form-textarea {{ $errors->has('body') ? 'is-invalid' : '' }}"
                                  style="display:none;">{{ old('body', $thread->body ?? '') }}</textarea>
                        <div id="editorContainer" style="border:1px solid var(--fk-gray-200);border-radius:10px;overflow:hidden;{{ $errors->has('body') ? 'border-color:#EF4444;' : '' }}">
                        </div>
                        @error('body') <div class="form-error">{{ $message }}</div> @enderror
                        <div style="font-size:0.72rem;color:var(--fk-gray-400);margin-top:4px;">
                            <i class="fa fa-info-circle"></i>
                            Kamu bisa paste gambar langsung (Ctrl+V), drag & drop gambar, atau gunakan tombol gambar di toolbar.
                        </div>
                    </div>

                    {{-- Admin Options --}}
                    @if(auth()->user()->is_admin)
                    <div style="background:var(--fk-gray-50);border:1px solid var(--fk-gray-200);border-radius:10px;padding:16px;margin-bottom:18px;">
                        <div style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:0.85rem;color:var(--fk-gray-700);margin-bottom:12px;">
                            <i class="fa fa-shield-halved" style="color:var(--fk-yellow-dark);margin-right:6px;"></i>Opsi Admin
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                            <label class="form-check">
                                <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned', $thread->is_pinned ?? false) ? 'checked' : '' }}>
                                <span><i class="fa fa-thumbtack" style="color:var(--fk-yellow-dark);"></i> Pinned</span>
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="is_announcement" value="1" {{ old('is_announcement', $thread->is_announcement ?? false) ? 'checked' : '' }}>
                                <span><i class="fa fa-bullhorn" style="color:#7C3AED;"></i> Pengumuman</span>
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="is_locked" value="1" {{ old('is_locked', $thread->is_locked ?? false) ? 'checked' : '' }}>
                                <span><i class="fa fa-lock" style="color:#EF4444;"></i> Kunci Thread</span>
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="is_hot" value="1" {{ old('is_hot', $thread->is_hot ?? false) ? 'checked' : '' }}>
                                <span><i class="fa fa-fire" style="color:#EF4444;"></i> Tandai Hot</span>
                            </label>
                        </div>
                    </div>
                    @endif

                    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                        <a href="{{ url()->previous() }}" class="btn btn-outline">Batal</a>
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            <i class="fa fa-{{ isset($thread) ? 'save' : 'paper-plane' }}"></i>
                            {{ isset($thread) ? 'Simpan Perubahan' : 'Publikasikan Thread' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar --}}
        <div>
            <div class="sidebar-section">
                <div class="sidebar-header"><i class="fa fa-lightbulb"></i> Tips Membuat Thread</div>
                <div class="sidebar-body">
                    <div style="display:flex;flex-direction:column;gap:12px;">
                        @foreach([
                            ['fa-heading','Judul Jelas','Buat judul yang spesifik dan informatif.'],
                            ['fa-image','Tambah Gambar','Upload thumbnail agar thread lebih menarik.'],
                            ['fa-folder','Kategori Tepat','Pilih kategori yang sesuai topik.'],
                            ['fa-align-left','Isi Lengkap','Berikan konteks dan informasi yang cukup.'],
                            ['fa-tags','Tag Relevan','Tambahkan tag untuk meningkatkan visibilitas.'],
                        ] as $tip)
                        <div style="display:flex;gap:10px;align-items:flex-start;">
                            <div style="width:30px;height:30px;background:var(--fk-blue-light);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fa {{ $tip[0] }}" style="color:var(--fk-blue);font-size:0.75rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:0.82rem;color:var(--fk-gray-800);">{{ $tip[1] }}</div>
                                <div style="font-size:0.78rem;color:var(--fk-gray-500);">{{ $tip[2] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="sidebar-section" style="margin-top:16px;">
                <div class="sidebar-header" style="background:linear-gradient(135deg,#EAB30820,#EAB30840);color:#92400e;">
                    <i class="fa fa-exclamation-triangle" style="color:#D97706;"></i> Peraturan
                </div>
                <div class="sidebar-body">
                    <ul style="padding-left:16px;font-size:0.82rem;color:var(--fk-gray-600);line-height:2;margin:0;">
                        <li>Dilarang SARA dan ujaran kebencian</li>
                        <li>Dilarang spam dan promosi berlebihan</li>
                        <li>Jaga privasi diri dan orang lain</li>
                        <li>Gunakan bahasa yang santun</li>
                        <li>Max ukuran gambar 3MB per file</li>
                    </ul>
                </div>
            </div>

            {{-- Upload progress indicator --}}
            <div id="uploadProgress" style="display:none;margin-top:16px;">
                <div class="card" style="padding:16px;text-align:center;">
                    <i class="fa fa-spinner fa-spin" style="color:var(--fk-blue);font-size:1.5rem;margin-bottom:8px;display:block;"></i>
                    <div style="font-size:0.85rem;color:var(--fk-gray-600);">Mengupload gambar...</div>
                    <div id="uploadProgressBar" style="height:4px;background:var(--fk-gray-100);border-radius:2px;margin-top:10px;overflow:hidden;">
                        <div id="uploadProgressFill" style="height:100%;background:var(--fk-blue);width:0%;transition:width 0.3s;border-radius:2px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CKEditor styles --}}
<style>
/* ===== CKEditor Border Fix ===== */
#editorContainer {
    border: 1.5px solid #d1d5db !important;
    border-radius: 10px !important;
    overflow: hidden !important;
    transition: border-color 0.2s, box-shadow 0.2s !important;
    background: white !important;
}
#editorContainer:focus-within {
    border-color: #2563EB !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
}
/* Override semua border bawaan CKEditor */
#editorContainer .ck.ck-editor,
#editorContainer .ck.ck-editor__top,
#editorContainer .ck.ck-editor__main,
#editorContainer .ck.ck-editor__editable {
    border: none !important;
    box-shadow: none !important;
    border-radius: 0 !important;
}
#editorContainer .ck.ck-toolbar {
    border: none !important;
    border-bottom: 1px solid #e5e7eb !important;
    background: #f9fafb !important;
    padding: 6px 12px !important;
    border-radius: 0 !important;
}
#editorContainer .ck-editor__editable_inline {
    min-height: 340px !important;
    font-family: 'Nunito', sans-serif !important;
    font-size: 0.95rem !important;
    line-height: 1.8 !important;
    color: #374151 !important;
    padding: 16px 20px !important;
    border: none !important;
}
#editorContainer .ck-editor__editable_inline img {
    max-width: 100% !important;
    border-radius: 8px !important;
    height: auto !important;
    display: block !important;
    margin: 8px auto !important;
    cursor: pointer !important;
}
/* Fix upload loading placeholder */
.ck-widget.ck-widget_selected .ck-widget__type-around,
.ck-image__upload-complete-icon { display: none !important; }
</style>

@push('scripts')
<script>
// ============================================================
// ForumKita Custom Upload Adapter untuk CKEditor 5
// Fix: pakai URL relatif agar tidak masalah beda port/domain
// ============================================================
class ForumKitaUploadAdapter {
    constructor(loader) {
        this.loader = loader;
        // URL endpoint upload
        this.uploadUrl = '{{ route("upload.image") }}';
        // CSRF Token dari meta tag (DOM sudah ready saat ini dipanggil)
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    upload() {
        return this.loader.file.then(file => {
            return new Promise((resolve, reject) => {
                const formData = new FormData();
                formData.append('upload', file);

                // Track upload progress
                const xhr = new XMLHttpRequest();

                xhr.upload.addEventListener('progress', evt => {
                    if (evt.lengthComputable) {
                        this.loader.uploadTotal = evt.total;
                        this.loader.uploaded = evt.loaded;
                    }
                });

                xhr.addEventListener('load', () => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.url) {
                                console.log('[FK Upload] Success:', response.url);
                                // Resolve dengan URL relatif — browser akan resolve ke domain saat ini
                                resolve({ default: response.url });
                            } else {
                                console.error('[FK Upload] No URL in response:', response);
                                reject(response.message || 'Server tidak mengembalikan URL gambar.');
                            }
                        } catch(e) {
                            console.error('[FK Upload] JSON parse error:', xhr.responseText);
                            reject('Response tidak valid dari server.');
                        }
                    } else {
                        console.error('[FK Upload] HTTP Error:', xhr.status, xhr.responseText);
                        reject(`Upload gagal (HTTP ${xhr.status}). Coba lagi.`);
                    }
                });

                xhr.addEventListener('error', () => {
                    console.error('[FK Upload] Network error');
                    reject('Koneksi gagal saat upload gambar.');
                });

                xhr.addEventListener('abort', () => reject('Upload dibatalkan.'));

                xhr.open('POST', this.uploadUrl, true);
                xhr.setRequestHeader('X-CSRF-TOKEN', this.csrfToken);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.send(formData);
            });
        });
    }

    abort() {
        if (this.xhr) this.xhr.abort();
    }
}

// Plugin yang di-inject ke CKEditor
function ForumKitaUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        return new ForumKitaUploadAdapter(loader);
    };
}

// ============================================================
// Inisialisasi CKEditor
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    const editorContainer = document.querySelector('#editorContainer');
    const bodyTextarea    = document.querySelector('#bodyEditor');
    const uploadProgress  = document.getElementById('uploadProgress');

    if (!editorContainer) return;

    ClassicEditor.create(editorContainer, {
        extraPlugins: [ForumKitaUploadAdapterPlugin],
        toolbar: {
            items: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'bulletedList', 'numberedList', '|',
                'blockQuote', 'link', '|',
                'uploadImage', 'insertTable', '|',
                'code', 'codeBlock', '|',
                'undo', 'redo'
            ]
        },
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph' },
                { model: 'heading2', view: 'h2', title: 'Judul Besar', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Judul Sedang', class: 'ck-heading_heading3' },
            ]
        },
        image: {
            toolbar: ['imageStyle:block', 'imageStyle:side', '|', 'imageTextAlternative']
        },
        table: {
            contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
        },
        language: 'en',
        placeholder: 'Tulis isi thread di sini. Paste gambar (Ctrl+V) atau klik tombol 🖼 di toolbar untuk upload gambar...',
        initialData: bodyTextarea ? bodyTextarea.value : '',
    }).then(editor => {
        window.bodyEditorInstance = editor;

        // Sync textarea saat submit
        const form = document.getElementById('threadForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const data = editor.getData();
                if (!data || data.trim() === '' || data.trim() === '<p>&nbsp;</p>') {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Isi thread kosong!',
                        text: 'Silakan isi konten thread terlebih dahulu.',
                        confirmButtonColor: '#2563EB',
                    });
                    return;
                }
                if (bodyTextarea) bodyTextarea.value = data;
            });
        }

        // Upload progress indicator
        const fileRepo = editor.plugins.get('FileRepository');
        const showProgress = () => {
            const loaders = Array.from(fileRepo.loaders);
            const isUploading = loaders.some(l => ['reading','uploading'].includes(l.status));
            if (uploadProgress) uploadProgress.style.display = isUploading ? 'block' : 'none';
        };

        editor.model.document.on('change', showProgress);

        console.log('[FK] CKEditor siap. Upload adapter aktif.');

    }).catch(err => {
        console.error('[FK] CKEditor gagal inisialisasi:', err);
        // Fallback: tampilkan textarea biasa
        if (bodyTextarea) {
            bodyTextarea.style.display = 'block';
            bodyTextarea.style.minHeight = '300px';
        }
        if (editorContainer) editorContainer.style.display = 'none';
    });

    // ============================================================
    // Thumbnail drag & drop
    // ============================================================
    function previewThumbnail(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        if (file.size > 3 * 1024 * 1024) {
            Swal.fire({ icon:'warning', title:'Gambar terlalu besar', text:'Maksimal 3MB.', confirmButtonColor:'#2563EB' });
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('thumbPreview');
            const wrap    = document.getElementById('thumbPreviewWrap');
            const ph      = document.getElementById('thumbPlaceholder');
            const zone    = document.getElementById('thumbnailDropZone');
            if (preview) preview.src = e.target.result;
            if (wrap)    wrap.style.display    = 'block';
            if (ph)      ph.style.display      = 'none';
            if (zone) {
                zone.style.borderColor = '#2563EB';
                zone.style.background  = '#eff6ff';
            }
        };
        reader.readAsDataURL(file);
    }

    // Expose to global scope for inline handlers
    window.previewThumbnail = previewThumbnail;
    window.handleThumbDrop = function(e) {
        e.preventDefault();
        const zone = document.getElementById('thumbnailDropZone');
        if (zone) { zone.style.borderColor = '#d1d5db'; zone.style.background = '#f9fafb'; }
        const files = e.dataTransfer?.files;
        if (files && files.length > 0 && files[0].type.startsWith('image/')) {
            const dt = new DataTransfer();
            dt.items.add(files[0]);
            const inp = document.getElementById('thumbnailInput');
            if (inp) { inp.files = dt.files; previewThumbnail(inp); }
        }
    };

    // ============================================================
    // Title counter
    // ============================================================
    const titleEl    = document.getElementById('title');
    const titleCount = document.getElementById('titleCount');
    if (titleEl && titleCount) {
        const updateCount = () => {
            const len = titleEl.value.length;
            titleCount.textContent = len;
            titleCount.style.color = len > 180 ? '#EF4444' : len > 150 ? '#D97706' : '#94a3b8';
        };
        titleEl.addEventListener('input', updateCount);
        updateCount();
    }
});
</script>
@endpush

@endsection
