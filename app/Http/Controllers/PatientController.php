<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $patients = $user->role === 'admin'
            ? Patient::with('user')->latest()->get()
            : Patient::with('user')->where('user_id', $user->id)->get();

        return view('patients.index', compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:patients,nik',
            'dob' => 'required|date',
            'gender' => 'required',
            'phone' => 'required',
            'address' => 'required'
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'username' => $request->nik,
            'role' => 'pasien',
            'password' => Hash::make('12345678'),
        ]);

        // Generate no_rm
        $lastNoRm = Patient::max('no_rm') ?? '000000';
        $newNoRm = str_pad((int)$lastNoRm + 1, 6, '0', STR_PAD_LEFT);

        Patient::create([
            'user_id' => $user->id,
            'no_rm' => $newNoRm,
            'nik' => $request->nik,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'nik' => 'required|unique:patients,nik,' . $patient->id,
            'dob' => 'required|date',
            'gender' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'password' => 'nullable|string|min:6'
        ]);

        $patient->update([
            'nik' => $request->nik,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $userData = [
            'name' => $request->name,
            'username' => $request->nik,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $patient->user->update($userData);

        return response()->json(['success' => true]);
    }

    public function destroy(Patient $patient)
    {
        $patient->user()->delete();
        $patient->delete();

        return response()->json(['success' => true]);
    }
}
