@extends('layouts.index')
@section('page_title', 'Manajemen Pasien')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h4 class="card-header d-flex justify-content-between align-items-center">
                <span>Manajemen Pasien</span>
                @if (auth()->user()->role === 'admin')
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#patientModal"
                        onclick="openAdd()">Tambah Pasien</button>
                @endif
            </h4>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>No RM</th>
                            <th>NIK</th>
                            <th>Tgl Lahir</th>
                            <th>Gender</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            @if (auth()->user()->role === 'admin')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patients as $patient)
                            <tr data-id="{{ $patient->id }}">
                                <td>{{ $patient->user->name }}</td>
                                <td>{{ $patient->no_rm }}</td>
                                <td>{{ $patient->nik }}</td>
                                <td>{{ $patient->dob }}</td>
                                <td>{{ ucfirst($patient->gender) }}</td>
                                <td>{{ $patient->phone }}</td>
                                <td>{{ $patient->address }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#"
                                                onclick='openEdit(@json($patient))'>
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            @if (auth()->user()->role === 'admin')
                                                <a class="dropdown-item text-danger" href="#"
                                                    onclick="deletePatient({{ $patient->id }})">
                                                    <i class="bx bx-trash me-1"></i> Hapus
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                        @if ($patients->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada data pasien</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @include('patients.modal')
    </div>
@endsection

@push('scripts')
    <script>
        function openAdd() {
            $('#patientForm')[0].reset();
            $('#patientId').val('');
            $('#patientModalLabel').text('Tambah Pasien');
        }

        function openEdit(patient) {
            $('#patientId').val(patient.id);
            $('#name').val(patient.user.name);
            $('#nik').val(patient.nik);
            $('#dob').val(patient.dob);
            $('#gender').val(patient.gender);
            $('#phone').val(patient.phone);
            $('#address').val(patient.address);
            $('#patientModalLabel').text('Edit Pasien');
            $('#patientModal').modal('show');
        }

        $('#patientForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#patientId').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/patients/${id}` : `/patients`;

            $.ajax({
                url,
                method,
                data: $('#patientForm').serialize(),
                success: res => location.reload(),
                error: err => alert('Terjadi kesalahan.')
            });
        });

        function deletePatient(id) {
            if (confirm("Yakin ingin menghapus data pasien ini?")) {
                $.ajax({
                    url: `/patients/${id}`,
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
