@extends('layouts.index')

@section('page_title', 'Dashboard | HFT Clinic')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Welcome Card --}}
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Selamat datang,
                                    <strong>{{ Auth::user()->name }}</strong>! 🎉
                                </h5>
                                <p class="mb-4">
                                    Gunakan halaman ini untuk memantau data operasional klinik secara menyeluruh dan
                                    real-time.
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img src="{{ asset('sneat/assets/img/illustrations/man-with-laptop-light.png') }}"
                                    height="140" alt="User Welcome"
                                    data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                    data-app-light-img="illustrations/man-with-laptop-light.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Data Cards --}}
        <div class="row">
            <div class="col-lg-3 col-md-6 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('sneat/assets/img/icons/unicons/users.png') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Pasien</span>
                        <h3 class="card-title mb-2">{{ $totalPatients }}</h3>
                        <small class="text-success fw-semibold">Orang</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('sneat/assets/img/icons/unicons/doctor.png') }}" alt="doctor"
                                    class="rounded" />
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Dokter</span>
                        <h3 class="card-title mb-2">{{ $totalDoctors }}</h3>
                        <small class="text-success fw-semibold">Orang</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('sneat/assets/img/icons/unicons/heart.png') }}" alt="appointment"
                                    class="rounded" />
                            </div>
                        </div>
                        <span class="d-block mb-1">Janji Dokter</span>
                        <h3 class="card-title mb-2">{{ $totalAppointments }}</h3>
                        <small class="text-success fw-semibold">Tercatat</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('sneat/assets/img/icons/unicons/cc-primary.png') }}" alt="payment"
                                    class="rounded" />
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Transaksi</span>
                        <h3 class="card-title mb-2">{{ $totalPayments }}</h3>
                        <small class="text-success fw-semibold">Pembayaran</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart: Kunjungan --}}
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Pertumbuhan Kunjungan Pasien</h5>
                    </div>
                    <div class="card-body">
                        <div id="kunjunganChart"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart: Pendapatan --}}
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Pertumbuhan Pendapatan</h5>
                    </div>
                    <div class="card-body">
                        <div id="growthChart"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const visits = @json($visits);
            const revenues = @json($revenues);
            const months = @json($months->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->translatedFormat('F Y')));

            // Kunjungan Chart
            new ApexCharts(document.querySelector("#kunjunganChart"), {
                chart: {
                    type: 'line',
                    height: 300
                },
                series: [{
                    name: 'Kunjungan',
                    data: visits
                }],
                xaxis: {
                    categories: months
                },
                colors: ['#03C3EC']
            }).render();

            // Pendapatan Chart
            new ApexCharts(document.querySelector("#growthChart"), {
                chart: {
                    type: 'bar',
                    height: 300
                },
                series: [{
                    name: 'Pendapatan',
                    data: revenues
                }],
                xaxis: {
                    categories: months
                },
                colors: ['#81D643'],
                dataLabels: {
                    formatter: function(val) {
                        return 'Rp' + val.toLocaleString('id-ID');
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return 'Rp' + val.toLocaleString('id-ID');
                        }
                    }
                }
            }).render();
        });
    </script>
@endpush
