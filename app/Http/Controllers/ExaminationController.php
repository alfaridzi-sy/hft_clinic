<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Service;
use App\Models\AppointmentService;
use Illuminate\Support\Facades\Auth;

class ExaminationController extends Controller
{
    public function index()
    {
        $doctorId = Doctor::where('user_id', Auth::id())->value('id');
        $appointments = Appointment::with('patient.user')
            ->where('doctor_id', $doctorId)
            ->where('status', 'dipesan')
            ->get();

        return view('examinations.index', compact('appointments'));
    }

    public function edit(Appointment $appointment)
    {
        $services = Service::all();
        return view('examinations.edit', compact('appointment', 'services'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'subjective' => 'required',
            'objective' => 'required',
            'assessment' => 'required',
            'plan' => 'required',
        ]);

        MedicalRecord::create([
            'appointment_id' => $appointment->id,
            'subjective' => $request->subjective,
            'objective' => $request->objective,
            'assessment' => $request->assessment,
            'plan' => $request->plan,
        ]);

        // Tambah layanan jika ada
        if ($request->has('services')) {
            foreach ($request->services as $serviceId) {
                AppointmentService::create([
                    'appointment_id' => $appointment->id,
                    'service_id' => $serviceId,
                ]);
            }
        }

        $appointment->update(['status' => 'selesai']);
        return redirect()->route('examinations.index');
    }
}
