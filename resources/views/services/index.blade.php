@extends('layouts.index')
@section('page_title', 'Manajemen Layanan')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h4 class="card-header d-flex justify-content-between align-items-center">
                <span>Manajemen Layanan</span>
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#serviceModal"
                    onclick="openAdd()">Tambah Layanan</button>
            </h4>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="bx bx-package me-1"></i> Nama</th>
                            <th><i class="bx bx-money me-1"></i> Harga</th>
                            <th><i class="bx bx-cog me-1"></i> Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($services as $service)
                            <tr data-id="{{ $service->id }}">
                                <td><strong>{{ $service->name }}</strong></td>
                                <td>Rp{{ number_format($service->price, 0, ',', '.') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick='openEdit(@json($service))'>
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                onclick="deleteService({{ $service->id }})">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if ($services->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada data layanan</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @include('services.modal')
    @endsection

    @push('scripts')
        <script>
            function openAdd() {
                $('#serviceForm')[0].reset();
                $('#serviceId').val('');
                $('#serviceModalLabel').text('Tambah Layanan');
            }

            function openEdit(service) {
                $('#serviceId').val(service.id);
                $('#name').val(service.name);
                $('#price').val(service.price);
                $('#serviceModalLabel').text('Edit Layanan');
                $('#serviceModal').modal('show');
            }

            $('#serviceForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#serviceId').val();
                const url = id ? `/services/${id}` : '/services';
                const method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: $('#serviceForm').serialize(),
                    success: res => location.reload(),
                    error: err => alert('Terjadi kesalahan.')
                });
            });

            function deleteService(id) {
                if (confirm("Yakin ingin menghapus layanan ini?")) {
                    $.ajax({
                        url: `/services/${id}`,
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
