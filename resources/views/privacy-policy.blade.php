@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header section dengan background -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary mb-3">Kebijakan Privasi SIMONAS</h1>
                <p class="lead text-muted">Komitmen kami dalam melindungi privasi dan data pengguna</p>
                <hr class="my-4 w-25 mx-auto">
            </div>
            
            <!-- Cards dengan hover effect dan icon -->
            <div class="row g-4">
                <!-- About Card -->
                <div class="col-md-12">
                    <div class="card shadow-sm hover-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-info-circle text-primary fa-2x me-3"></i>
                                <h4 class="mb-0">Tentang SIMONAS</h4>
                            </div>
                            <p class="card-text">SIMONAS (Sistem Monitoring Asrama) adalah aplikasi monitoring Asrama YAPI yang terdiri dari SIMONAS dan IKAYAPI Connect.</p>
                        </div>
                    </div>
                </div>

                <!-- Information Collection Card -->
                <div class="col-md-6">
                    <div class="card shadow-sm h-100 hover-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-database text-primary fa-2x me-3"></i>
                                <h4 class="mb-0">Informasi yang Kami Kumpulkan</h4>
                            </div>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check-circle text-success me-2"></i>Informasi pribadi</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Data akademik</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Informasi kegiatan asrama</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Data penilaian dan monitoring</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Information Usage Card -->
                <div class="col-md-6">
                    <div class="card shadow-sm h-100 hover-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-tasks text-primary fa-2x me-3"></i>
                                <h4 class="mb-0">Penggunaan Informasi</h4>
                            </div>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check-circle text-success me-2"></i>Monitoring kegiatan asrama</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Evaluasi perkembangan warga asrama</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Peningkatan layanan asrama</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Komunikasi terkait program asrama</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Data Security Card -->
                <div class="col-md-12">
                    <div class="card shadow-sm hover-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-shield-alt text-primary fa-2x me-3"></i>
                                <h4 class="mb-0">Keamanan Data</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-lock text-success me-2"></i>Enkripsi data sensitif</li>
                                        <li><i class="fas fa-user-shield text-success me-2"></i>Pembatasan akses berdasarkan peran</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-sync text-success me-2"></i>Pemantauan keamanan secara berkala</li>
                                        <li><i class="fas fa-database text-success me-2"></i>Backup data rutin</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Card -->
                <div class="col-md-12">
                    <div class="card shadow-sm hover-card bg-light">
                        <div class="card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-3">
                                <i class="fas fa-envelope text-primary fa-2x me-3"></i>
                                <h4 class="mb-0">Kontak</h4>
                            </div>
                            <p class="mb-2">Untuk pertanyaan terkait kebijakan privasi ini, silakan hubungi:</p>
                            <p class="mb-0">
                                <a href="mailto:info@simonas.id" class="text-decoration-none">
                                    <i class="fas fa-envelope-open me-2"></i>info@simonas.id
                                </a>
                            </p>
                            <p class="mb-0">
                                <a href="http://www.simonas.id" class="text-decoration-none" target="_blank">
                                    <i class="fas fa-globe me-2"></i>www.simonas.id
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS tambahan -->
<style>
.hover-card {
    transition: transform 0.3s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-5px);
}

.text-primary {
    color: #4e73df !important;
}

.text-success {
    color: #1cc88a !important;
}
</style>
@endsection
