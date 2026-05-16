@extends('layouts.app')
@section('title', 'Trouver un médecin')

@section('content')
<div class="page-header">
    <h3 class="fw-bold mb-1"><i class="bi bi-search me-2"></i>Trouver un médecin</h3>
    <p class="mb-0 opacity-75">Consultez nos professionnels de santé disponibles</p>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('doctors.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-500 small text-muted">Rechercher</label>
                <input type="text" name="search" class="form-control" placeholder="Nom ou spécialisation..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-500 small text-muted">Spécialisation</label>
                <select name="specialization" class="form-select">
                    <option value="">Toutes</option>
                    @foreach($specializations as $spec)
                        <option value="{{ $spec }}" {{ request('specialization')===$spec ? 'selected' : '' }}>{{ $spec }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-500 small text-muted">Prix min (MAD)</label>
                <input type="number" name="min_fee" class="form-control" value="{{ request('min_fee') }}" placeholder="0">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-500 small text-muted">Prix max (MAD)</label>
                <input type="number" name="max_fee" class="form-control" value="{{ request('max_fee') }}" placeholder="1000">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted mb-0">{{ $doctors->total() }} médecin(s) trouvé(s)</p>
    @if(request()->hasAny(['search','specialization','min_fee','max_fee']))
        <a href="{{ route('doctors.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Réinitialiser</a>
    @endif
</div>

<div class="row g-4">
    @forelse($doctors as $doctor)
    <div class="col-md-6 col-lg-4">
        <div class="card doctor-card h-100">
            <div class="card-body p-4">
                <div class="d-flex gap-3 align-items-start mb-3">
                    <img src="{{ $doctor->user->photo_url }}" alt="{{ $doctor->user->full_name }}" class="doctor-avatar">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">Dr. {{ $doctor->user->full_name }}</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill small">{{ $doctor->specialization }}</span>
                        <div class="mt-1 stars small">
                            @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$doctor->average_rating ? '-fill' : '' }}"></i>@endfor
                            <span class="text-muted ms-1">{{ $doctor->average_rating }}/5</span>
                        </div>
                    </div>
                </div>
                @if($doctor->biography)
                <p class="text-muted small mb-3" style="overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $doctor->biography }}</p>
                @endif
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong class="text-success">{{ number_format($doctor->consultation_fee,0) }} MAD</strong>
                        <span class="text-muted small">/consultation</span>
                    </div>
                    <span class="badge {{ $doctor->is_available ? 'bg-success' : 'bg-secondary' }} bg-opacity-15 {{ $doctor->is_available ? 'text-success' : 'text-secondary' }} rounded-pill">
                        {{ $doctor->is_available ? 'Disponible' : 'Indisponible' }}
                    </span>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-eye me-2"></i>Voir le profil
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-person-x fs-1 text-muted d-block mb-3 opacity-50"></i>
                <h5 class="text-muted">Aucun médecin trouvé</h5>
                <a href="{{ route('doctors.index') }}" class="btn btn-primary mt-2">Voir tous les médecins</a>
            </div>
        </div>
    </div>
    @endforelse
</div>
<div class="mt-4 d-flex justify-content-center">{{ $doctors->withQueryString()->links() }}</div>
@endsection
