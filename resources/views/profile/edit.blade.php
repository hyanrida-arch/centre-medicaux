@extends('layouts.app')
@section('title', 'Mon profil')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="page-header mb-4">
            <h3 class="fw-bold mb-1"><i class="bi bi-person-gear me-2"></i>Mon profil</h3>
            <p class="mb-0 opacity-75">Gérez vos informations personnelles</p>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <img src="{{ $user->photo_url }}" alt="{{ $user->full_name }}" style="width:100px;height:100px;object-fit:cover;border-radius:50%;border:4px solid #e8f4f8;">
                    <div class="fw-bold mt-2">{{ $user->full_name }}</div>
                    <div class="text-muted small">{{ $user->email }}</div>
                </div>
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Prénom</label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $user->first_name) }}" required>
                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Nom</label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $user->last_name) }}" required>
                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Téléphone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Photo de profil</label>
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/jpg,image/jpeg,image/png">
                            <div class="form-text">JPG/PNG, max 2MB</div>
                            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @if($user->isDoctor() && $user->doctor)
                        <div class="col-12"><hr><p class="fw-600 text-primary mb-1"><i class="bi bi-stethoscope me-2"></i>Informations médicales</p></div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Spécialisation</label>
                            <select name="specialization" class="form-select">
                                @foreach(['Médecine générale','Cardiologie','Dermatologie','Pédiatrie','Gynécologie','Orthopédie','Neurologie','Ophtalmologie','ORL','Psychiatrie','Radiologie','Urologie','Endocrinologie','Gastro-entérologie','Pneumologie'] as $spec)
                                    <option value="{{ $spec }}" {{ old('specialization', $user->doctor->specialization)===$spec ? 'selected' : '' }}>{{ $spec }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Frais (MAD)</label>
                            <input type="number" name="consultation_fee" class="form-control" value="{{ old('consultation_fee', $user->doctor->consultation_fee) }}" min="0" step="50">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-500">Biographie</label>
                            <textarea name="biography" class="form-control" rows="4">{{ old('biography', $user->doctor->biography) }}</textarea>
                        </div>
                        @endif
                        <div class="col-12"><hr><p class="fw-600 mb-1">Changer le mot de passe</p></div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Nouveau mot de passe</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Confirmer</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-3">
                        <button type="submit" class="btn btn-primary px-5"><i class="bi bi-save me-2"></i>Enregistrer</button>
                        <a href="{{ auth()->user()->isDoctor() ? route('doctor.dashboard') : route('doctors.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
