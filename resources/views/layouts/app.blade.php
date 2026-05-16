<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Centre Médicaux')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #1a6b8a; --primary-dark: #0f4a63; --primary-light: #e8f4f8; }
        * { font-family: 'Inter', sans-serif; }
        body { background: #f0f4f8; min-height: 100vh; }
        .navbar { background: var(--primary) !important; box-shadow: 0 2px 12px rgba(0,0,0,.15); }
        .navbar .nav-link { color: rgba(255,255,255,.85) !important; font-weight: 500; transition: color .2s; }
        .navbar .nav-link:hover, .navbar .nav-link.active { color: #fff !important; }
        .navbar-brand span { color: #2ecc71; }
        .card { border: none; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
        .card-header { border-radius: 14px 14px 0 0 !important; background: var(--primary); color: #fff; font-weight: 600; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); color: #fff; }
        .stat-card { border-radius: 14px; padding: 1.4rem 1.6rem; color: #fff; }
        .stat-card .stat-icon { font-size: 2.2rem; opacity: .85; }
        .stat-card .stat-number { font-size: 2rem; font-weight: 700; }
        .stat-card .stat-label { font-size: .85rem; opacity: .9; }
        .doctor-card { transition: transform .2s, box-shadow .2s; }
        .doctor-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
        .doctor-avatar { width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 3px solid var(--primary-light); }
        .doctor-avatar-lg { width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 4px solid var(--primary); }
        .badge-pending   { background: #fff3cd; color: #856404; }
        .badge-accepted  { background: #cff4fc; color: #055160; }
        .badge-completed { background: #d1e7dd; color: #0a3622; }
        .badge-cancelled { background: #f8d7da; color: #58151c; }
        .stars { color: #ffc107; }
        .alert { border-radius: 10px; border: none; }
        .message-bubble { border-radius: 18px; padding: .7rem 1.1rem; max-width: 75%; }
        .message-sent { background: var(--primary); color: #fff; margin-left: auto; }
        .message-received { background: #fff; border: 1px solid #e0e0e0; }
        .conversation-item { transition: background .15s; border-radius: 10px; }
        .conversation-item:hover { background: var(--primary-light); }
        .page-header { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff; border-radius: 14px; padding: 2rem; margin-bottom: 1.5rem; }
        .form-control, .form-select { border-radius: 10px; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,107,138,.1); }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold fs-5" href="{{ auth()->check() && auth()->user()->isDoctor() ? route('doctor.dashboard') : route('doctors.index') }}">
            <i class="bi bi-hospital me-2"></i>Centre <span>Médicaux</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            @auth
            <ul class="navbar-nav me-auto">
                @if(auth()->user()->isDoctor())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}" href="{{ route('doctor.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.appointments*') ? 'active' : '' }}" href="{{ route('doctor.appointments') }}">
                            <i class="bi bi-calendar-check me-1"></i>Rendez-vous
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctors.index') ? 'active' : '' }}" href="{{ route('doctors.index') }}">
                            <i class="bi bi-search me-1"></i>Médecins
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('patient.appointments*') ? 'active' : '' }}" href="{{ route('patient.appointments') }}">
                            <i class="bi bi-calendar3 me-1"></i>Mes rendez-vous
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('messages*') ? 'active' : '' }}" href="{{ route('messages.index') }}">
                        <i class="bi bi-chat-dots me-1"></i>Messages
                        @if(auth()->user()->unreadMessages()->count() > 0)
                            <span class="badge bg-danger rounded-pill">{{ auth()->user()->unreadMessages()->count() }}</span>
                        @endif
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()->photo_url }}" alt="avatar" style="width:32px;height:32px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,.5)">
                        <span>{{ auth()->user()->full_name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-gear me-2"></i>Mon profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
            @endauth
            @guest
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">S'inscrire</a></li>
            </ul>
            @endguest
        </div>
    </div>
</nav>

<main class="container-fluid px-4 py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
