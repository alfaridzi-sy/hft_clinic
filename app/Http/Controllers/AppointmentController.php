<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;

        $query = Appointment::with(['patient.user', 'doctor.user'])
            ->orderByDesc('created_at');

        // Filter default tanggal hari ini
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        } else {
            $query->whereDate('appointment_date', now()->toDateString());
        }

        // Jika role dokter, batasi hanya appointment dokter tersebut
        if ($role === 'dokter') {
            $doctor = Doctor::where('user_id', $user->id)->first();
            if ($doctor) {
                $query->where('doctor_id', $doctor->id);
            } else {
                $query->whereRaw('0=1'); // kosong
            }
        }

        // Jika role pasien, batasi hanya appointment miliknya
        if ($role === 'pasien') {
            $patient = Patient::where('user_id', $user->id)->first();
            if ($patient) {
                $query->where('patient_id', $patient->id);
            } else {
                $query->whereRaw('0=1'); // kosong
            }
        }

        $appointments = $query->get();

        // Ambil data pasien dan dokter untuk dropdown hanya jika admin/resepsionis
        $patients = collect();
        $doctors = collect();

        if (in_array($role, ['admin', 'resepsionis'])) {
            $patients = Patient::with('user')->get();
            $doctors = Doctor::with(['user', 'schedules'])->get();
        }

        return view('appointments.index', compact('appointments', 'role', 'patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);

        // Ambil nomor antrian tertinggi
        $maxQueue = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->max('queu_number');

        $newQueue = $maxQueue ? $maxQueue + 1 : 1;

        $appointment = Appointment::create([
            ...$validated,
            'queu_number' => $newQueue,
            'status' => 'dipesan',
        ]);

        return response()->json([
            'success' => true,
            'appointment' => $appointment->load('doctor.user'),
            'print_url' => route('appointments.print', $appointment->id),
        ]);
    }

    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);
        if ($appointment->status === 'dipesan') {
            $appointment->status = 'batal';
            $appointment->save();
        }

        return redirect()->route('appointments.index')->with('success', 'Appointment dibatalkan.');
    }

    public function examine($id)
    {
        return redirect()->route('medical-records.create', ['appointment_id' => $id]);
    }

    public function print(Appointment $appointment)
    {
        return view('appointments.print', compact('appointment'));
    }
}
