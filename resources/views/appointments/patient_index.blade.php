@extends('layouts.app')
@section('title', 'Mes rendez-vous')

@section('content')
<div class="page-header">
    <h3 class="fw-bold mb-1"><i class="bi bi-calendar3 me-2"></i>Mes rendez-vous</h3>
    <p class="mb-0 opacity-75">Historique et suivi de vos rendez-vous médicaux</p>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <span class="text-muted small fw-600">Filtrer :</span>
            <a href="{{ route('patient.appointments') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">Tous</a>
            <a href="{{ route('patient.appointments', ['status'=>'pending']) }}" class="btn btn-sm {{ request('status')==='pending' ? 'btn-warning text-dark' : 'btn-outline-secondary' }} rounded-pill">En attente</a>
            <a href="{{ route('patient.appointments', ['status'=>'accepted']) }}" class="btn btn-sm {{ request('status')==='accepted' ? 'btn-info text-dark' : 'btn-outline-secondary' }} rounded-pill">Acceptés</a>
            <a href="{{ route('patient.appointments', ['status'=>'completed']) }}" class="btn btn-sm {{ request('status')==='completed' ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill">Terminés</a>
            <a href="{{ route('patient.appointments', ['status'=>'cancelled']) }}" class="btn btn-sm {{ request('status')==='cancelled' ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill">Annulés</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @forelse($appointments as $appt)
        <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
            <img src="{{ $appt->doctor->user->photo_url }}" alt="" style="width:50px;height:50px;border-radius:50%;object-fit:cover;border:2px solid #e8f4f8">
            <div class="flex-grow-1">
                <div class="fw-bold">Dr. {{ $appt->doctor->user->full_name }}</div>
                <div class="text-muted small"><i class="bi bi-stethoscope me-1"></i>{{ $appt->doctor->specialization }}</div>
                <div class="text-muted small">
                    <i class="bi bi-calendar me-1"></i>{{ $appt->appointment_date->format('d/m/Y') }}
                    <i class="bi bi-clock ms-2 me-1"></i>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('H:i') }}
                </div>
            </div>
            <div class="d-flex flex-column align-items-end gap-2">
                <span class="badge badge-{{ $appt->status }} px-3 py-2 rounded-pill">{{ $appt->status_label }}</span>
                <div class="d-flex gap-2">
                    <a href="{{ route('patient.appointments.show', $appt) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                    @if($appt->isPending() || $appt->isAccepted())
                    <form action="{{ route('appointments.cancel', $appt) }}" method="POST" onsubmit="return confirm('Annuler ?')">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i></button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x fs-1 d-block mb-3 opacity-50"></i>
            <h6>Aucun rendez-vous</h6>
            <a href="{{ route('doctors.index') }}" class="btn btn-primary mt-2"><i class="bi bi-search me-2"></i>Trouver un médecin</a>
        </div>
        @endforelse
    </div>
</div>
<div class="mt-4 d-flex justify-content-center">{{ $appointments->withQueryString()->links() }}</div>
@endsection
