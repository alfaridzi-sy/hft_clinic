@extends('layouts.index')
@section('page_title', 'Manajemen Appointment')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="card-header d-flex justify-content-between align-items-center">
            <span>Jadwalkan Konsultasi</span>
            @if (in_array($role, ['resepsionis', 'admin']))
                <button class="btn btn-primary mb-3" onclick="openAppointment()" data-bs-toggle="modal"
                    data-bs-target="#appointmentModal">
                    Tambah Janji Temu
                </button>
            @endif
        </h4>

        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="date" name="date" value="{{ request('date') }}" class="form-control" />
                <button type="submit" class="btn btn-primary">Filter Tanggal</button>
            </div>
        </form>

        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Antrian</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $apt)
                            <tr>
                                <td>
                                    {{ strtoupper(Str::limit(preg_replace('/[^A-Za-z]/', '', $apt->doctor->user->name), 3, '')) }}
                                    -{{ str_pad($apt->queu_number, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>{{ $apt->appointment_date }}</td>
                                <td>{{ $apt->appointment_time }}</td>
                                <td>{{ $apt->patient->user->name ?? '-' }}</td>
                                <td>{{ $apt->doctor->user->name ?? '-' }}</td>
                                <td>
                                    <span
                                        class="badge bg-label-{{ $apt->status == 'dipesan' ? 'info' : ($apt->status == 'selesai' ? 'success' : 'danger') }}">
                                        {{ ucfirst($apt->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if (($role == 'resepsionis' || $role == 'admin') && $apt->status == 'dipesan')
                                        <form action="{{ route('appointments.cancel', $apt->id) }}" method="POST"
                                            class="cancel-form" style="display:inline;">
                                            @csrf
                                            <button type="button"
                                                class="btn btn-sm btn-danger btn-cancel">Batalkan</button>
                                        </form>
                                    @endif

                                    @if (
                                        $role === 'dokter' ||
                                            ($role === 'admin' && $apt->status === 'dipesan') ||
                                            ($role === 'pasien' && $apt->patient->user_id == Auth::id()))
                                        <a href="{{ route('examinations.create', $apt->id) }}"
                                            class="btn btn-sm btn-success">Periksa</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @if ($appointments->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada appointment</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('appointments.modal', ['patients' => $patients, 'doctors' => $doctors])
@endsection

@push('scripts')
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function initSelect2() {
            $('#patientSelect').select2({
                dropdownParent: $('#appointmentModal'),
                width: '100%' // fix ukuran agar ikut .form-select
            });

            $('#doctorSelect').select2({
                dropdownParent: $('#appointmentModal'),
                width: '100%'
            });
        }

        $('#appointmentModal').on('shown.bs.modal', function() {
            initSelect2();
        });

        function openAppointment() {
            $('#addAppointmentForm')[0].reset();
            $('#appointmentId').val('');
            $('#appointmentModalLabel').text('Tambah Janji Temu');

            // Inisialisasi ulang Select2 saat modal dibuka
            setTimeout(() => initSelect2(), 200);
        }

        $('#addAppointmentForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('appointments.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: res => {
                    $('#appointmentModal').modal('hide');
                    window.open(res.print_url, '_blank'); // Cetak
                    location.reload();
                },
                error: err => {
                    alert('Gagal menyimpan appointment.');
                }
            });
        });

        $('#doctorSelect, #appointment_date, #appointment_time').on('change', function() {
            const doctorOption = $('#doctorSelect option:selected');
            const schedules = doctorOption.data('schedules');
            const selectedDate = $('#appointment_date').val();
            const selectedTime = $('#appointment_time').val();

            if (!schedules || !selectedDate || !selectedTime) return;

            const selectedDay = new Date(selectedDate).toLocaleDateString('id-ID', {
                weekday: 'long'
            });

            const isScheduled = schedules.some(schedule => {
                const dayMatch = schedule.day.toLowerCase() === selectedDay.toLowerCase();
                const timeMatch = selectedTime >= schedule.start_time && selectedTime <= schedule.end_time;
                return dayMatch && timeMatch;
            });

            if (!isScheduled) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Jadwal Tidak Sesuai',
                    text: `Dokter tidak memiliki jadwal pada hari ${selectedDay} jam ${selectedTime}.`,
                    didOpen: () => {
                        // Naikkan z-index manual (fallback)
                        document.querySelector('.swal2-container').style.zIndex = '2000';
                    }
                });
            }
        });

        $(document).on('click', '.btn-cancel', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');

            Swal.fire({
                title: 'Konfirmasi Pembatalan',
                text: "Apakah Anda yakin ingin membatalkan appointment ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, batalkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
