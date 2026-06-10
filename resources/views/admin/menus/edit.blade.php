@extends('admin.layout')
@section('content')

<!-- Back Navigation & Header -->
<div class="mb-6">
    <a href="/admin/menus" class="inline-flex items-center gap-2 text-xs font-bold text-slate-550 hover:text-orange-500 transition mb-3">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Kembali ke Katalog</span>
    </a>
    <h1 class="text-2xl lg:text-3xl font-bold text-slate-800 tracking-tight">Edit Menu</h1>
    <p class="text-slate-500 mt-1 font-medium text-xs sm:text-sm">Perbarui spesifikasi produk katering: <span class="text-orange-500 font-bold">{{ $menu->name }}</span></p>
</div>

<!-- Form Container -->
<div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm max-w-3xl overflow-hidden mb-8">
    <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
        <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-lg flex items-center justify-center shrink-0 border border-orange-100/50">
            <i class="fa-solid fa-pen-to-square text-sm"></i>
        </div>
        <div>
            <h2 class="text-sm font-bold text-slate-800">Edit Spesifikasi Menu</h2>
            <p class="text-[10px] sm:text-xs text-slate-500 font-medium">Perubahan akan langsung tampil di menu publik setelah disimpan.</p>
        </div>
    </div>

    <form action="/admin/menus/{{ $menu->id }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
        @csrf
        @method('POST')

        <!-- Row 1: Nama Sajian -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Sajian (Judul) <span class="text-red-500">*</span></label>
            <input type="text" name="name" required value="{{ old('name', $menu->name) }}"
                class="w-full border border-slate-200 bg-slate-550/30 p-3 rounded-xl focus:bg-white focus:border-orange-500 transition outline-none font-medium text-slate-800 text-xs sm:text-sm @error('name') border-red-400 @enderror"
                placeholder="Misal: Nasi Kotak Ayam Bakar Taliwang">
            @error('name')
                <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Row 2: Kategori & Harga -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Golongan Kategori <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="category"
                        class="w-full border border-slate-200 bg-slate-550/30 p-3 rounded-xl focus:bg-white focus:border-orange-500 transition outline-none font-semibold text-slate-750 cursor-pointer appearance-none text-xs sm:text-sm">
                        <option value="makanan" {{ old('category', $menu->category) == 'makanan' ? 'selected' : '' }}>🍛 MAKANAN UTAMA</option>
                        <option value="snack"   {{ old('category', $menu->category) == 'snack'   ? 'selected' : '' }}>🥨 SNACK / JAJANAN</option>
                    </select>
                    <div class="absolute inset-y-0 right-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Harga Jual (Net) <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none font-bold text-slate-400 text-xs sm:text-sm">Rp</span>
                    <input type="number" name="price" required value="{{ old('price', $menu->price) }}"
                        class="w-full pl-9 pr-4 py-3 border border-slate-200 bg-slate-550/30 rounded-xl focus:bg-white focus:border-orange-500 transition outline-none font-bold text-slate-800 text-xs sm:text-sm @error('price') border-red-400 @enderror"
                        placeholder="0">
                </div>
                @error('price')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Row 3: Foto Produk -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Foto Produk</label>

            <!-- Gambar Saat Ini -->
            <div id="currentImageBox" class="mb-3 p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-3">
                <img id="currentImg"
                    src="{{ $menu->image }}"
                    alt="{{ $menu->name }}"
                    class="w-20 h-20 rounded-xl object-cover border border-slate-200 shadow-sm shrink-0"
                    onerror="this.src='https://placehold.co/80x80/f1f5f9/94a3b8?text=No+Img'">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-700">Foto saat ini</p>
                    <p class="text-[10px] text-slate-500 mt-0.5 truncate">{{ $menu->image }}</p>
                    <p class="text-[10px] text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengganti foto.</p>
                </div>
            </div>

            <!-- Drop Zone (untuk ganti gambar) -->
            <div id="dropZone" onclick="document.getElementById('imageInput').click()"
                class="relative border-2 border-dashed border-slate-200 rounded-xl p-5 cursor-pointer hover:border-orange-400 hover:bg-orange-50/30 transition-all group">

                <!-- Preview gambar baru (hidden by default) -->
                <div id="imagePreview" class="hidden">
                    <div class="flex items-center gap-4">
                        <img id="previewImg" src="" alt="Preview" class="w-24 h-24 rounded-xl object-cover border border-slate-200 shadow-sm shrink-0">
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider mb-0.5">✓ Foto baru dipilih</p>
                            <p id="fileName" class="font-bold text-slate-800 text-xs sm:text-sm truncate"></p>
                            <p id="fileSize" class="text-[10px] sm:text-xs text-slate-500 mt-0.5"></p>
                            <button type="button" onclick="clearImage(event)"
                                class="mt-2 inline-flex items-center gap-1 text-[10px] font-semibold text-red-600 bg-red-50 hover:bg-red-100 border border-red-100 px-2.5 py-1 rounded-lg transition">
                                <i class="fa-solid fa-xmark text-[9px]"></i> Batalkan Ganti Foto
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Placeholder -->
                <div id="dropPlaceholder" class="flex flex-col items-center justify-center py-4 text-center">
                    <div class="w-12 h-12 bg-orange-50 border border-orange-100 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-arrow-up-from-bracket text-orange-400 text-lg"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-700">Klik untuk ganti foto</p>
                    <p class="text-[10px] sm:text-xs text-slate-400 mt-1">Format: JPG, PNG, WEBP — Maks. 2MB</p>
                </div>
            </div>

            <!-- Hidden file input -->
            <input type="file" id="imageInput" name="image" accept="image/jpeg,image/png,image/webp"
                class="hidden" onchange="previewImage(this)">

            @error('image')
                <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Row 4: Pita Promosi (Tag) -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pita Promosi (Tag) <span class="text-slate-400 font-medium lowercase ml-1">(opsional)</span></label>
            <div class="relative">
                <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none text-orange-400 text-xs shrink-0">
                    <i class="fa-solid fa-tag"></i>
                </span>
                <input type="text" name="tag" value="{{ old('tag', $menu->tag) }}"
                    class="w-full pl-9 pr-4 py-3 border border-slate-200 bg-slate-550/30 rounded-xl focus:bg-white focus:border-orange-500 transition outline-none font-medium text-slate-800 text-xs sm:text-sm"
                    placeholder="Misal: BEST SELLER, KEMASAN BARU, REKOMENDASI">
            </div>
        </div>

        <!-- Row 5: Deskripsi -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Produk <span class="text-red-500">*</span></label>
            <textarea name="description" required rows="4"
                class="w-full border border-slate-200 bg-slate-550/30 p-3 rounded-xl focus:bg-white focus:border-orange-500 transition outline-none resize-none font-medium text-slate-700 text-xs sm:text-sm @error('description') border-red-400 @enderror"
                placeholder="Terangkan komposisi bahan, porsi sajian, ketahanan pangan, dan rincian rasa...">{{ old('description', $menu->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Form Actions -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
            <a href="/admin/menus" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition text-xs sm:text-sm">
                Batalkan
            </a>
            <button type="submit"
                class="bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white px-6 py-2.5 rounded-xl font-bold transition shadow-sm active:scale-[0.98] flex items-center justify-center gap-1.5 text-xs sm:text-sm">
                <i class="fa-regular fa-floppy-disk text-xs"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];

    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran gambar tidak boleh lebih dari 2MB!');
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = formatFileSize(file.size);
        document.getElementById('imagePreview').classList.remove('hidden');
        document.getElementById('dropPlaceholder').classList.add('hidden');
        document.getElementById('currentImageBox').classList.add('opacity-40');
        document.getElementById('dropZone').classList.add('border-orange-400', 'bg-orange-50/30');
        document.getElementById('dropZone').classList.remove('border-dashed');
    };
    reader.readAsDataURL(file);
}

function clearImage(e) {
    e.stopPropagation();
    const input = document.getElementById('imageInput');
    input.value = '';
    document.getElementById('previewImg').src = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('dropPlaceholder').classList.remove('hidden');
    document.getElementById('currentImageBox').classList.remove('opacity-40');
    document.getElementById('dropZone').classList.remove('border-orange-400', 'bg-orange-50/30');
    document.getElementById('dropZone').classList.add('border-dashed');
}

function formatFileSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

// Drag & Drop support
const dropZone = document.getElementById('dropZone');
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-orange-400', 'bg-orange-50/50');
});
dropZone.addEventListener('dragleave', () => {
    if (!document.getElementById('imageInput').files.length) {
        dropZone.classList.remove('border-orange-400', 'bg-orange-50/50');
    }
});
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const input = document.getElementById('imageInput');
        const dt = new DataTransfer();
        dt.items.add(files[0]);
        input.files = dt.files;
        previewImage(input);
    }
});
</script>
@endpush
