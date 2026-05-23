@extends('layouts.app')

@section('title', 'Gadgetra - Profile')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <i class="fas fa-chevron-right"></i>
        <span>Profile</span>
    </div>

    <div class="profile-layout">
        <aside class="profile-sidebar">
            <div class="avatar-section">
                <div class="avatar-wrapper">
                    <img id="profile-avatar" src="{{ $user->avatar_url }}" alt="Profile Photo">
                    <button class="avatar-edit-btn" id="triggerUpload" title="Ganti Foto">
                        <i class="fas fa-camera"></i>
                    </button>
                </div>
                <h2 class="profile-name">{{ $user->Nama }}</h2>
                <p class="profile-since">Member since {{ $user->member_since }}</p>
                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-label">Rentals</span>
                        <span class="stat-value">12</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Points</span>
                        <span class="stat-value points">2,450</span>
                    </div>
                </div>
                <button class="btn-edit-profile" id="openEditModal">
                    <i class="fas fa-pen"></i> Edit Profile
                </button>
            </div>
            <div class="premium-card">
                <div class="premium-content">
                    <h3>Gadgetra Premium</h3>
                    <p>Get 20% off on your next rental and early access to new tech.</p>
                    <button class="btn-upgrade">UPGRADE NOW</button>
                </div>
                <div class="premium-icon"><i class="fas fa-star"></i></div>
            </div>
        </aside>

        <section class="profile-main">
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-id-card"></i>
                    <h3>Personal Information</h3>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <i class="fas fa-birthday-cake"></i>
                        <div>
                            <span class="info-label">AGE</span>
                            <span class="info-value">{{ $user->umur ? $user->umur . ' tahun' : '-' }}</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <span class="info-label">PLACE OF BIRTH</span>
                            <span class="info-value">{{ $user->tempat_lahir ?: '-' }}</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <span class="info-label">EMAIL ADDRESS</span>
                            <span class="info-value">{{ $user->Email }}</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <span class="info-label">PHONE NUMBER</span>
                            <span class="info-value">{{ $user->phone ?: '-' }}</span>
                        </div>
                    </div>
                    <div class="info-item full-width">
                        <i class="fas fa-users"></i>
                        <div>
                            <span class="info-label">RELATIVE'S PHONE NUMBER</span>
                            <span class="info-value">{{ $user->phone_keluarga ?: '-' }}</span>
                        </div>
                    </div>
                    <div class="info-item full-width">
                        <i class="fas fa-home"></i>
                        <div>
                            <span class="info-label">HOME ADDRESS</span>
                            <span class="info-value">{{ $user->alamat ?: '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="verified-badge">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>Account Verified</strong>
                        <p>Your identity has been verified. You have full access to high-tier rentals and premium insurance coverage.</p>
                    </div>
                </div>
            </div>

            <div class="rentals-section">
                <h3>Recent Rentals</h3>
                <div class="rentals-grid">
                    <div class="rental-card">
                        <img src="{{ asset('assets/products/Sony image.png') }}" alt="Sony">
                        <div class="rental-info">
                            <strong>Sony Alpha A7 IV</strong>
                            <span class="rental-status active">Active • Ends in 2 days</span>
                        </div>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    <div class="rental-card">
                        <img src="{{ asset('assets/products/MacBook Pro M3 Space Black.png') }}" alt="MacBook">
                        <div class="rental-info">
                            <strong>MacBook Pro 16"</strong>
                            <span class="rental-status returned">Returned • Oct 12</span>
                        </div>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    <div class="rental-card">
                        <img src="{{ asset('assets/products/DJI Mavic 3 Pro.png') }}" alt="DJI">
                        <div class="rental-info">
                            <strong>DJI Mavic 3 Pro</strong>
                            <span class="rental-status returned">Returned • Sep 28</span>
                        </div>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </section>
    </div>

    
    <div class="modal-overlay" id="uploadModal">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fas fa-camera"></i> Upload Foto Profil</h3>
                <button class="modal-close" id="closeModal"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-zone" id="uploadZone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Klik atau drag foto ke sini</p>
                        <span>Format: JPG, PNG • Maks. 5MB</span>
                        <input type="file" id="fileInput" name="photo" accept=".jpg,.jpeg,.png" hidden>
                    </div>
                    <div class="preview-box" id="previewBox" style="display:none;">
                        <img id="previewImg" src="" alt="Preview">
                        <div class="preview-info" id="previewInfo"></div>
                        <button type="button" class="btn-remove" id="removeFile">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="upload-error" id="uploadError" style="display:none;"></div>
                    <div class="modal-actions">
                        <button type="button" class="btn-cancel" id="cancelModal">Batal</button>
                        <button type="submit" class="btn-upload" id="submitBtn" disabled>
                            <i class="fas fa-upload"></i> Upload Foto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal-overlay" id="editModal">
        <div class="modal modal-edit">
            <div class="modal-header">
                <h3><i class="fas fa-pen"></i> Edit Profile</h3>
                <button class="modal-close" id="closeEditModal"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="editForm" action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" value="{{ old('nama', $user->Nama) }}" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label>Umur</label>
                            <input type="number" value="{{ old('umur', $user->umur) }}" name="umur">
                        </div>
                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <input type="text" value="{{ old('tempat_lahir', $user->tempat_lahir) }}" name="tempat_lahir">
                        </div>
                        <div class="form-group">
                            <label>Nomor HP</label>
                            <input type="text" value="{{ old('phone', $user->phone) }}" name="phone">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="{{ $user->Email }}" name="email" readonly style="opacity:0.6;cursor:not-allowed;">
                        </div>
                        <div class="form-group">
                            <label>No. HP Keluarga</label>
                            <input type="text" value="{{ old('phone_keluarga', $user->phone_keluarga) }}" name="phone_keluarga">
                        </div>
                        <div class="form-group full-width">
                            <label>Alamat Rumah</label>
                            <textarea name="alamat" rows="2">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn-cancel" id="cancelEditModal">Batal</button>
                        <button type="submit" class="btn-upload">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/Profile.js') }}"></script>
    
    @if (session('success') === 'photo')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('Foto profil berhasil diupload!');
            });
        </script>
    @elseif (session('success') === 'profile')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('Profil berhasil diperbarui!');
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ $errors->first() }}');
                var editModal = document.getElementById('editModal');
                if (editModal) {
                    editModal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            });
        </script>
    @endif
@endpush
