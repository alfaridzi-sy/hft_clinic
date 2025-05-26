<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        Service::create($request->only('name', 'price'));

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $service->update($request->only('name', 'price'));

        return response()->json(['success' => true]);
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json(['success' => true]);
    }
}
