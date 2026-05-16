@extends('layouts.app')
@section('title', 'Détail du rendez-vous')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <a href="{{ auth()->user()->isDoctor() ? route('doctor.appointments') : route('patient.appointments') }}" class="btn btn-outline-secondary btn-sm mb-4">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
        <div class="card mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-event me-2"></i>Rendez-vous #{{ $appointment->id }}</span>
                <span class="badge badge-{{ $appointment->status }} px-3 py-2 rounded-pill fs-6">{{ $appointment->status_label }}</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-3">
                            <div class="text-muted small fw-600 mb-2 text-uppercase">Patient</div>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $appointment->patient->photo_url }}" alt="" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                                <div>
                                    <div class="fw-bold">{{ $appointment->patient->full_name }}</div>
                                    <div class="text-muted small">{{ $appointment->patient->email }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-3">
                            <div class="text-muted small fw-600 mb-2 text-uppercase">Médecin</div>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $appointment->doctor->user->photo_url }}" alt="" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                                <div>
                                    <div class="fw-bold">Dr. {{ $appointment->doctor->user->full_name }}</div>
                                    <div class="text-muted small">{{ $appointment->doctor->specialization }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 text-center">
                                    <i class="bi bi-calendar3 text-primary fs-4 d-block mb-1"></i>
                                    <div class="fw-bold">{{ $appointment->appointment_date->format('d/m/Y') }}</div>
                                    <div class="text-muted small">Date</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 text-center">
                                    <i class="bi bi-clock text-primary fs-4 d-block mb-1"></i>
                                    <div class="fw-bold">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</div>
                                    <div class="text-muted small">Heure</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 text-center">
                                    <i class="bi bi-currency-dollar text-success fs-4 d-block mb-1"></i>
                                    <div class="fw-bold text-success">{{ number_format($appointment->doctor->consultation_fee,0) }} MAD</div>
                                    <div class="text-muted small">Frais</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($appointment->reason)
                    <div class="col-12">
                        <div class="border rounded-3 p-3">
                            <div class="text-muted small fw-600 mb-1 text-uppercase">Motif</div>
                            <p class="mb-0">{{ $appointment->reason }}</p>
                        </div>
                    </div>
                    @endif
                    @if($appointment->notes)
                    <div class="col-12">
                        <div class="border border-success rounded-3 p-3">
                            <div class="text-success small fw-600 mb-1 text-uppercase"><i class="bi bi-journal-medical me-1"></i>Notes de consultation</div>
                            <p class="mb-0">{{ $appointment->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @if(auth()->user()->isDoctor())
            <div class="card-footer bg-transparent p-4 pt-0">
                <div class="d-flex gap-3 flex-wrap">
                    @if($appointment->isPending())
                        <form action="{{ route('appointments.accept', $appointment) }}" method="POST">@csrf @method('PATCH')
                            <button class="btn btn-success"><i class="bi bi-check-lg me-2"></i>Accepter</button></form>
                        <form action="{{ route('appointments.refuse', $appointment) }}" method="POST" onsubmit="return confirm('Refuser ?')">@csrf @method('PATCH')
                            <button class="btn btn-danger"><i class="bi bi-x-lg me-2"></i>Refuser</button></form>
                    @elseif($appointment->isAccepted())
                        <form action="{{ route('appointments.complete', $appointment) }}" method="POST">@csrf @method('PATCH')
                            <button class="btn btn-success"><i class="bi bi-check2-circle me-2"></i>Marquer terminé</button></form>
                    @endif
                </div>
                @if($appointment->isCompleted())
                <div class="mt-4">
                    <form action="{{ route('appointments.notes', $appointment) }}" method="POST">@csrf @method('PATCH')
                        <label class="form-label fw-600"><i class="bi bi-journal-medical me-2"></i>Notes de consultation</label>
                        <textarea name="notes" class="form-control mb-3" rows="4" placeholder="Notes médicales...">{{ $appointment->notes }}</textarea>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Enregistrer</button>
                    </form>
                </div>
                @endif
            </div>
            @endif
        </div>

        @if(auth()->user()->isPatient() && $appointment->isCompleted())
            @if($appointment->review)
            <div class="card">
                <div class="card-header py-3"><i class="bi bi-star-fill me-2 text-warning"></i>Votre avis</div>
                <div class="card-body">
                    <div class="stars mb-2">@for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$appointment->review->rating ? '-fill' : '' }}"></i>@endfor</div>
                    @if($appointment->review->comment)<p class="text-muted mb-0">{{ $appointment->review->comment }}</p>@endif
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-header py-3"><i class="bi bi-star me-2"></i>Laisser un avis</div>
                <div class="card-body">
                    <form action="{{ route('reviews.store', $appointment) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-500">Note</label>
                            <div class="d-flex gap-3">
                                @for($i=1;$i<=5;$i++)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" {{ old('rating')==$i ? 'checked' : '' }}>
                                    <label class="form-check-label" for="star{{ $i }}">{{ $i }} ⭐</label>
                                </div>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-500">Commentaire <span class="text-muted">(optionnel)</span></label>
                            <textarea name="comment" class="form-control" rows="3" placeholder="Partagez votre expérience...">{{ old('comment') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-warning fw-600"><i class="bi bi-star-fill me-2"></i>Soumettre l'avis</button>
                    </form>
                </div>
            </div>
            @endif
        @endif
    </div>
</div>
@endsection
