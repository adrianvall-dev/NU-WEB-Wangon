@extends('layouts.admin')

@section('title', 'Tambah Admin Baru')

@section('content')
<div class="space-y-4">

    <!-- PAGE TITLE BAR -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.admin-user.index') }}" class="badge-solid badge-solid-forest text-[11px] text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i>ADMIN
                </a>
                <span class="text-xs text-[#2D4736] font-bold uppercase tracking-wider">• BUAT AKUN PENGELOLA</span>
            </div>
            <h1 class="h2 fw-extrabold text-[#142E25] tracking-tight mb-1">Tambah Admin Baru</h1>
            <p class="text-[#2D4736] text-sm mb-0">Buat akun kredensial baru untuk memberikan hak akses pengelolaan panel.</p>
        </div>
        <div>
            <a href="{{ route('admin.admin-user.index') }}" class="btn-solid-light btn-solid-press">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <!-- ALERT ERROR NOTIFICATION -->
    @if ($errors->any())
        <div class="card-solid p-3 mb-4" style="background-color: #FEE2E2; border-color: #EF4444;">
            <div class="d-flex align-items-center gap-2 font-bold text-[#991B1B] mb-2">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <span>Terdapat kesalahan pada pengisian form:</span>
            </div>
            <ul class="mb-0 text-xs font-semibold text-[#991B1B] ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM CARD CONTAINER -->
    <div class="card-solid p-4 p-md-5 mb-5">
        <form action="{{ route('admin.admin-user.store') }}" method="POST">
            @csrf

            <div class="row g-4">
                <!-- NAMA LENGKAP -->
                <div class="col-md-6">
                    <label class="form-label-solid">
                        Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" class="form-control form-control-solid @error('name') is-invalid @enderror" placeholder="Contoh: Ahmad Subagja" value="{{ old('name') }}" required>
                </div>

                <!-- EMAIL -->
                <div class="col-md-6">
                    <label class="form-label-solid">
                        Alamat Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" name="email" class="form-control form-control-solid @error('email') is-invalid @enderror" placeholder="admin@domain.com" value="{{ old('email') }}" required>
                </div>

                <!-- PASSWORD -->
                <div class="col-md-6">
                    <label class="form-label-solid">
                        Password <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control form-control-solid border-r-0 @error('password') is-invalid @enderror" placeholder="••••••••" required>
                        <button class="btn btn-solid-light border-l-0 px-3" type="button" onclick="togglePassword('password', 'iconPassword')" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                            <i class="bi bi-eye-slash" id="iconPassword"></i>
                        </button>
                    </div>
                    <div class="text-xs text-[#2D4736] mt-1 font-semibold">
                        <i class="bi bi-info-circle me-1"></i> Minimal 8 karakter, kombinasi huruf dan angka disarankan.
                    </div>
                </div>

                <!-- KONFIRMASI PASSWORD -->
                <div class="col-md-6">
                    <label class="form-label-solid">
                        Konfirmasi Password <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-solid border-r-0" placeholder="••••••••" required>
                        <button class="btn btn-solid-light border-l-0 px-3" type="button" onclick="togglePassword('password_confirmation', 'iconConfirm')" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                            <i class="bi bi-eye-slash" id="iconConfirm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- BOTTOM BUTTON ACTION BAR -->
            <div class="d-flex align-items-center justify-content-end gap-3 mt-5 pt-4 border-top-solid">
                <a href="{{ route('admin.admin-user.index') }}" class="btn-solid-light btn-solid-press">
                    Batal
                </a>
                <button type="submit" class="btn-solid-primary btn-solid-press px-4">
                    <i class="bi bi-check-lg me-1"></i>Simpan Admin
                </button>
            </div>
        </form>
    </div>

</div>

<!-- SCRIPT TOGGLE PASSWORD VISIBILITY -->
<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }
</script>
@endsection