@extends('layouts.index')
@section('page_title', 'Jadwal Dokter')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h4 class="card-header d-flex justify-content-between align-items-center">
                <span>Jadwal Dokter</span>
                @if (auth()->user()->role === 'admin' || auth()->user()->role === 'dokter')
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#scheduleModal"
                        onclick="openAdd()">Tambah Jadwal</button>
                @endif
            </h4>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Dokter</th>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            @if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'dokter')
                                <th></th>
                            @else
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->doctor->user->name }}</td>
                                <td>{{ $schedule->day }}</td>
                                <td>{{ $schedule->start_time }}</td>
                                <td>{{ $schedule->end_time }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#"
                                                onclick='openEdit(@json($schedule))'>
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item text-danger" href="#"
                                                onclick="deleteSchedule({{ $schedule->id }})">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if ($schedules->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada jadwal</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @include('schedules.modal', ['doctors' => $doctors, 'days' => $days])
    </div>
@endsection

@push('scripts')
    <script>
        function openAdd() {
            $('#scheduleForm')[0].reset();
            $('#scheduleId').val('');
            $('#scheduleModalLabel').text('Tambah Jadwal');
        }

        function openEdit(schedule) {
            $('#scheduleId').val(schedule.id);
            $('#doctor_id').val(schedule.doctor_id);
            $('#day').val(schedule.day);
            $('#start_time').val(schedule.start_time);
            $('#end_time').val(schedule.end_time);
            $('#scheduleModalLabel').text('Edit Jadwal');
            $('#scheduleModal').modal('show');
        }

        $('#scheduleForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#scheduleId').val();
            const url = id ? `/schedules/${id}` : `/schedules`;
            const method = id ? 'PUT' : 'POST';

            $.ajax({
                url,
                method,
                data: $('#scheduleForm').serialize(),
                success: () => location.reload(),
                error: () => alert('Terjadi kesalahan.')
            });
        });

        function deleteSchedule(id) {
            if (confirm("Yakin ingin menghapus jadwal ini?")) {
                $.ajax({
                    url: `/schedules/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: () => location.reload()
                });
            }
        }
    </script>
@endpush
