<div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="scheduleForm" method="POST">
            @csrf
            <input type="hidden" id="scheduleId" name="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">Tambah Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select name="doctor_id" id="doctor_id" class="form-control mb-2" required>
                        <option value="">-- Pilih Dokter --</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->user->name }}</option>
                        @endforeach
                    </select>

                    <select name="day" id="day" class="form-control mb-2" required>
                        <option value="">-- Pilih Hari --</option>
                        @foreach ($days as $day)
                            <option value="{{ $day }}">{{ $day }}</option>
                        @endforeach
                    </select>

                    <input type="time" name="start_time" id="start_time" class="form-control mb-2" required>
                    <input type="time" name="end_time" id="end_time" class="form-control mb-2" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
