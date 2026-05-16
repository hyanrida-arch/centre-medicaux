@extends('layouts.app')
@section('title', 'Prendre rendez-vous')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="page-header mb-4">
            <h4 class="fw-bold mb-1"><i class="bi bi-calendar-plus me-2"></i>Prendre un rendez-vous</h4>
            <p class="mb-0 opacity-75">avec Dr. {{ $doctor->user->full_name }}</p>
        </div>
        <div class="card mb-4">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <img src="{{ $doctor->user->photo_url }}" class="doctor-avatar" alt="">
                <div>
                    <div class="fw-bold">Dr. {{ $doctor->user->full_name }}</div>
                    <div class="text-muted small">{{ $doctor->specialization }}</div>
                    <div class="text-success small fw-600 mt-1">{{ number_format($doctor->consultation_fee,0) }} MAD</div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header py-3"><i class="bi bi-calendar-event me-2"></i>Détails du rendez-vous</div>
            <div class="card-body p-4">
                @if($errors->any())
                <div class="alert alert-danger rounded-3 mb-4">
                    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif
                <form method="POST" action="{{ route('appointments.store', $doctor) }}">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Date <span class="text-danger">*</span></label>
                            <input type="date" name="appointment_date" class="form-control @error('appointment_date') is-invalid @enderror"
                                   value="{{ old('appointment_date') }}" min="{{ date('Y-m-d') }}" required>
                            @error('appointment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Heure <span class="text-danger">*</span></label>
                            <select name="appointment_time" class="form-select @error('appointment_time') is-invalid @enderror" required>
                                <option value="">Choisir...</option>
                                @foreach(['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30'] as $t)
                                    <option value="{{ $t }}" {{ old('appointment_time')===$t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                            @error('appointment_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-500">Motif <span class="text-muted">(optionnel)</span></label>
                        <textarea name="reason" class="form-control" rows="4" placeholder="Décrivez brièvement la raison...">{{ old('reason') }}</textarea>
                    </div>
                    <div class="d-flex gap-3">
                        <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-outline-secondary flex-grow-1"><i class="bi bi-arrow-left me-2"></i>Retour</a>
                        <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-send me-2"></i>Confirmer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
