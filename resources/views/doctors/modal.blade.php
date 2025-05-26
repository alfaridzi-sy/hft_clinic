<div class="modal fade" id="doctorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="doctorForm" method="POST" action="">
            @csrf
            <input type="hidden" id="doctorId" name="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="doctorModalLabel">Tambah Dokter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" id="name" class="form-control mb-2" placeholder="Nama"
                        required>
                    <input type="text" name="username" id="username" class="form-control mb-2"
                        placeholder="Username" required>
                    <input type="password" name="password" id="password" class="form-control mb-2"
                        placeholder="Password (opsional)">
                    <select name="specialization" id="specialization" class="form-control mb-2" required>
                        <option value="">-- Pilih Spesialisasi --</option>
                        @foreach ($specializations as $spec)
                            <option value="{{ $spec }}">{{ $spec }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="sip_number" id="sip_number" class="form-control mb-2"
                        placeholder="Nomor SIP" required>
                    <input type="text" name="phone" id="phone" class="form-control mb-2" placeholder="Telepon"
                        required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function submitDoctor() {
        const id = $('#doctorId').val();
        const form = $('#doctorForm');
        const url = id ? `/doctors/${id}` : '/doctors';
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url,
            method,
            data: form.serialize(),
            success: res => location.reload(),
            error: err => alert('Terjadi kesalahan')
        });
    }
</script>
