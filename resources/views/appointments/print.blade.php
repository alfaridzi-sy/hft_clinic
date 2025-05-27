<!DOCTYPE html>
<html lang="id" class="light-style customizer-hide" dir="ltr" data-theme="theme-default">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cetak Antrian</title>

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
            text-align: center;
            padding-top: 30px;
        }

        .app-logo {
            margin-bottom: 20px;
        }

        .queue-card {
            border: 2px dashed #696cff;
            display: inline-block;
            padding: 30px 40px;
            border-radius: 12px;
            margin-top: 20px;
        }

        .queue-number {
            font-size: 64px;
            font-weight: bold;
            color: #696cff;
        }

        .doctor-name {
            font-size: 22px;
            margin-top: 10px;
            font-weight: 500;
        }

        .queue-date,
        .queue-time {
            margin-top: 8px;
            font-size: 18px;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="app-logo">
        <img src="{{ asset('sneat/assets/img/hft_clinic_logo.svg') }}" alt="Logo Klinik Sehat" width="100">
        <h2 style="margin-top: 10px;">HFT Clinic</h2>
    </div>

    <div class="queue-card">
        <div class="doctor-name">Dokter: {{ $appointment->doctor->user->name }}</div>
        <div class="queue-number">
            {{ strtoupper(Str::limit(preg_replace('/[^A-Za-z]/', '', $appointment->doctor->user->name), 3, '')) }}-{{ str_pad($appointment->queu_number, 3, '0', STR_PAD_LEFT) }}
        </div>
        <div class="queue-date">
            {{ \Carbon\Carbon::parse($appointment->appointment_date)->translatedFormat('l, d M Y') }}</div>
        <div class="queue-time">Pukul {{ $appointment->appointment_time }}</div>
    </div>

    <script>
        window.onload = () => window.print();
    </script>
</body>

</html>
