@extends('layouts.index')
@section('page_title', 'Manajemen Pengguna')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h4 class="card-header d-flex justify-content-between align-items-center">
                <span>Manajemen Pengguna</span>
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#userModal"
                    onclick="openAdd()">Tambah
                    Pengguna</button>
            </h4>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="bx bx-user me-1"></i> Nama</th>
                            <th><i class="bx bx-user-circle me-1"></i> Username</th>
                            <th><i class="bx bx-shield-quarter me-1"></i> Role</th>
                            <th><i class="bx bx-cog me-1"></i> Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($users as $user)
                            <tr data-id="{{ $user->id }}">
                                <td>
                                    <i class="bx bx-user-circle text-primary me-2"></i>
                                    <strong>{{ $user->name }}</strong>
                                </td>
                                <td>{{ $user->username }}</td>
                                <td>
                                    @php
                                        $badge = match ($user->role) {
                                            'admin' => 'bg-label-danger',
                                            'dokter' => 'bg-label-success',
                                            'resepsionis' => 'bg-label-warning',
                                            default => 'bg-label-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick='openEdit(@json($user))'>
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                onclick="deleteUser({{ $user->id }})">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if ($users->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data pengguna</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @include('users.modal')
    @endsection

    @push('scripts')
        <script>
            function openAdd() {
                $('#userForm')[0].reset();
                $('#userId').val('');
                $('#userModalLabel').text('Tambah Pengguna');
            }

            function openEdit(user) {
                $('#userId').val(user.id);
                $('#name').val(user.name);
                $('#username').val(user.username);
                $('#role').val(user.role);
                $('#userModalLabel').text('Edit Pengguna');
                $('#userModal').modal('show');
            }

            $('#userForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#userId').val();
                const url = id ? `/users/${id}` : '/users';
                const method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: $('#userForm').serialize(),
                    success: res => location.reload(),
                    error: err => alert('Terjadi kesalahan.')
                });
            });

            function deleteUser(id) {
                if (confirm("Yakin ingin menghapus pengguna ini?")) {
                    $.ajax({
                        url: `/users/${id}`,
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
