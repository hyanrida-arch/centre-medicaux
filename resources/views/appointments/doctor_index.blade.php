@extends('layouts.app')
@section('title', 'Gestion des rendez-vous')

@section('content')
<div class="page-header">
    <h3 class="fw-bold mb-1"><i class="bi bi-calendar-check me-2"></i>Gestion des rendez-vous</h3>
    <p class="mb-0 opacity-75">Gérez vos demandes et vos consultations</p>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-6">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('doctor.appointments') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">Tous</a>
                    <a href="{{ route('doctor.appointments', ['status'=>'pending']) }}" class="btn btn-sm {{ request('status')==='pending' ? 'btn-warning text-dark' : 'btn-outline-secondary' }} rounded-pill">En attente</a>
                    <a href="{{ route('doctor.appointments', ['status'=>'accepted']) }}" class="btn btn-sm {{ request('status')==='accepted' ? 'btn-info text-dark' : 'btn-outline-secondary' }} rounded-pill">Acceptés</a>
                    <a href="{{ route('doctor.appointments', ['status'=>'completed']) }}" class="btn btn-sm {{ request('status')==='completed' ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill">Terminés</a>
                </div>
            </div>
            <div class="col-md-4">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-2">
                <a href="{{ route('doctor.appointments') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @forelse($appointments as $appt)
        <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
            <img src="{{ $appt->patient->photo_url }}" alt="" style="width:50px;height:50px;border-radius:50%;object-fit:cover;border:2px solid #e0e0e0">
            <div class="flex-grow-1">
                <div class="fw-bold">{{ $appt->patient->full_name }}</div>
                <div class="text-muted small">
                    <i class="bi bi-calendar me-1"></i>{{ $appt->appointment_date->format('d/m/Y') }}
                    <i class="bi bi-clock ms-2 me-1"></i>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('H:i') }}
                </div>
                @if($appt->reason)<div class="text-muted small"><i class="bi bi-chat-left-text me-1"></i>{{ $appt->reason }}</div>@endif
            </div>
            <span class="badge badge-{{ $appt->status }} px-3 py-2 rounded-pill">{{ $appt->status_label }}</span>
            <div class="d-flex gap-2">
                <a href="{{ route('doctor.appointments.show', $appt) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                @if($appt->isPending())
                    <form action="{{ route('appointments.accept', $appt) }}" method="POST">@csrf @method('PATCH')
                        <button class="btn btn-sm btn-success" title="Accepter"><i class="bi bi-check-lg"></i></button>
                    </form>
                    <form action="{{ route('appointments.refuse', $appt) }}" method="POST" onsubmit="return confirm('Refuser ?')">@csrf @method('PATCH')
                        <button class="btn btn-sm btn-danger" title="Refuser"><i class="bi bi-x-lg"></i></button>
                    </form>
                @elseif($appt->isAccepted())
                    <form action="{{ route('appointments.complete', $appt) }}" method="POST">@csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-success" title="Terminer"><i class="bi bi-check2-circle"></i></button>
                    </form>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x fs-1 d-block mb-3 opacity-50"></i><h6>Aucun rendez-vous trouvé</h6>
        </div>
        @endforelse
    </div>
</div>
<div class="mt-4 d-flex justify-content-center">{{ $appointments->withQueryString()->links() }}</div>
@endsection
