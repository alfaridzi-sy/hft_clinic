<!DOCTYPE html>
<html lang="id" class="light-style customizer-hide" dir="ltr" data-theme="theme-default">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kwitansi Pembayaran</title>

    <!-- Logo & Fonts -->
    <link rel="icon" href="{{ asset('sneat/assets/img/hft_clinic_logo.svg') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600&display=swap" />

    <!-- Core CSS (Sneat Theme) -->
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('sneat/assets/css/demo.css') }}" />

    <style>
        body {
            font-family: 'Public Sans', sans-serif;
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            border: 2px dashed #696cff;
            border-radius: 12px;
            color: #333;
        }

        .app-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .app-logo img {
            width: 80px;
        }

        h2 {
            margin-top: 10px;
            font-weight: 600;
            color: #696cff;
        }

        .section-title {
            font-weight: 600;
            margin-top: 25px;
            margin-bottom: 8px;
            border-bottom: 1px solid #696cff;
            padding-bottom: 4px;
            color: #696cff;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .info-label {
            font-weight: 500;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            padding: 8px 6px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f3f4ff;
            color: #444;
        }

        .total-row td {
            font-weight: 700;
            color: #696cff;
            font-size: 18px;
        }

        .footer-note {
            margin-top: 30px;
            font-size: 14px;
            color: #666;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="app-logo">
        <img src="{{ asset('sneat/assets/img/hft_clinic_logo.svg') }}" alt="Logo Klinik Sehat" />
        <h2>HFT Clinic</h2>
        <div style="font-size:14px; color:#555;">Kwitansi Pembayaran</div>
    </div>

    <div class="section-title">Informasi Pasien & Dokter</div>
    <div class="info-row">
        <div><span class="info-label">Pasien:</span> {{ $appointment->patient->user->name ?? '-' }}</div>
        <div><span class="info-label">Dokter:</span> {{ $appointment->doctor->user->name ?? '-' }}</div>
    </div>
    <div class="info-row">
        <div><span class="info-label">Tanggal:</span> {{ \Carbon\Carbon::parse($appointment->appointment_date)->translatedFormat('l, d M Y') }}</div>
        <div><span class="info-label">Waktu:</span> {{ $appointment->appointment_time }}</div>
    </div>

    <div class="section-title">Rincian Layanan</div>
    <table>
        <thead>
            <tr>
                <th>Nama Layanan</th>
                <th>Harga Satuan</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointment->services as $service)
                <tr>
                    <td>{{ $service->name }}</td>
                    <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                    <td>{{ $service->pivot->quantity }}</td>
                    <td>Rp {{ number_format($service->price * $service->pivot->quantity, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" style="text-align:right;">Total Tagihan</td>
                <td>
                    Rp {{ number_format($appointment->payment->total ?? $appointment->services->sum(fn($s) => $s->price * $s->pivot->quantity), 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="section-title">Detail Pembayaran</div>
    <div class="info-row">
        <div><span class="info-label">Metode Pembayaran:</span> {{ ucfirst(str_replace('_', ' ', $appointment->payment->method ?? '-')) }}</div>
        <div><span class="info-label">Tanggal Bayar:</span> {{ optional($appointment->payment)->paid_at ? \Carbon\Carbon::parse($appointment->payment->paid_at)->translatedFormat('d M Y') : '-' }}</div>
    </div>

    <div class="footer-note">
        Terima kasih atas kepercayaan Anda. <br />
        Klinik Sehat - HFT Clinic
    </div>

    <script>
        window.onload = () => window.print();
    </script>
</body>

</html>
