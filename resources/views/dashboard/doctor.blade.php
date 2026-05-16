@extends('layouts.app')
@section('title', 'Tableau de bord')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <img src="{{ auth()->user()->photo_url }}" alt="avatar" class="doctor-avatar-lg">
        <div>
            <h3 class="mb-1 fw-bold">Bonjour, Dr. {{ auth()->user()->first_name }} 👋</h3>
            <p class="mb-0 opacity-75"><i class="bi bi-stethoscope me-2"></i>{{ $doctor->specialization }}</p>
            <p class="mb-0 opacity-75"><i class="bi bi-envelope me-2"></i>{{ auth()->user()->email }}</p>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#f39c12,#e67e22);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="stat-number">{{ $stats['pending'] }}</div><div class="stat-label">En attente</div></div>
                <i class="bi bi-hourglass-split stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#3498db,#2980b9);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="stat-number">{{ $stats['accepted'] }}</div><div class="stat-label">Acceptés</div></div>
                <i class="bi bi-calendar-check stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#2ecc71,#27ae60);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="stat-number">{{ $stats['completed'] }}</div><div class="stat-label">Terminés</div></div>
                <i class="bi bi-check-circle stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#9b59b6,#8e44ad);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="stat-number">{{ $stats['total'] }}</div><div class="stat-label">Total</div></div>
                <i class="bi bi-clipboard2-pulse stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <span><i class="bi bi-calendar-day me-2"></i>Rendez-vous d'aujourd'hui
                    <span class="badge bg-warning text-dark ms-2">{{ $todayAppointments->count() }}</span>
                </span>
                <a href="{{ route('doctor.appointments') }}" class="btn btn-sm btn-light">Voir tout</a>
            </div>
            <div class="card-body p-0">
                @forelse($todayAppointments as $appt)
                <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                    <img src="{{ $appt->patient->photo_url }}" alt="" style="width:44px;height:44px;border-radius:50%;object-fit:cover;">
                    <div class="flex-grow-1">
                        <div class="fw-600">{{ $appt->patient->full_name }}</div>
                        <div class="text-muted small">
                            <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('H:i') }}
                            @if($appt->reason) — {{ $appt->reason }} @endif
                        </div>
                    </div>
                    <span class="badge badge-{{ $appt->status }} px-3 py-2 rounded-pill">{{ $appt->status_label }}</span>
                    <a href="{{ route('doctor.appointments.show', $appt) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-50"></i>Aucun rendez-vous aujourd'hui
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header py-3">
                <i class="bi bi-chat-dots me-2"></i>Messages non lus
                @if($unreadCount > 0)<span class="badge bg-danger ms-2">{{ $unreadCount }}</span>@endif
            </div>
            <div class="card-body text-center py-4">
                @if($unreadCount > 0)
                    <i class="bi bi-envelope-open-fill fs-1 text-warning mb-2 d-block"></i>
                    <p class="mb-3">Vous avez <strong>{{ $unreadCount }}</strong> message(s) non lu(s).</p>
                    <a href="{{ route('messages.index') }}" class="btn btn-primary btn-sm px-4">Voir les messages</a>
                @else
                    <i class="bi bi-check-circle-fill fs-1 text-success mb-2 d-block"></i>
                    <p class="text-muted mb-0">Aucun message non lu.</p>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header py-3"><i class="bi bi-lightning me-2"></i>Accès rapide</div>
            <div class="list-group list-group-flush rounded-bottom">
                <a href="{{ route('doctor.appointments') }}?status=pending" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-hourglass me-2 text-warning"></i>Demandes en attente</span>
                    <span class="badge bg-warning text-dark">{{ $stats['pending'] }}</span>
                </a>
                <a href="{{ route('messages.index') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-chat me-2 text-primary"></i>Messagerie
                </a>
                <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-person-gear me-2 text-secondary"></i>Mon profil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
