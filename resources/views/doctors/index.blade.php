@extends('layouts.index')
@section('page_title', 'Manajemen Dokter')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h4 class="card-header d-flex justify-content-between align-items-center">
                <span>Manajemen Dokter</span>
                @if (auth()->user()->role === 'admin')
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#doctorModal"
                        onclick="openAdd()">Tambah Dokter</button>
                @endif
            </h4>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Spesialisasi</th>
                            <th>No SIP</th>
                            <th>Telepon</th>
                            @if (auth()->user()->role === 'admin')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($doctors as $doctor)
                            <tr data-id="{{ $doctor->id }}">
                                <td>{{ $doctor->user->name }}</td>
                                <td>{{ $doctor->user->username }}</td>
                                <td>{{ $doctor->specialization }}</td>
                                <td>{{ $doctor->sip_number }}</td>
                                <td>{{ $doctor->phone }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#"
                                                onclick='openEdit(@json($doctor))'>
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            @if (auth()->user()->role === 'admin')
                                                <a class="dropdown-item text-danger" href="#"
                                                    onclick="deleteDoctor({{ $doctor->id }})">
                                                    <i class="bx bx-trash me-1"></i> Hapus
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if ($doctors->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data dokter</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @include('doctors.modal', ['specializations' => $specializations])
    </div>
@endsection

@push('scripts')
    <script>
        function openAdd() {
            $('#doctorForm')[0].reset();
            $('#doctorId').val('');
            $('#doctorModalLabel').text('Tambah Dokter');
        }

        function openEdit(doctor) {
            $('#doctorId').val(doctor.id);
            $('#name').val(doctor.user.name);
            $('#username').val(doctor.user.username);
            $('#specialization').val(doctor.specialization);
            $('#sip_number').val(doctor.sip_number);
            $('#phone').val(doctor.phone);
            $('#doctorModalLabel').text('Edit Dokter');
            $('#doctorModal').modal('show');
        }

        $('#doctorForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#doctorId').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/doctors/${id}` : `/doctors`;

            $.ajax({
                url,
                method,
                data: $('#doctorForm').serialize(),
                success: res => location.reload(),
                error: err => alert('Terjadi kesalahan.')
            });
        });

        function deleteDoctor(id) {
            if (confirm("Yakin ingin menghapus data dokter ini?")) {
                $.ajax({
                    url: `/doctors/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: res => location.reload()
                });
            }
        }
    </script>
@endpush
