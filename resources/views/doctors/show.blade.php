@extends('layouts.app')
@section('title', 'Dr. ' . $doctor->user->full_name)

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body p-4">
                <img src="{{ $doctor->user->photo_url }}" alt="{{ $doctor->user->full_name }}" class="doctor-avatar-lg mb-3">
                <h4 class="fw-bold mb-1">Dr. {{ $doctor->user->full_name }}</h4>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill mb-2">{{ $doctor->specialization }}</span>
                <div class="stars mb-3">
                    @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$doctor->average_rating ? '-fill' : '' }}"></i>@endfor
                    <span class="text-muted small ms-1">{{ $doctor->average_rating }}/5</span>
                </div>
                <div class="d-flex justify-content-around border-top border-bottom py-3 mb-3">
                    <div class="text-center"><div class="fw-bold fs-5 text-primary">{{ $doctor->appointments->count() }}</div><div class="text-muted small">Consultations</div></div>
                    <div class="text-center"><div class="fw-bold fs-5 text-warning">{{ $doctor->reviews->count() }}</div><div class="text-muted small">Avis</div></div>
                    <div class="text-center"><div class="fw-bold fs-5 text-success">{{ number_format($doctor->consultation_fee,0) }}</div><div class="text-muted small">MAD</div></div>
                </div>
                @if($doctor->user->phone)<p class="text-muted small mb-2"><i class="bi bi-telephone me-2"></i>{{ $doctor->user->phone }}</p>@endif
                <p class="text-muted small mb-3"><i class="bi bi-envelope me-2"></i>{{ $doctor->user->email }}</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('appointments.create', $doctor) }}" class="btn btn-primary"><i class="bi bi-calendar-plus me-2"></i>Prendre rendez-vous</a>
                    <a href="{{ route('messages.thread', $doctor->user) }}" class="btn btn-outline-primary"><i class="bi bi-chat-dots me-2"></i>Envoyer un message</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        @if($doctor->biography)
        <div class="card mb-4">
            <div class="card-header py-3"><i class="bi bi-person-lines-fill me-2"></i>À propos</div>
            <div class="card-body"><p class="mb-0 lh-lg">{{ $doctor->biography }}</p></div>
        </div>
        @endif
        <div class="card">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <span><i class="bi bi-star-fill me-2"></i>Avis des patients</span>
                <span class="badge bg-light text-dark">{{ $doctor->reviews->count() }} avis</span>
            </div>
            <div class="card-body p-0">
                @forelse($doctor->reviews as $review)
                <div class="px-4 py-3 border-bottom">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <img src="{{ $review->patient->photo_url }}" alt="" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                        <div>
                            <div class="fw-600 small">{{ $review->patient->full_name }}</div>
                            <div class="stars" style="font-size:.75rem;">
                                @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$review->rating ? '-fill' : '' }}"></i>@endfor
                            </div>
                        </div>
                        <span class="text-muted small ms-auto">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    @if($review->comment)<p class="text-muted small mb-0 ms-5">{{ $review->comment }}</p>@endif
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-chat-square-text fs-1 d-block mb-2 opacity-50"></i>Aucun avis pour le moment.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
