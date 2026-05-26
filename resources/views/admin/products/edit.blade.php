@extends('layouts.admin')

@section('title', 'Edit Produk - Gadgetra')

@section('content')
<div class="admin-title-row">
    <h1 class="admin-title">Edit Produk</h1>
</div>

<div class="double-bezel-wrapper">
    <div class="double-bezel-inner">
        <div class="product-manage-layout">
            <aside class="product-manage-nav">
                <a href="{{ route('admin.products.create') }}" class="product-manage-nav-item">
                    <i class="fas fa-plus"></i>
                    <span>Tambah</span>
                </a>
                <a href="{{ route('admin.products') }}" class="product-manage-nav-item">
                    <i class="fas fa-eye"></i>
                    <span>Lihat</span>
                </a>
                <a href="{{ route('admin.products.returns') }}" class="product-manage-nav-item">
                    <i class="fas fa-undo"></i>
                    <span>Pengembalian</span>
                </a>
            </aside>

            <div style="flex: 1;">
                @if ($errors->any())
                    <div style="background-color: #fef2f2; color: #b91c1c; padding: 1rem; border-radius: 1rem; margin-bottom: 1.5rem; font-size: 0.875rem;">
                        <ul style="margin: 0; padding-left: 1.25rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-grid">
                        <div>
                            <label class="form-label">Nama Alat</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-input" required placeholder="Masukkan nama alat...">
                        </div>
                        
                        <div>
                            <label class="form-label">Jumlah Unit</label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-input" required min="0">
                        </div>

                        <div>
                            <label class="form-label">Harga Sewa / hari</label>
                            <input type="number" name="price_per_day" value="{{ old('price_per_day', $product->price_per_day) }}" class="form-input" required placeholder="Rp">
                        </div>

                        <div>
                            <label class="form-label">Jenis Alat / Kategori</label>
                            <select name="category" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Smartphone" {{ old('category', $product->category) === 'Smartphone' ? 'selected' : '' }}>Smartphone</option>
                                <option value="Laptop" {{ old('category', $product->category) === 'Laptop' ? 'selected' : '' }}>Laptop</option>
                                <option value="Kamera" {{ old('category', $product->category) === 'Kamera' ? 'selected' : '' }}>Kamera</option>
                                <option value="Konsol Game" {{ old('category', $product->category) === 'Konsol Game' ? 'selected' : '' }}>Konsol Game</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Kondisi Fisik</label>
                            <input type="text" name="condition_fisik" value="{{ old('condition_fisik', $product->conditions['Fisik'] ?? '') }}" class="form-input" required>
                        </div>

                        <div>
                            <label class="form-label">Kondisi Fungsi</label>
                            <input type="text" name="condition_fungsi" value="{{ old('condition_fungsi', $product->conditions['Fungsi'] ?? '') }}" class="form-input" required>
                        </div>

                        <div class="form-group-full">
                            <label class="form-label">Kondisi Kelengkapan</label>
                            <input type="text" name="condition_kelengkapan" value="{{ old('condition_kelengkapan', $product->conditions['Kelengkapan'] ?? '') }}" class="form-input" required placeholder="e.g. Unit charger, tas pelindung, kabel data">
                        </div>

                        <div>
                            <label class="form-label">Processor (Opsional)</label>
                            <input type="text" name="spec_processor" value="{{ old('spec_processor', $product->specifications['Processor'] ?? '') }}" class="form-input" placeholder="e.g. Intel i7 / Apple M3">
                        </div>

                        <div>
                            <label class="form-label">RAM (Opsional)</label>
                            <input type="text" name="spec_ram" value="{{ old('spec_ram', $product->specifications['RAM'] ?? '') }}" class="form-input" placeholder="e.g. 16GB">
                        </div>

                        <div>
                            <label class="form-label">Penyimpanan (Opsional)</label>
                            <input type="text" name="spec_storage" value="{{ old('spec_storage', $product->specifications['Penyimpanan'] ?? '') }}" class="form-input" placeholder="e.g. 512GB SSD">
                        </div>

                        <div>
                            <label class="form-label">Spesifikasi Lainnya (Opsional)</label>
                            <input type="text" name="spec_display" value="{{ old('spec_display', $product->specifications['Layar'] ?? '') }}" class="form-input" placeholder="e.g. Layar 14 inch Retina">
                        </div>

                        <div class="form-group-full">
                            <label class="form-label">Detail Deskripsi Alat</label>
                            <textarea name="description" class="form-textarea" required placeholder="Masukkan detail spesifikasi dan kegunaan alat...">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="form-group-full">
                            <label class="form-label">Foto Alat Saat Ini</label>
                            <div style="margin-bottom: 1rem; display: flex; align-items: center; gap: 1.5rem;">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 120px; height: 120px; object-fit: contain; border: 1px solid #e2e8f0; border-radius: 1rem; background-color: #f8fafc;">
                                <span style="font-size: 0.875rem; color: #64748b;">Gambar aktif: {{ $product->image }}</span>
                            </div>
                        </div>

                        <div class="form-group-full">
                            <label class="form-label">Ganti Foto Alat (Opsional)</label>
                            <div class="form-file-picker">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span class="form-file-picker-label" id="filePickerLabel">Klik atau seret file gambar ke sini untuk mengganti (Max 5MB)</span>
                                <input type="file" name="photo" id="photoInput" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit-admin">
                            <i class="fas fa-save"></i>
                            <span>Update Produk</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const fileInput = document.getElementById('photoInput');
        const fileLabel = document.getElementById('filePickerLabel');
        
        if (fileInput && fileLabel) {
            fileInput.addEventListener('change', (e) => {
                if (fileInput.files.length > 0) {
                    fileLabel.textContent = 'Terpilih: ' + fileInput.files[0].name;
                } else {
                    fileLabel.textContent = 'Klik atau seret file gambar ke sini untuk mengganti (Max 5MB)';
                }
            });
        }
    });
</script>
@endpush
