<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Tampilkan daftar appointment selesai dengan detail pembayaran
    public function finished(Request $request)
    {
        $query = Appointment::with(['patient.user', 'doctor.user', 'payment', 'services'])
            ->whereIn('status', ['selesai', 'paid']);

        if ($request->date) {
            $query->where('appointment_date', $request->date);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->get();

        // Hitung total tagihan per appointment
        foreach ($appointments as $appointment) {
            $appointment->total_tagihan = $appointment->services->sum(function ($service) {
                return $service->price * $service->pivot->quantity;
            });
        }

        return view('payments.index', compact('appointments'));
    }

    // Proses pembayaran dan update status
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'method' => 'required|in:tunai,transfer,qris,e-wallet',
            'paid_at' => 'required|date',
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);

        $total = $appointment->services->sum(function ($service) {
            return $service->price * $service->pivot->quantity;
        });

        $payment = Payment::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'total' => $total,
                'method' => $request->method,
                'paid_at' => $request->paid_at,
            ]
        );

        // Update status appointment menjadi 'paid' (atau sesuai enum/status kamu)
        $appointment->update(['status' => 'paid']);

        return response()->json(['success' => true]);
    }

    public function receipt(Appointment $appointment)
    {
        $appointment->load(['patient.user', 'doctor.user', 'payment', 'services']);

        return view('payments.receipt', compact('appointment'));
    }
}
