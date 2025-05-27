<div class="modal fade" id="appointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md"> <!-- medium size -->
        <form id="addAppointmentForm" method="POST">
            @csrf
            <input type="hidden" id="appointmentId" name="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Tambah Janji Temu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="patientSelect" class="form-label">Pasien</label>
                            <select id="patientSelect" name="patient_id" class="form-select" required>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="doctorSelect" class="form-label">Dokter</label>
                            <select id="doctorSelect" name="doctor_id" class="form-select" required>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" data-schedules='@json($doctor->schedules)'>
                                        {{ $doctor->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="appointment_date" class="form-label">Tanggal</label>
                            <input type="date" name="appointment_date" id="appointment_date" class="form-control"
                                required>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="appointment_time" class="form-label">Waktu</label>
                            <input type="time" name="appointment_time" id="appointment_time" class="form-control"
                                required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
