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
    public function create(Appointment $appointment)
    {
        $services = Service::all();
        $selectedServices = AppointmentService::where('appointment_id', $appointment->id)->with('service')->get();
        return view('medical_records.create', compact('appointment', 'services', 'selectedServices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'subjective' => 'required|string',
            'objective' => 'required|string',
            'assessment' => 'required|string',
            'plan' => 'required|string',
        ]);

        $record = MedicalRecord::where('appointment_id', $request->appointment_id)->first();

        if ($record) {
            $record->update($request->only('subjective', 'objective', 'assessment', 'plan'));
        } else {
            MedicalRecord::create($request->all());
        }

        // Update status appointment menjadi 'selesai'
        $appointment = Appointment::find($request->appointment_id);
        if ($appointment) {
            $appointment->update(['status' => 'selesai']);
        }

        return redirect()->route('examinations.create', $request->appointment_id)
            ->with('success', 'Pemeriksaan berhasil disimpan dan status appointment diperbarui.');
    }

    public function addService(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'service_id' => 'required|exists:services,id',
            'quantity' => 'required|integer|min:1',
        ]);

        AppointmentService::create($request->only('appointment_id', 'service_id', 'quantity'));

        return back()->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        // Contoh hapus layanan dari pivot atau service
        $servicePivot = AppointmentService::findOrFail($id);
        $servicePivot->delete();

        return response()->json(['message' => 'Layanan berhasil dihapus']);
    }
}
