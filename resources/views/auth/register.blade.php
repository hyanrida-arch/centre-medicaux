<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — Centre Médicaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1a6b8a, #0f4a63); min-height: 100vh; padding: 2rem 0; }
        .auth-card { border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,.3); border: none; }
        .auth-logo { width: 70px; height: 70px; background: #1a6b8a; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .form-control, .form-select { border-radius: 10px; padding: .75rem 1rem; border: 1.5px solid #e0e0e0; }
        .form-control:focus, .form-select:focus { border-color: #1a6b8a; box-shadow: 0 0 0 3px rgba(26,107,138,.1); }
        .btn-primary { background: #1a6b8a; border: none; border-radius: 10px; padding: .8rem; font-weight: 600; }
        .btn-primary:hover { background: #0f4a63; }
        #doctor-fields { display: none; }
        .role-card { border: 2px solid #e0e0e0; border-radius: 12px; padding: 1rem; cursor: pointer; transition: all .2s; text-align: center; }
        .role-card:hover, .role-card.selected { border-color: #1a6b8a; background: #e8f4f8; }
        .role-card i { font-size: 1.8rem; color: #1a6b8a; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card auth-card p-4 mb-4">
                <div class="card-body">
                    <div class="auth-logo"><i class="bi bi-hospital-fill text-white fs-2"></i></div>
                    <h4 class="text-center fw-bold mb-1">Créer un compte</h4>
                    <p class="text-center text-muted mb-4">Rejoignez Centre Médicaux</p>
                    @if($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-600 mb-2">Je suis un(e)</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="role-card {{ old('role')==='patient' ? 'selected' : '' }}" onclick="selectRole('patient')">
                                        <i class="bi bi-person-heart d-block mb-1"></i>
                                        <strong>Patient</strong>
                                        <p class="text-muted small mb-0">Je cherche un médecin</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="role-card {{ old('role')==='doctor' ? 'selected' : '' }}" onclick="selectRole('doctor')">
                                        <i class="bi bi-person-vcard d-block mb-1"></i>
                                        <strong>Médecin</strong>
                                        <p class="text-muted small mb-0">Je pratique la médecine</p>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="role" id="role" value="{{ old('role','') }}">
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label">Prénom</label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Nom</label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Téléphone <span class="text-muted">(optionnel)</span></label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Confirmer</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                        <div id="doctor-fields">
                            <hr class="my-3">
                            <p class="fw-600 text-primary mb-3"><i class="bi bi-stethoscope me-2"></i>Informations médicales</p>
                            <div class="mb-3">
                                <label class="form-label">Spécialisation</label>
                                <select name="specialization" class="form-select @error('specialization') is-invalid @enderror">
                                    <option value="">Sélectionner...</option>
                                    @foreach(['Médecine générale','Cardiologie','Dermatologie','Pédiatrie','Gynécologie','Orthopédie','Neurologie','Ophtalmologie','ORL','Psychiatrie','Radiologie','Urologie','Endocrinologie','Gastro-entérologie','Pneumologie'] as $spec)
                                        <option value="{{ $spec }}" {{ old('specialization')===$spec ? 'selected' : '' }}>{{ $spec }}</option>
                                    @endforeach
                                </select>
                                @error('specialization')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Frais de consultation (MAD)</label>
                                <input type="number" name="consultation_fee" class="form-control" value="{{ old('consultation_fee', 200) }}" min="0" step="50">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Biographie <span class="text-muted">(optionnel)</span></label>
                                <textarea name="biography" class="form-control" rows="3" placeholder="Présentez-vous en quelques mots...">{{ old('biography') }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-3 mt-2">
                            <i class="bi bi-person-plus me-2"></i>Créer mon compte
                        </button>
                        <p class="text-center text-muted mb-0">
                            Déjà un compte ?
                            <a href="{{ route('login') }}" class="text-decoration-none fw-600" style="color:#1a6b8a">Se connecter</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function selectRole(role) {
        document.getElementById('role').value = role;
        document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
        event.currentTarget.classList.add('selected');
        document.getElementById('doctor-fields').style.display = role === 'doctor' ? 'block' : 'none';
    }
    window.addEventListener('load', () => {
        const role = document.getElementById('role').value;
        if (role === 'doctor') document.getElementById('doctor-fields').style.display = 'block';
    });
</script>
</body>
</html>
