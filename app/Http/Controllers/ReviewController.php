<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Appointment $appointment)
    {
        abort_unless($appointment->patient_id === auth()->id(), 403);
        abort_unless($appointment->isCompleted(), 403);
        abort_if($appointment->review()->exists(), 403, 'Avis déjà soumis.');

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'patient_id'     => auth()->id(),
            'doctor_id'      => $appointment->doctor_id,
            'appointment_id' => $appointment->id,
            'rating'         => $validated['rating'],
            'comment'        => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'Merci pour votre avis!');
    }
}
