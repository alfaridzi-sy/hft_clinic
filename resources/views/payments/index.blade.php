@extends('layouts.index')
@section('page_title', 'Manajemen Appointment Selesai')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Appointment Selesai</span>
        </h4>

        {{-- Filter Tanggal --}}
        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="date" name="date" value="{{ request('date') }}" class="form-control" />
                <button type="submit" class="btn btn-primary">Filter Tanggal</button>
            </div>
        </form>

        {{-- Tabel Appointment Selesai --}}
        <div class="card mb-4">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Status</th>
                            <th>Total Tagihan</th> {{-- Tambah ini --}}
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $apt)
                            <tr>
                                <td>{{ $apt->appointment_date }}</td>
                                <td>{{ $apt->appointment_time }}</td>
                                <td>{{ $apt->patient->user->name ?? '-' }}</td>
                                <td>{{ $apt->doctor->user->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-label-success">Selesai</span>
                                </td>
                                <td>
                                    Rp{{ number_format($apt->total_tagihan ?? 0, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if ($apt->payment)
                                        <button class="btn btn-sm btn-secondary btn-payment-detail"
                                            data-id="{{ $apt->id }}">Detail Pembayaran</button>
                                    @else
                                        <button class="btn btn-sm btn-primary btn-pay"
                                            data-id="{{ $apt->id }}">Bayar</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                </table>
            </div>
        </div>

        {{-- Detail Pembayaran & Form Pembayaran --}}
        <div class="card d-none" id="paymentCard">
            <div class="card-header">
                <h5>Detail & Proses Pembayaran</h5>
            </div>
            <div class="card-body">
                <form id="paymentForm">
                    @csrf
                    <input type="hidden" name="appointment_id" id="paymentAppointmentId" />

                    <div class="mb-3">
                        <label class="form-label">Total Tagihan</label>
                        <input type="text" id="paymentTotal" class="form-control" readonly />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="method" id="paymentMethod" class="form-select" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="tunai">Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="qris">QRIS</option>
                            <option value="e-wallet">E-Wallet</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Bayar</label>
                        <input type="date" name="paid_at" id="paymentDate" class="form-control"
                            value="{{ date('Y-m-d') }}" required />
                    </div>

                    <button type="submit" class="btn btn-success">Proses Pembayaran</button>
                    <button type="button" class="btn btn-secondary" id="closePayment">Tutup</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- jQuery sudah ada di layout? Kalau belum tambahkan -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {
            let appointmentsData = @json(
                $appointments->map(function ($apt) {
                    return [
                        'id' => $apt->id,
                        'total' => $apt->payment->total ?? ($apt->total_tagihan ?? 0),
                    ];
                }));

            // Show payment form with data when click Bayar
            $('.btn-pay, .btn-payment-detail').on('click', function() {
                const id = $(this).data('id');
                const apt = appointmentsData.find(a => a.id === id);
                if (!apt) return;

                $('#paymentAppointmentId').val(id);
                $('#paymentTotal').val('Rp ' + (apt.total ? apt.total.toLocaleString('id-ID') : '0'));
                if ($(this).hasClass('btn-payment-detail')) {
                    // Show readonly if detail
                    $('#paymentMethod').prop('disabled', true);
                    $('#paymentDate').prop('disabled', true);
                    $('#paymentForm button[type=submit]').hide();
                } else {
                    // Reset form to input payment
                    $('#paymentMethod').prop('disabled', false).val('');
                    $('#paymentDate').prop('disabled', false).val(new Date().toISOString().slice(0, 10));
                    $('#paymentForm button[type=submit]').show();
                }

                $('#paymentCard').removeClass('d-none').show();
                $('html, body').animate({
                    scrollTop: $("#paymentCard").offset().top
                }, 500);
            });

            $('#closePayment').on('click', function() {
                $('#paymentCard').addClass('d-none');
            });

            // Inisialisasi select2 untuk metode pembayaran
            $('#paymentMethod').select2({
                theme: 'bootstrap-5',
                width: '100%',
                minimumResultsForSearch: Infinity
            });

            // Proses pembayaran ajax
            $('#paymentForm').on('submit', function(e) {
                e.preventDefault();
                const data = $(this).serialize();
                $.post("{{ route('payments.store') }}", data)
                    .done(res => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Pembayaran berhasil diproses.'
                        }).then(() => {
                            let appointmentId = $('#paymentAppointmentId').val();
                            let url = "{{ url('payments/receipt') }}/" + appointmentId;
                            window.open(url, '_blank');
                            location.reload();
                        });
                    })
                    .fail(() => {
                        Swal.fire('Error', 'Gagal memproses pembayaran.', 'error');
                    });
            });
        });
    </script>
@endpush
