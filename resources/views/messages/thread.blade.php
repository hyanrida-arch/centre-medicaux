@extends('layouts.app')
@section('title', 'Conversation')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card mb-0" style="border-radius:14px 14px 0 0;">
            <div class="card-body py-3 px-4 d-flex align-items-center gap-3 border-bottom">
                <a href="{{ route('messages.index') }}" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-arrow-left"></i></a>
                <img src="{{ $contact->photo_url }}" alt="" style="width:44px;height:44px;border-radius:50%;object-fit:cover;">
                <div>
                    <div class="fw-bold">{{ $contact->isDoctor() ? 'Dr. ' : '' }}{{ $contact->full_name }}</div>
                    @if($contact->isDoctor() && $contact->doctor)<div class="text-muted small">{{ $contact->doctor->specialization }}</div>@endif
                </div>
                @if($contact->isDoctor())
                <div class="ms-auto">
                    <a href="{{ route('doctors.show', $contact->doctor) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-person-vcard me-1"></i>Profil</a>
                </div>
                @endif
            </div>
        </div>
        <div class="card rounded-0 border-0">
            <div class="card-body p-4" style="min-height:400px;max-height:60vh;overflow-y:auto;" id="msg-container">
                @forelse($messages as $msg)
                <div class="d-flex mb-3 {{ $msg->sender_id===auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                    @if($msg->sender_id !== auth()->id())
                        <img src="{{ $msg->sender->photo_url }}" alt="" style="width:32px;height:32px;border-radius:50%;object-fit:cover;margin-right:.6rem;align-self:flex-end;">
                    @endif
                    <div>
                        <div class="message-bubble {{ $msg->sender_id===auth()->id() ? 'message-sent' : 'message-received' }}">{{ $msg->body }}</div>
                        <div class="text-muted mt-1" style="font-size:.72rem;{{ $msg->sender_id===auth()->id() ? 'text-align:right' : '' }}">
                            {{ $msg->created_at->format('d/m H:i') }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4"><i class="bi bi-chat-dots fs-1 d-block mb-2 opacity-50"></i>Commencez la conversation !</div>
                @endforelse
            </div>
        </div>
        <div class="card" style="border-radius:0 0 14px 14px;border-top:1px solid #e0e0e0;">
            <div class="card-body p-3">
                <form action="{{ route('messages.send', $contact) }}" method="POST">
                    @csrf
                    @error('body')<div class="alert alert-danger py-2 small mb-2">{{ $message }}</div>@enderror
                    <div class="d-flex gap-2">
                        <textarea name="body" class="form-control" rows="2" placeholder="Écrire un message..." required style="border-radius:10px;resize:none;">{{ old('body') }}</textarea>
                        <button type="submit" class="btn btn-primary px-4 align-self-end" style="border-radius:10px;"><i class="bi bi-send-fill"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const c = document.getElementById('msg-container');
    if (c) c.scrollTop = c.scrollHeight;
</script>
@endpush
