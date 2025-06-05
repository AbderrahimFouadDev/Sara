<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Administration</title>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Styles -->
    @vite([
        'resources/css/admin_dashboard.css',
        'resources/js/admin_dashboard.js'
    ])
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Additional Styles -->
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo_all_in_one.png') }}" alt="Logo" class="logo">
                <h2>FacturePro</h2>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-home"></i> 
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <a href="#users">
                            <i class="fas fa-users"></i> 
                            <span>Utilisateurs</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.activities') ? 'active' : '' }}">
                        <a href="{{ route('admin.activities') }}">
                            <i class="fas fa-history"></i> 
                            <span>Activités</span>
                        </a>
                    </li>
                    <li>
                        <a href="#messages">
                            <i class="fas fa-envelope"></i> 
                            <span>Messages</span>
                            <span class="badge-count">{{ $unreadMessages ?? 0 }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="#home-editor">
                            <i class="fas fa-edit"></i> 
                            <span>Éditer Accueil</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <div class="admin-info">
                    <img src="{{ asset('images/avatar.png') }}" alt="Admin" class="admin-avatar">
                    <div class="admin-details">
                        <span class="admin-name">{{ Auth::user()->name }}</span>
                        <span class="admin-role">Administrateur</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Navigation -->
            <nav class="top-nav">
                <div class="nav-left">
                    <button class="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">@yield('title', 'Administration')</h1>
                </div>
                <div class="nav-right">
                    <div class="date-time">
                        <span class="current-date">{{ now()->format('d M Y') }}</span>
                        <span class="current-time" id="current-time"></span>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="page-content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Additional Scripts -->
    @stack('scripts')

    <style>
    .admin-layout {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        width: 280px;
        background: #2c3e50;
        color: white;
        display: flex;
        flex-direction: column;
        position: fixed;
        height: 100vh;
        left: 0;
        top: 0;
    }

    .sidebar-header {
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .logo {
        width: 40px;
        height: 40px;
        object-fit: contain;
    }

    .sidebar-header h2 {
        margin: 0;
        font-size: 1.25rem;
        color: white;
    }

    .sidebar-nav {
        flex: 1;
        padding: 20px 0;
    }

    .sidebar-nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-nav li {
        margin-bottom: 5px;
    }

    .sidebar-nav a {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        transition: all 0.3s;
        gap: 12px;
    }

    .sidebar-nav a:hover, .sidebar-nav li.active a {
        background: rgba(255,255,255,0.1);
        color: white;
    }

    .sidebar-nav .badge-count {
        background: #e74c3c;
        color: white;
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 0.75rem;
        margin-left: auto;
    }

    .sidebar-footer {
        padding: 20px;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .admin-info {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
    }

    .admin-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .admin-details {
        display: flex;
        flex-direction: column;
    }

    .admin-name {
        font-weight: 600;
        color: white;
    }

    .admin-role {
        font-size: 0.875rem;
        color: rgba(255,255,255,0.6);
    }

    .logout-btn {
        width: 100%;
        padding: 10px;
        background: rgba(231, 76, 60, 0.1);
        border: 1px solid rgba(231, 76, 60, 0.2);
        color: #e74c3c;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .logout-btn:hover {
        background: rgba(231, 76, 60, 0.2);
    }

    .main-content {
        flex: 1;
        margin-left: 280px;
        background: #f8fafc;
        min-height: 100vh;
    }

    .top-nav {
        background: white;
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .nav-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .menu-toggle {
        background: none;
        border: none;
        color: #4a5568;
        font-size: 1.25rem;
        cursor: pointer;
        padding: 5px;
        display: none;
    }

    .page-title {
        margin: 0;
        font-size: 1.5rem;
        color: #2d3748;
    }

    .nav-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .date-time {
        display: flex;
        gap: 15px;
        color: #4a5568;
    }

    .page-content {
        padding: 30px;
    }

    @media (max-width: 1024px) {
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
        }

        .menu-toggle {
            display: block;
        }
    }
    </style>

    <script>
    // Update time
    function updateTime() {
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            const now = new Date();
            timeElement.textContent = now.toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Update time
        updateTime();
        setInterval(updateTime, 60000);

        // Mobile menu toggle
        const menuToggle = document.querySelector('.menu-toggle');
        const sidebar = document.querySelector('.sidebar');
        
        if (menuToggle && sidebar) {
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }
    });
    </script>
</body>
</html> 