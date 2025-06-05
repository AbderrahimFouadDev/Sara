<!-- resources/views/customer/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>

    <!-- Include Chart.js before any scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Add ApexCharts for more advanced charts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @vite([
        'resources/css/cus_dash.css',
        'resources/js/cus_dash.js',
        'resources/css/ajouter_client.css',
        'resources/js/ajouter_client.js',
        'resources/css/liste_clients.css',
        'resources/js/liste_clients.js',
    ])

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Enhanced Header -->
        <header class="dashboard-header">
            <div class="header-left">
                <div class="logo-section">
                    <img src="{{ asset('images/logo_all_in_one.png') }}" alt="Logo" class="site-logo">
                    <div class="welcome-text">
                        <span>Bienvenue</span>
                        <h2>{{ Auth::user()->name }}</h2>
                    </div>
                </div>
            </div>
            
            <div class="header-center">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" id="nav-search" placeholder="Rechercher..." autocomplete="off">
                    <div id="search-results" class="search-results"></div>
                </div>
            </div>

            <div class="header-right">
                <div class="header-actions">
                    <div class="quick-action-buttons">
                        <div class="action-button" onclick="loadComponent('clients/ajouter_client')" title="Ajouter Client">
                            <div class="icon-container">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                        <div class="action-button" onclick="loadComponent('fournisseurs/ajouter_fournisseur')" title="Ajouter Fournisseur">
                            <div class="icon-container">
                                <i class="fas fa-truck"></i>
                            </div>
                        </div>
                        <div class="action-button" onclick="loadComponent('devis/creer_devis')" title="Ajouter Devis">
                            <div class="icon-container">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                        </div>
                        <div class="action-button" onclick="loadComponent('factures/creer_facture')" title="Ajouter Facture">
                            <div class="icon-container">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                        </div>
                        <div class="action-button" onclick="loadComponent('rh/ajouter_salarie')" title="Ajouter Salarié">
                            <div class="icon-container">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                        <div class="action-button" onclick="loadComponent('bon_livraison/creer_bon_livraison')" title="Créer Bon Livraison">
                            <div class="icon-container">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                        </div>
                        <div class="action-button" onclick="loadComponent('stock/form_produit')" title="Ajouter Produit">
                            <div class="icon-container">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                        <div class="action-button" onclick="loadComponent('rh/nouveau_conge')" title="Nouveau Congé">
                            <div class="icon-container">
                                <i class="fas fa-umbrella-beach"></i>
                            </div>
                        </div>
                    </div>
                    <div class="separator"></div>
                    <div class="user-profile">
                        @php
                            $customer = App\Models\Customer::where('email', Auth::user()->email)->first();
                        @endphp
                        @if($customer && $customer->photo)
                            <img src="{{ asset('storage/' . $customer->photo) }}" alt="Profile" class="user-avatar">
                        @else
                            <img src="{{ asset('images/profile.jpg') }}" alt="Profile" class="user-avatar">
                        @endif
                        <div class="user-info">
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                            <div class="user-dropdown">
                                <a href="#" onclick="loadComponent('profil_customer'); return false;" class="dropdown-item">
                                    <i class="fas fa-user"></i> Mon profil
                                </a>
                               
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="logout-btn">
                                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="dashboard-sidebar">
                <nav class="sidebar-nav">
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-view="dashboard">
                                <i class="fas fa-home"></i>
                                <span>Tableau de bord</span>
                            </a>
                        </li>

                        <!-- Clients Section -->
                        <li class="nav-item has-submenu">
                            <a href="#" class="nav-link">
                                <i class="fas fa-users"></i>
                                <span>Clients</span>
                                <i class="fas fa-chevron-down arrow"></i>
                            </a>
                            <ul class="submenu">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="clients/ajouter_client">
                                        <i class="fas fa-user-plus"></i>
                                        <span>Ajouter client</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="clients/liste_clients">
                                        <i class="fas fa-list"></i>
                                        <span>Liste des Clients</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                          <!-- Fournisseurs Section -->
                        <li class="nav-item has-submenu">
                            <a href="#" class="nav-link submenu-toggle">
                                <i class="fas fa-truck"></i>
                                <span>Fournisseurs</span>
                                <i class="fas fa-chevron-down arrow"></i>
                            </a>
                            <ul class="submenu">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="fournisseurs/ajouter_fournisseur">
                                        <i class="fas fa-plus-circle"></i>
                                        <span>Nouveau Fournisseur</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="fournisseurs/liste_fournisseurs">
                                        <i class="fas fa-list"></i>
                                        <span>Liste des Fournisseurs</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Devis Section -->
                        <li class="nav-item has-submenu">
                            <a href="#" class="nav-link">
                                <i class="fas fa-file-invoice"></i>
                                <span>Devis</span>
                                <i class="fas fa-chevron-down arrow"></i>
                            </a>
                            <ul class="submenu">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="devis/creer_devis">
                                        <i class="fas fa-plus-circle"></i>
                                        <span>Créer Devis</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="devis/liste_devis">
                                        <i class="fas fa-list"></i>
                                        <span>Liste des Devis</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Factures Section -->
                        <li class="nav-item has-submenu">
                            <a href="#" class="nav-link">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <span>Factures</span>
                                <i class="fas fa-chevron-down arrow"></i>
                            </a>
                            <ul class="submenu">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="factures/creer_facture">
                                        <i class="fas fa-plus-circle"></i>
                                        <span>Créer Facture</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="factures/liste_factures">
                                        <i class="fas fa-list"></i>
                                        <span>Liste des Factures</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Bon de Livraison Section -->
                        <li class="nav-item has-submenu">
                            <a href="#" class="nav-link">
                                <i class="fas fa-truck-loading"></i>
                                <span>Bons de Livraison</span>
                                <i class="fas fa-chevron-down arrow"></i>
                            </a>
                            <ul class="submenu">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="bon_livraison/creer_bon_livraison">
                                        <i class="fas fa-plus-circle"></i>
                                        <span>Nouveau Bon</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="bon_livraison/liste_bon_livraison">
                                        <i class="fas fa-list"></i>
                                        <span>Liste des Bons</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Stock Section -->
                        <li class="nav-item has-submenu">
                            <a href="#" class="nav-link">
                                <i class="fas fa-boxes"></i>
                                <span>Stock</span>
                                <i class="fas fa-chevron-down arrow"></i>
                            </a>
                            <ul class="submenu">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="stock/form_produit">
                                        <i class="fas fa-plus-circle"></i>
                                        <span>Ajouter Article/Service</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="stock/liste_produits">
                                        <i class="fas fa-list"></i>
                                        <span>Liste Articles/Services</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="stock/ajouter_categorie">
                                        <i class="fas fa-folder-plus"></i>
                                        <span>Ajouter Catégorie</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="stock/liste_categories">
                                        <i class="fas fa-folder"></i>
                                        <span>Liste des Catégories</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- RH Section -->
                        <li class="nav-item has-submenu">
                            <a href="#" class="nav-link">
                                <i class="fas fa-user-tie"></i>
                                <span>RH & Paie</span>
                                <i class="fas fa-chevron-down arrow"></i>
                            </a>
                            <ul class="submenu">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="rh/salaries">
                                        <i class="fas fa-users"></i>
                                        <span>Salariés</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="rh/ajouter_salarie">
                                        <i class="fas fa-user-plus"></i>
                                        <span>Ajouter Salarié</span>
                                    </a>
                                </li>
                              
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-view="rh/conges">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>Congés</span>
                                    </a>
                                </li>
                                
                            </ul>
                        </li>
                    </ul>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="dashboard-main">
                <!-- Default dashboard content -->
                <div id="default-dashboard">
                    <!-- Clients & Fournisseurs Overview -->
                    <div class="clients-fournisseurs-overview">
                        <div class="overview-header">
                            <h2>Vue d'ensemble Clients & Fournisseurs</h2>
                            <div class="overview-period">
                                <span>Période:</span>
                                <select class="period-select">
                                    <option>Cette année</option>
                                    <option>Ce mois</option>
                                    <option>Cette semaine</option>
                                </select>
                            </div>
                        </div>
                        <div class="overview-charts">
                            <div class="chart-container comparison-chart">
                                <canvas id="clientsFournisseursChart"></canvas>
                            </div>
                            <div class="metrics-grid">
                                <div class="metric-card">
                                    <div class="metric-icon clients">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="metric-info">
                                        <h3>Total Clients</h3>
                                        <p class="metric-value">{{ $totalClients ?? 0 }}</p>
                                        <span class="metric-trend positive">
                                            <i class="fas fa-arrow-up"></i> +12% ce mois
                                        </span>
                                    </div>
                                </div>
                                <div class="metric-card">
                                    <div class="metric-icon suppliers">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="metric-info">
                                        <h3>Total Fournisseurs</h3>
                                        <p class="metric-value">{{ $totalFournisseurs ?? 0 }}</p>
                                        <span class="metric-trend positive">
                                            <i class="fas fa-arrow-up"></i> +5% ce mois
                                        </span>
                                    </div>
                                </div>
                                <div class="metric-card">
                                    <div class="metric-icon active">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="metric-info">
                                        <h3>Clients Actifs</h3>
                                        <p class="metric-value">{{ $activeClients ?? 0 }}</p>
                                        <span class="metric-trend neutral">
                                            <i class="fas fa-minus"></i> Stable
                                        </span>
                                    </div>
                                </div>
                                <div class="metric-card">
                                    <div class="metric-icon active">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="metric-info">
                                        <h3>Fournisseurs Actifs</h3>
                                        <p class="metric-value">{{ $activeFournisseurs ?? 0 }}</p>
                                        <span class="metric-trend neutral">
                                            <i class="fas fa-minus"></i> Stable
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Stats Overview -->
                    <div class="stats-overview">
                        <div class="stat-card primary">
                            <div class="stat-icon">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div class="stat-details">
                                <h3>Chiffre d'affaires</h3>
                                <div class="stat-numbers">
                                    <span class="stat-value">125,000</span>
                                    <span class="stat-currency">MAD</span>
                                </div>
                                <div class="stat-trend positive">
                                    <i class="fas fa-arrow-up"></i>
                                    <span>12% vs mois dernier</span>
                                </div>
                            </div>
                            <div class="stat-chart" id="revenueChart"></div>
                        </div>

                        <div class="stat-card success">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-details">
                                <h3>Nouveaux Clients</h3>
                                <div class="stat-numbers">
                                    <span class="stat-value">{{ $totalClients ?? 0 }}</span>
                                    <span class="stat-label">ce mois</span>
                                </div>
                                <div class="stat-trend positive">
                                    <i class="fas fa-arrow-up"></i>
                                    <span>8% vs mois dernier</span>
                                </div>
                            </div>
                            <div class="stat-chart" id="clientsChart"></div>
                        </div>

                        <div class="stat-card warning">
                            <div class="stat-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="stat-details">
                                <h3>Commandes en cours</h3>
                                <div class="stat-numbers">
                                    <span class="stat-value">24</span>
                                </div>
                                <div class="stat-trend neutral">
                                    <i class="fas fa-minus"></i>
                                    <span>Stable</span>
                                </div>
                            </div>
                            <div class="stat-chart" id="ordersChart"></div>
                        </div>

                        <div class="stat-card danger">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-details">
                                <h3>Factures impayées</h3>
                                <div class="stat-numbers">
                                    <span class="stat-value">45,000</span>
                                    <span class="stat-currency">MAD</span>
                                </div>
                                <div class="stat-trend negative">
                                    <i class="fas fa-arrow-up"></i>
                                    <span>15% à traiter</span>
                                </div>
                            </div>
                            <div class="stat-chart" id="unpaidChart"></div>
                        </div>
                    </div>

                    <!-- New Charts Section -->
                    <div class="dashboard-charts">
                        <div class="chart-row">
                            <div class="chart-container large">
                                <div class="chart-header">
                                    <h3>Évolution du chiffre d'affaires</h3>
                                    <div class="chart-actions">
                                        <button class="chart-period-btn active">Jour</button>
                                        <button class="chart-period-btn">Semaine</button>
                                        <button class="chart-period-btn">Mois</button>
                                    </div>
                                </div>
                                <div class="chart-body">
                                    <canvas id="revenueLineChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="chart-row">
                            <div class="chart-container medium">
                                <div class="chart-header">
                                    <h3>Répartition des ventes</h3>
                                </div>
                                <div class="chart-body">
                                    <canvas id="salesDistributionChart"></canvas>
                                </div>
                            </div>
                            <div class="chart-container medium">
                                <div class="chart-header">
                                    <h3>Top Clients</h3>
                                </div>
                                <div class="chart-body">
                                    <canvas id="topClientsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions Table -->
                    <div class="recent-transactions">
                        <div class="section-header">
                            <h2>Transactions Récentes</h2>
                            <button class="refresh-btn">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="transactions-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#INV-001</td>
                                        <td>
                                            <div class="client-info">
                                                <img src="{{ asset('images/logo_all_in_one.png') }}" alt="Client">
                                                <span>Client XYZ</span>
                                            </div>
                                        </td>
                                        <td>2024-03-20</td>
                                        <td>15,000 MAD</td>
                                        <td><span class="status-badge success">Payée</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                                <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                                <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Add more rows as needed -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Module Cards -->
                    <div class="module-cards">
                        <div class="module-card">
                            <div class="module-icon">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div class="module-content">
                                <h3>Facturation & Devis</h3>
                                <p>Gérez vos factures, devis et documents commerciaux</p>
                                <ul class="module-features">
                                    <li><i class="fas fa-check"></i> Création de factures et devis</li>
                                    <li><i class="fas fa-check"></i> Export PDF et envoi par email</li>
                                    <li><i class="fas fa-check"></i> Suivi des paiements</li>
                                </ul>
                                <a href="#" class="module-link">Accéder <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="module-card">
                            <div class="module-icon">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <div class="module-content">
                                <h3>Comptabilité</h3>
                                <p>Suivez votre comptabilité et vos déclarations fiscales</p>
                                <ul class="module-features">
                                    <li><i class="fas fa-check"></i> Plan comptable marocain</li>
                                    <li><i class="fas fa-check"></i> Déclaration TVA</li>
                                    <li><i class="fas fa-check"></i> Rapports financiers</li>
                                </ul>
                                <a href="#" class="module-link">Accéder <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="module-card">
                            <div class="module-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="module-content">
                                <h3>CRM</h3>
                                <p>Gérez vos clients et fournisseurs efficacement</p>
                                <ul class="module-features">
                                    <li><i class="fas fa-check"></i> Base de données clients</li>
                                    <li><i class="fas fa-check"></i> Suivi des opportunités</li>
                                    <li><i class="fas fa-check"></i> Communication intégrée</li>
                                </ul>
                                <a href="#" class="module-link">Accéder <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="module-card">
                            <div class="module-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="module-content">
                                <h3>RH & Paie</h3>
                                <p>Gérez vos ressources humaines et la paie</p>
                                <ul class="module-features">
                                    <li><i class="fas fa-check"></i> Gestion des salariés</li>
                                    <li><i class="fas fa-check"></i> Bulletins de paie</li>
                                    <li><i class="fas fa-check"></i> Gestion des congés</li>
                                </ul>
                                <a href="#" class="module-link">Accéder <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="module-card">
                            <div class="module-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="module-content">
                                <h3>Stocks</h3>
                                <p>Suivez vos stocks et produits en temps réel</p>
                                <ul class="module-features">
                                    <li><i class="fas fa-check"></i> Gestion des stocks</li>
                                    <li><i class="fas fa-check"></i> Alertes de niveau</li>
                                    <li><i class="fas fa-check"></i> Inventaire automatique</li>
                                </ul>
                                <a href="#" class="module-link">Accéder <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic content container -->
                <div id="dynamic-content" style="display: none;"></div>
            </main>
        </div>
    </div>

    <style>
        /* Quick Action Buttons */
        .quick-action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .action-button {
            position: relative;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .action-button .icon-container {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        
        .action-button .icon-container i {
            font-size: 16px;
            color: #4b5563;
            transition: all 0.2s ease;
        }
        
        .action-button:hover .icon-container {
            background-color: #3b82f6;
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.25);
        }
        
        .action-button:hover .icon-container i {
            color: white;
        }
        
        .action-button::after {
            content: attr(title);
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #1f2937;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            z-index: 10;
        }
        
        .action-button:hover::after {
            opacity: 1;
            visibility: visible;
        }
        
        /* Enhanced Header Styles */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-center {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            flex: 1;
            margin: 0 2rem;
        }

        .search-bar {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .search-bar input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .search-bar i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .quick-filters {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: #fff;
            color: #64748b;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-btn.active {
            background: #3b82f6;
            color: #fff;
            border-color: #3b82f6;
        }

        .notification-center {
            display: flex;
            gap: 1rem;
            margin-right: 2rem;
        }

        .notification-btn, .messages-btn {
            position: relative;
            padding: 0.5rem;
            border: none;
            background: none;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: #fff;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
        }

        /* Enhanced Dashboard Styles */
        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .stat-card.primary { border-left: 4px solid #3b82f6; }
        .stat-card.success { border-left: 4px solid #10b981; }
        .stat-card.warning { border-left: 4px solid #f59e0b; }
        .stat-card.danger { border-left: 4px solid #ef4444; }

        .stat-chart {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100px;
            height: 40px;
            opacity: 0.2;
        }

        .dashboard-charts {
            margin: 2rem 0;
        }

        .chart-row {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .chart-container {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .chart-container.large { flex: 2; }
        .chart-container.medium { flex: 1; }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .chart-actions {
            display: flex;
            gap: 0.5rem;
        }

        .chart-period-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: #fff;
            font-size: 0.875rem;
            cursor: pointer;
        }

        .chart-period-btn.active {
            background: #3b82f6;
            color: #fff;
            border-color: #3b82f6;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
        }

        .transactions-table th,
        .transactions-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .client-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .client-info img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
        }

        .status-badge.success { background: #dcfce7; color: #166534; }
        .status-badge.pending { background: #fef3c7; color: #92400e; }
        .status-badge.failed { background: #fee2e2; color: #991b1b; }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            padding: 0.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-btn.view-btn { color: #3b82f6; }
        .action-btn.edit-btn { color: #f59e0b; }
        .action-btn.delete-btn { color: #ef4444; }

        .action-btn:hover {
            background: rgba(0,0,0,0.05);
        }

        @media (max-width: 1024px) {
            .chart-row {
                flex-direction: column;
            }
            
            .chart-container.medium {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .header-center {
                display: none;
            }
            
            .stats-overview {
                grid-template-columns: 1fr;
            }
        }

        /* Clients & Fournisseurs Overview Styles */
        .clients-fournisseurs-overview {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .overview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .overview-header h2 {
            font-size: 1.25rem;
            color: #1f2937;
            font-weight: 600;
        }

        .overview-period {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .overview-period span {
            color: #6b7280;
        }

        .period-select {
            padding: 0.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: #fff;
            color: #374151;
            font-size: 0.875rem;
        }

        .overview-charts {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }

        .comparison-chart {
            background: #fff;
            border-radius: 8px;
            padding: 1rem;
            height: 300px;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .metric-card {
            background: #fff;
            border-radius: 8px;
            padding: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            border: 1px solid #e5e7eb;
        }

        .metric-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .metric-icon.clients {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .metric-icon.suppliers {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .metric-icon.active {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .metric-info h3 {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .metric-value {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .metric-trend {
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .metric-trend.positive {
            color: #10b981;
        }

        .metric-trend.neutral {
            color: #6b7280;
        }

        .metric-trend.negative {
            color: #ef4444;
        }

        @media (max-width: 1024px) {
            .overview-charts {
                grid-template-columns: 1fr;
            }
            
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .metrics-grid {
                grid-template-columns: 1fr;
            }
            
            .overview-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }

        /* Enhanced Responsive Styles */
        /* Tablet Landscape */
        @media (max-width: 1024px) {
            .dashboard-container {
                grid-template-columns: 1fr;
            }

            .dashboard-sidebar {
                display: none;
            }

            .dashboard-main {
                padding: 1rem;
            }

            .overview-charts {
                grid-template-columns: 1fr;
            }

            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stats-overview {
                grid-template-columns: repeat(2, 1fr);
            }

            .chart-row {
                flex-direction: column;
            }

            .chart-container.medium,
            .chart-container.large {
                width: 100%;
            }
        }

        /* Tablet Portrait */
        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                padding: 1rem;
                gap: 1rem;
            }

            .header-left,
            .header-center,
            .header-right {
                width: 100%;
            }

            .header-center {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .search-bar {
                max-width: 100%;
            }

            .quick-filters {
                overflow-x: auto;
                padding-bottom: 0.5rem;
            }

            .filter-btn {
                white-space: nowrap;
            }

            .header-right {
                flex-direction: column;
                gap: 0.75rem;
            }

            .notification-center {
                justify-content: center;
                margin-right: 0;
            }

            .quick-actions {
                width: 100%;
                justify-content: center;
            }

            .stats-overview {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stat-card {
                padding: 1rem;
            }

            .recent-transactions {
                overflow-x: auto;
            }

            .transactions-table {
                min-width: 700px;
            }

            .module-cards {
                grid-template-columns: 1fr;
            }
        }

        /* Mobile */
        @media (max-width: 640px) {
            .dashboard-header {
                padding: 0.75rem;
            }

            .header-left h1 {
                font-size: 1.25rem;
            }

            .metrics-grid {
                grid-template-columns: 1fr;
            }

            .metric-card {
                padding: 0.75rem;
            }

            .overview-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .overview-period {
                width: 100%;
            }

            .period-select {
                width: 100%;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }

            .action-btn {
                width: 100%;
            }

            .chart-container {
                padding: 0.75rem;
            }

            .chart-actions {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .chart-period-btn {
                font-size: 0.75rem;
                padding: 0.375rem 0.75rem;
            }
        }

        /* Small Mobile */
        @media (max-width: 380px) {
            .stat-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .stat-icon {
                margin-bottom: 0.5rem;
            }

            .stat-details {
                align-items: center;
            }

            .metric-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .metric-icon {
                margin-bottom: 0.5rem;
            }

            .metric-info {
                align-items: center;
            }

            .client-info {
                flex-direction: column;
                align-items: center;
                gap: 0.5rem;
            }

            .status-badge {
                width: 100%;
                text-align: center;
            }
        }

        /* Ensure charts are responsive */
        .chart-body {
            position: relative;
            height: 300px;
        }

        @media (max-width: 768px) {
            .chart-body {
                height: 250px;
            }
        }

        @media (max-width: 640px) {
            .chart-body {
                height: 200px;
            }
        }

        /* Improve table responsiveness */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 -1rem;
            padding: 0 1rem;
        }

        .transactions-table th,
        .transactions-table td {
            white-space: nowrap;
            padding: 0.75rem;
        }

        @media (max-width: 768px) {
            .transactions-table th,
            .transactions-table td {
                padding: 0.5rem;
                font-size: 0.875rem;
            }
        }

        /* Add a mobile menu trigger */
        .mobile-menu-trigger {
            display: none;
            padding: 0.5rem;
            font-size: 1.5rem;
            color: #374151;
            background: none;
            border: none;
            cursor: pointer;
        }

        @media (max-width: 1024px) {
            .mobile-menu-trigger {
                display: block;
            }

            .dashboard-sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
                width: 280px;
                z-index: 1000;
                transition: left 0.3s ease;
                background: #fff;
            }

            .dashboard-sidebar.active {
                left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }

        /* Add these styles for the user dropdown */
        .user-profile {
            position: relative;
            cursor: pointer;
        }

        .user-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            z-index: 1000;
            margin-top: 0.5rem;
        }

        .user-profile:hover .user-dropdown {
            display: block;
        }

        .user-dropdown a,
        .user-dropdown button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            color: #4b5563;
            text-decoration: none;
            transition: background-color 0.2s;
            width: 100%;
            text-align: left;
            border: none;
            background: none;
            font-size: 0.875rem;
        }

        .user-dropdown a:hover,
        .user-dropdown button:hover {
            background-color: #f3f4f6;
            color: #1f2937;
        }

        .logout-btn {
            border-top: 1px solid #e5e7eb;
            color: #ef4444 !important;
        }

        .logout-btn:hover {
            background-color: #fee2e2 !important;
        }

        /* Add this to ensure the dropdown is above other elements */
        .header-right {
            position: relative;
            z-index: 1000;
        }
        .site-logo {
            width: 200px;
            height: auto;
            object-fit: contain;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }
    </style>

    <!-- Add overlay for mobile sidebar -->
    <div class="sidebar-overlay"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there's a component to load from session
            @if(session('component'))
                loadComponent('{{ session('component') }}');
            @endif
            
            // Add this function to handle component loading
            window.loadComponent = function(component) {
                console.log('Loading component:', component); // Debug log
                const dynamicContent = document.getElementById('dynamic-content');
                const defaultDashboard = document.getElementById('default-dashboard');
                
                // Show loading state
                dynamicContent.innerHTML = '<div class="loading">Chargement...</div>';
                dynamicContent.style.display = 'block';
                defaultDashboard.style.display = 'none';

                // Fetch the component
                fetch(`/dashboard/component/${component}`)
                    .then(response => {
                        console.log('Response status:', response.status); // Debug log
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(html => {
                        console.log('Component loaded successfully'); // Debug log
                        dynamicContent.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error loading component:', error); // Debug log
                        dynamicContent.innerHTML = `<div class="error">Une erreur s'est produite lors du chargement.</div>`;
                    });
            };

            // Clients & Fournisseurs Comparison Chart
            const comparisonCtx = document.getElementById('clientsFournisseursChart');
            new Chart(comparisonCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                    datasets: [
                        {
                            label: 'Clients',
                            data: [65, 75, 85, 95, 110, {{ $totalClients ?? 120 }}],
                            backgroundColor: '#3b82f6',
                            borderRadius: 4,
                        },
                        {
                            label: 'Fournisseurs',
                            data: [25, 30, 35, 40, 45, {{ $totalFournisseurs ?? 50 }}],
                            backgroundColor: '#f59e0b',
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                boxWidth: 12,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        title: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Revenue Line Chart
            const revenueCtx = document.getElementById('revenueLineChart');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Chiffre d\'affaires',
                        data: [65000, 75000, 95000, 85000, 115000, 125000],
                        borderColor: '#3b82f6',
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(59, 130, 246, 0.1)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Sales Distribution Chart
            const salesCtx = document.getElementById('salesDistributionChart');
            new Chart(salesCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Produits', 'Services', 'Abonnements'],
                    datasets: [{
                        data: [45, 30, 25],
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Top Clients Chart
            const clientsCtx = document.getElementById('topClientsChart');
            new Chart(clientsCtx, {
                type: 'bar',
                data: {
                    labels: ['Client A', 'Client B', 'Client C', 'Client D', 'Client E'],
                    datasets: [{
                        label: 'Chiffre d\'affaires',
                        data: [45000, 35000, 30000, 25000, 20000],
                        backgroundColor: '#3b82f6'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Add mobile menu functionality
            const menuTrigger = document.querySelector('.mobile-menu-trigger');
            const sidebar = document.querySelector('.dashboard-sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            if (menuTrigger && sidebar && overlay) {
                menuTrigger.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });

                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }
        });
    </script>
</body>
</html>
