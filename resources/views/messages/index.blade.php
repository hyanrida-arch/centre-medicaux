@extends('layouts.app')
@section('title', 'Messagerie')

@section('content')
<div class="page-header">
    <h3 class="fw-bold mb-1"><i class="bi bi-chat-dots me-2"></i>Messagerie</h3>
    <p class="mb-0 opacity-75">Vos conversations</p>
</div>
<div class="card">
    <div class="card-body p-0">
        @forelse($conversations as $contact)
        <a href="{{ route('messages.thread', $contact) }}" class="text-decoration-none text-dark">
            <div class="conversation-item d-flex align-items-center gap-3 px-4 py-3 border-bottom {{ $contact->unreadCount > 0 ? 'bg-primary bg-opacity-5' : '' }}">
                <div class="position-relative">
                    <img src="{{ $contact->photo_url }}" alt="" style="width:52px;height:52px;border-radius:50%;object-fit:cover;border:2px solid {{ $contact->unreadCount > 0 ? '#1a6b8a' : '#e0e0e0' }}">
                    @if($contact->unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-pill" style="font-size:.65rem;">{{ $contact->unreadCount }}</span>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <div class="fw-{{ $contact->unreadCount > 0 ? 'bold' : '500' }}">
                            {{ $contact->isDoctor() ? 'Dr. ' : '' }}{{ $contact->full_name }}
                        </div>
                        @if($contact->lastMessage)<small class="text-muted">{{ $contact->lastMessage->created_at->diffForHumans() }}</small>@endif
                    </div>
                    @if($contact->lastMessage)
                    <div class="text-muted small text-truncate" style="max-width:400px;">
                        @if($contact->lastMessage->sender_id === auth()->id())<span class="text-primary">Vous: </span>@endif
                        {{ $contact->lastMessage->body }}
                    </div>
                    @endif
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </div>
        </a>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-chat-square-text fs-1 d-block mb-3 opacity-50"></i>
            <h6>Aucune conversation</h6>
            @if(auth()->user()->isPatient())
            <a href="{{ route('doctors.index') }}" class="btn btn-primary mt-2"><i class="bi bi-search me-2"></i>Trouver un médecin</a>
            @endif
        </div>
        @endforelse
    </div>
</div>
@endsection
