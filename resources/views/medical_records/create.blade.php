@extends('layouts.index')
@section('page_title', 'Form Pemeriksaan Dokter')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Card Informasi Pasien --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Pasien</h5>
            </div>
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Pasien</label>
                        <input type="text" class="form-control" value="{{ $appointment->patient->user->name }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Dokter</label>
                        <input type="text" class="form-control" value="{{ $appointment->doctor->user->name }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal</label>
                        <input type="text" class="form-control"
                            value="{{ \Carbon\Carbon::parse($appointment->appointment_date)->translatedFormat('d M Y') }}"
                            disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam</label>
                        <input type="text" class="form-control" value="{{ $appointment->appointment_time }}" disabled>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tab Pemeriksaan dan Layanan --}}
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Form Pemeriksaan Dokter</h4>
                <small class="text-muted">Isi data pemeriksaan dan layanan</small>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="examinationTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="medical-tab" data-bs-toggle="tab" data-bs-target="#medical"
                            type="button" role="tab">Medical Record</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#services"
                            type="button" role="tab">Tindakan / Layanan</button>
                    </li>
                </ul>

                <div class="tab-content pt-3" id="examinationTabsContent">
                    {{-- Tab Medical Record --}}
                    <div class="tab-pane fade show active" id="medical" role="tabpanel">
                        @php
                            $record = \App\Models\MedicalRecord::where('appointment_id', $appointment->id)->first();
                        @endphp
                        <form action="{{ route('examinations.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                            @foreach (['subjective', 'objective', 'assessment', 'plan'] as $field)
                                <div class="mb-3">
                                    <label class="form-label text-capitalize">{{ $field }}</label>
                                    <textarea name="{{ $field }}" class="form-control @error($field) is-invalid @enderror" rows="3" required>{{ old($field, $record->$field ?? '') }}</textarea>
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                            <button type="submit" class="btn btn-primary">Simpan Pemeriksaan</button>
                        </form>
                    </div>

                    {{-- Tab Layanan --}}
                    <div class="tab-pane fade" id="services" role="tabpanel">
                        <form id="addServiceForm">
                            @csrf
                            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                            <div class="mb-3">
                                <label class="form-label">Layanan</label>
                                <select name="service_id" class="form-select" required>
                                    <option value="">-- Pilih Layanan --</option>
                                    @foreach (App\Models\Service::all() as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }}
                                            (Rp{{ number_format($service->price, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="quantity" class="form-control" value="1" min="1"
                                    required>
                            </div>

                            <button type="submit" class="btn btn-success">Tambahkan Layanan</button>
                        </form>

                        <hr>
                        <h6>Layanan yang Ditambahkan</h6>
                        <ul class="list-group">
                            @forelse($appointment->services as $appService)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        {{ $appService->name }} x{{ $appService->pivot->quantity }}
                                        <button type="button" class="btn btn-sm btn-outline-danger ms-2 delete-service-btn"
                                            data-id="{{ $appService->pivot->id }}">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                    <span class="badge bg-primary">
                                        Rp{{ number_format($appService->price * $appService->pivot->quantity, 0, ',', '.') }}
                                    </span>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">Belum ada layanan ditambahkan</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- jQuery -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {
            let activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                let tabTrigger = document.querySelector(`button[data-bs-target="${activeTab}"]`);
                if (tabTrigger) new bootstrap.Tab(tabTrigger).show();

                // Hapus setelah digunakan agar tidak mengganggu reload berikutnya
                localStorage.removeItem('activeTab');
            }
            // Inisialisasi select2
            $('select[name="service_id"]').select2({
                theme: 'bootstrap-5',
                placeholder: "-- Pilih Layanan --",
                allowClear: true,
                dropdownParent: $('#services') // supaya dropdown Select2 tidak keluar container modal/tab
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            // Tambah layanan
            $('#addServiceForm').on('submit', function(e) {
                e.preventDefault();

                // Simpan tab aktif agar tetap di tab layanan setelah reload
                localStorage.setItem('activeTab', '#services');

                $.post("{{ route('examinations.addService') }}", $(this).serialize())
                    .done(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: 'Layanan berhasil ditambahkan.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    })
                    .fail(() => {
                        Swal.fire('Error', 'Gagal menambahkan layanan.', 'error');
                    });
            });

            // Hapus layanan dengan SweetAlert2 confirm
            $(document).on('click', '.delete-service-btn', function() {
                const id = $(this).data('id');
                console.log('ID layanan yang akan dihapus:', id);

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Layanan ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {

                        localStorage.setItem('activeTab', '#services');
                        $.ajax({
                            url: `/examinations/services/${id}`,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: () => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses!',
                                    text: 'Layanan berhasil dihapus.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: () => {
                                Swal.fire('Error', 'Gagal menghapus layanan.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
