<div class="modal fade" id="patientModal" tabindex="-1" aria-labelledby="patientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="patientForm" class="modal-content">
            @csrf
            <input type="hidden" name="_method" id="_method" value="POST">
            <input type="hidden" name="id" id="patientId">
            <div class="modal-header">
                <h5 class="modal-title" id="patientModalLabel">Tambah Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">NIK</label>
                    <input type="text" name="nik" id="nik" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="dob" id="dob" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="gender" id="gender" class="form-control" required>
                        <option value="" selected disabled hidden>Pilih Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="phone" id="phone" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" id="address" class="form-control" required></textarea>
                </div>
                <div class="mb-2">
                    <label class="form-label">Password (Opsional - hanya isi jika ingin mengubah)</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>
