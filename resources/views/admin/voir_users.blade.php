<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Détails Utilisateur - FacturePro</title>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #1a237e;
            --primary-light: #534bae;
            --primary-dark: #000051;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --text-primary: #2d3748;
            --text-secondary: #4a5568;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.05);
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 2rem;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: var(--bg-secondary);
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 500;
        }

        .user-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            padding: 2rem;
            margin-top: 1rem;
        }

        .info-group {
            margin-bottom: 1.5rem;
        }

        .info-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .info-value {
            font-size: 1rem;
            color: var(--text-primary);
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-badge.active {
            background-color: #c6f6d5;
            color: #2f855a;
        }

        .status-badge.inactive {
            background-color: #fed7d7;
            color: #9b2c2c;
        }

        .actions {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-secondary {
            background-color: #e2e8f0;
            color: var(--text-primary);
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ url('/admin/dashboard') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Retour au tableau de bord
            </a>
            <h1>Détails de l'utilisateur</h1>
        </div>

        <div class="section-header">
            <div class="header-left">
                <h2>Détails de l'utilisateur</h2>
            </div>
        </div>

        <div class="user-info card">
            <div class="info-group">
                <div class="info-label">Nom</div>
                <div class="info-value">{{ $customer->nom }}</div>
            </div>

            <div class="info-group">
                <div class="info-label">Prénom</div>
                <div class="info-value">{{ $customer->prenom }}</div>
            </div>

            <div class="info-group">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $customer->email }}</div>
            </div>

            <div class="info-group">
                <div class="info-label">Téléphone</div>
                <div class="info-value">{{ $customer->telephone }}</div>
            </div>

            <div class="info-group">
                <div class="info-label">Entreprise</div>
                <div class="info-value">{{ $customer->entreprise ?? '-' }}</div>
            </div>

            <div class="info-group">
                <div class="info-label">Adresse de l'entreprise</div>
                <div class="info-value">{{ $customer->adresse_entreprise ?? '-' }}</div>
            </div>

            <div class="info-group">
                <div class="info-label">Secteur d'activité</div>
                <div class="info-value">{{ $customer->secteur ?? '-' }}</div>
            </div>

            <div class="info-group">
                <div class="info-label">Statut</div>
                <div class="info-value">
                    <span class="status-badge {{ $customer->status ?? 'active' }}">
                        {{ ucfirst($customer->status ?? 'Actif') }}
                    </span>
                </div>
            </div>

            <div class="info-group">
                <div class="info-label">Date d'inscription</div>
                <div class="info-value">{{ $customer->created_at->format('d/m/Y H:i') }}</div>
            </div>

            <div class="info-group">
                <div class="info-label">Dernière modification</div>
                <div class="info-value">{{ $customer->updated_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <div class="actions">
            <button class="btn btn-secondary" onclick="showUsersSection()">
                <i class="fas fa-arrow-left"></i>
                Retour à la liste
            </button>
        </div>
    </div>

    <script>
        function showUsersSection() {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.style.display = 'none';
            });
            
            // Show the users section
            document.querySelector('.users-section').style.display = 'block';
        }
    </script>
</body>
</html> 