<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Admin Klinik', 'username' => 'admin', 'password' => Hash::make('password'), 'role' => 'admin'],
            ['id' => 2, 'name' => 'Dr. Siti', 'username' => 'drsiti', 'password' => Hash::make('password'), 'role' => 'dokter'],
            ['id' => 3, 'name' => 'Resepsionis A', 'username' => 'reseps', 'password' => Hash::make('password'), 'role' => 'resepsionis'],
            ['id' => 4, 'name' => 'John Doe', 'username' => 'johndoe', 'password' => Hash::make('password'), 'role' => 'pasien'],
        ]);

        // PATIENTS
        DB::table('patients')->insert([
            ['id' => 1, 'user_id' => 4, 'no_rm' => 'RM001', 'nik' => '1234567890123456', 'dob' => '1995-05-01', 'gender' => 'L', 'phone' => '08123456789', 'address' => 'Jl. Melati No. 1'],
        ]);

        // DOCTORS
        DB::table('doctors')->insert([
            ['id' => 1, 'user_id' => 2, 'specialization' => 'Umum', 'sip_number' => 'SIP123456', 'phone' => '08129876543'],
        ]);

        // SCHEDULES
        DB::table('schedules')->insert([
            ['doctor_id' => 1, 'day' => 'Senin', 'start_time' => '08:00:00', 'end_time' => '12:00:00'],
            ['doctor_id' => 1, 'day' => 'Rabu', 'start_time' => '08:00:00', 'end_time' => '12:00:00'],
        ]);

        // APPOINTMENTS
        DB::table('appointments')->insert([
            ['id' => 1, 'patient_id' => 1, 'doctor_id' => 1, 'appointment_date' => '2025-06-01', 'appointment_time' => '08:30:00', 'status' => 'dipesan', 'queu_number' => 1],
            ['id' => 2, 'patient_id' => 1, 'doctor_id' => 1, 'appointment_date' => '2025-06-02', 'appointment_time' => '09:00:00', 'status' => 'selesai', 'queu_number' => 2],
        ]);

        // MEDICAL RECORDS
        DB::table('medical_records')->insert([
            ['appointment_id' => 2, 'subjective' => 'Demam 3 hari', 'objective' => 'Suhu 38.5C', 'assessment' => 'Infeksi virus', 'plan' => 'Parasetamol 500mg'],
        ]);

        // SERVICES
        DB::table('services')->insert([
            ['id' => 1, 'name' => 'Konsultasi', 'price' => 50000],
            ['id' => 2, 'name' => 'Cek Lab', 'price' => 100000],
        ]);

        // PIVOT: appointment_service (pastikan sudah dibuat migrasinya)
        DB::table('appointment_services')->insert([
            ['appointment_id' => 1, 'service_id' => 1, 'quantity' => 1],
            ['appointment_id' => 2, 'service_id' => 1, 'quantity' => 1],
            ['appointment_id' => 2, 'service_id' => 2, 'quantity' => 1],
        ]);

        // PAYMENTS
        DB::table('payments')->insert([
            ['appointment_id' => 2, 'total' => 150000, 'method' => 'tunai', 'paid_at' => Carbon::now()],
        ]);
    }
}
