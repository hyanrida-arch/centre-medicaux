<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Centre Médicaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1a6b8a, #0f4a63); min-height: 100vh; display: flex; align-items: center; }
        .auth-card { border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,.3); border: none; }
        .auth-logo { width: 70px; height: 70px; background: #1a6b8a; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .form-control { border-radius: 10px; padding: .75rem 1rem; border: 1.5px solid #e0e0e0; }
        .form-control:focus { border-color: #1a6b8a; box-shadow: 0 0 0 3px rgba(26,107,138,.1); }
        .btn-primary { background: #1a6b8a; border: none; border-radius: 10px; padding: .8rem; font-weight: 600; }
        .btn-primary:hover { background: #0f4a63; }
        .input-group-text { border-radius: 10px 0 0 10px; background: #f8f9fa; border: 1.5px solid #e0e0e0; border-right: none; }
        .form-control.has-icon { border-left: none; border-radius: 0 10px 10px 0; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card auth-card p-4">
                <div class="card-body">
                    <div class="auth-logo"><i class="bi bi-hospital-fill text-white fs-2"></i></div>
                    <h4 class="text-center fw-bold mb-1">Centre Médicaux</h4>
                    <p class="text-center text-muted mb-4">Connectez-vous à votre compte</p>
                    @if($errors->any())
                        <div class="alert alert-danger rounded-3 py-2">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-500">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control has-icon" value="{{ old('email') }}" placeholder="votre@email.com" required autofocus>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-500">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control has-icon" placeholder="••••••••" required>
                            </div>
                        </div>
                        <div class="mb-4 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label text-muted" for="remember">Se souvenir de moi</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                        </button>
                        <p class="text-center text-muted mb-0">
                            Pas encore de compte ?
                            <a href="{{ route('register') }}" class="text-decoration-none fw-600" style="color:#1a6b8a">S'inscrire</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
