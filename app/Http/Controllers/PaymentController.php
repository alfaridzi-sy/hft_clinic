<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\AppointmentService;
use Illuminate\Http\Request;


class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        $appointments = Appointment::with('patient.user', 'services')
            ->where('status', 'selesai')
            ->whereDate('appointment_date', $date)
            ->get();

        return view('payments.index', compact('appointments', 'date'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        $request->validate([
            'method' => 'required',
        ]);

        $total = $appointment->services->sum(function ($s) {
            return $s->price;
        });

        Payment::create([
            'appointment_id' => $appointment->id,
            'total' => $total,
            'method' => $request->method,
            'paid_at' => now(),
        ]);

        return redirect()->route('payments.index');
    }

    public function addService(Request $request, Appointment $appointment)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id'
        ]);

        AppointmentService::create([
            'appointment_id' => $appointment->id,
            'service_id' => $request->service_id,
        ]);

        return redirect()->back();
    }
}
