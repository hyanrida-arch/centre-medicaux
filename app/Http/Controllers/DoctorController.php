<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with('user', 'reviews')->where('is_available', true);

        if ($request->filled('search'))         $query->search($request->search);
        if ($request->filled('specialization')) $query->where('specialization', $request->specialization);
        if ($request->filled('min_fee') && $request->filled('max_fee'))
            $query->byFee($request->min_fee, $request->max_fee);

        $doctors         = $query->paginate(12);
        $specializations = Doctor::distinct()->pluck('specialization')->sort()->values();

        return view('doctors.index', compact('doctors', 'specializations'));
    }

    public function show(Doctor $doctor)
    {
        $doctor->load('user', 'reviews.patient', 'appointments');
        return view('doctors.show', compact('doctor'));
    }

    public function dashboard()
    {
        $user   = auth()->user();
        $doctor = $user->doctor;

        $stats = [
            'pending'   => $doctor->appointments()->pending()->count(),
            'accepted'  => $doctor->appointments()->accepted()->count(),
            'completed' => $doctor->appointments()->completed()->count(),
            'total'     => $doctor->appointments()->count(),
        ];

        $todayAppointments = $doctor->appointments()
            ->today()->with('patient')->orderBy('appointment_time')->get();

        $unreadCount = $user->unreadMessages()->count();

        return view('dashboard.doctor', compact('doctor', 'stats', 'todayAppointments', 'unreadCount'));
    }
}
