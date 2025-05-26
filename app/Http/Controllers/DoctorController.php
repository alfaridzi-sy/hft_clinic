<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Auth::user()->role === 'admin'
            ? Doctor::with('user')->get()
            : Doctor::with('user')->where('user_id', Auth::id())->get();

        $specializations = ['Umum', 'Anak', 'Gigi', 'Kandungan', 'Saraf', 'Mata'];

        return view('doctors.index', compact('doctors', 'specializations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'specialization' => 'required',
            'sip_number' => 'required',
            'phone' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make('12345678'),
            'role' => 'dokter',
        ]);

        Doctor::create([
            'user_id' => $user->id,
            'specialization' => $request->specialization,
            'sip_number' => $request->sip_number,
            'phone' => $request->phone,
        ]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'name' => 'required',
            'username' => "required|unique:users,username,{$doctor->user_id}",
            'specialization' => 'required',
            'sip_number' => 'required',
            'phone' => 'required',
        ]);

        $user = $doctor->user;
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $doctor->update([
            'specialization' => $request->specialization,
            'sip_number' => $request->sip_number,
            'phone' => $request->phone,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->user()->delete(); // delete user also
        $doctor->delete();
        return response()->json(['success' => true]);
    }
}
