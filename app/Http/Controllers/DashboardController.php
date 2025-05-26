<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Jumlah data
        $totalPatients = Patient::count();
        $totalDoctors = Doctor::count();
        $totalAppointments = Appointment::count();
        $totalPayments = Payment::count();

        $months = collect(range(0, 5))->map(function ($i) {
            return Carbon::now()->subMonths($i)->format('Y-m');
        })->reverse()->values();

        $visits = $months->map(function ($month) {
            return Appointment::where('appointment_date', 'like', "$month%")->count();
        });

        $revenues = $months->map(function ($month) {
            return Payment::where('created_at', 'like', "$month%")->sum('total');
        });

        return view('dashboard', [
            'totalPatients' => $totalPatients,
            'totalDoctors' => $totalDoctors,
            'totalAppointments' => $totalAppointments,
            'totalPayments' => $totalPayments,
            'months' => $months,
            'visits' => $visits,
            'revenues' => $revenues,
        ]);
    }
}
