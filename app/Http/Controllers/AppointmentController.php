<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function create(Doctor $doctor)
    {
        return view('appointments.create', compact('doctor'));
    }

    public function store(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason'           => 'nullable|string|max:500',
        ]);

        Appointment::create([
            'patient_id'       => auth()->id(),
            'doctor_id'        => $doctor->id,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'reason'           => $validated['reason'] ?? null,
            'status'           => 'pending',
        ]);

        return redirect()->route('patient.appointments')->with('success', 'Rendez-vous demandé avec succès!');
    }

    public function patientIndex(Request $request)
    {
        $query = Appointment::where('patient_id', auth()->id())
            ->with('doctor.user')->orderByDesc('appointment_date');

        if ($request->filled('status')) $query->where('status', $request->status);

        return view('appointments.patient_index', ['appointments' => $query->paginate(10)]);
    }

    public function cancel(Appointment $appointment)
    {
        abort_unless($appointment->patient_id === auth()->id(), 403);
        abort_unless(in_array($appointment->status, ['pending', 'accepted']), 403);
        $appointment->update(['status' => 'cancelled']);
        return back()->with('success', 'Rendez-vous annulé.');
    }

    public function doctorIndex(Request $request)
    {
        $doctor = auth()->user()->doctor;
        $query  = $doctor->appointments()->with('patient')->orderByDesc('appointment_date');

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('date'))   $query->whereDate('appointment_date', $request->date);

        return view('appointments.doctor_index', ['appointments' => $query->paginate(10)]);
    }

    public function accept(Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);
        abort_unless($appointment->isPending(), 403);
        $appointment->update(['status' => 'accepted']);
        return back()->with('success', 'Rendez-vous accepté.');
    }

    public function refuse(Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);
        abort_unless($appointment->isPending(), 403);
        $appointment->update(['status' => 'cancelled']);
        return back()->with('success', 'Rendez-vous refusé.');
    }

    public function complete(Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);
        abort_unless($appointment->isAccepted(), 403);
        $appointment->update(['status' => 'completed']);
        return back()->with('success', 'Rendez-vous marqué comme terminé.');
    }

    public function addNotes(Request $request, Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);
        abort_unless($appointment->isCompleted(), 403);
        $validated = $request->validate(['notes' => 'required|string|max:2000']);
        $appointment->update(['notes' => $validated['notes']]);
        return back()->with('success', 'Notes ajoutées avec succès.');
    }

    public function show(Appointment $appointment)
    {
        if (auth()->user()->isDoctor()) {
            $this->authorizeDoctor($appointment);
        } else {
            abort_unless($appointment->patient_id === auth()->id(), 403);
        }
        $appointment->load('patient', 'doctor.user', 'review');
        return view('appointments.show', compact('appointment'));
    }

    private function authorizeDoctor(Appointment $appointment): void
    {
        abort_unless(
            auth()->user()->isDoctor() &&
            auth()->user()->doctor->id === $appointment->doctor_id,
            403
        );
    }
}
