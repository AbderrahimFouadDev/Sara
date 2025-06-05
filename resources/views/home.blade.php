<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All-in-One Platform - Solution de Gestion Intégrée pour TPE/PME au Maroc</title>

     <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    @vite(['resources/css/home.css', 'resources/js/home.js'])

</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <a  href="{{ url('/') }}">All-in-One</a>
            </div>
            <nav class="main-nav">
                <button class="mobile-menu-btn" aria-label="Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <ul class="nav-links">
                    <li><a href="#home" class="active">Accueil</a></li>
                    <li><a href="#features">Fonctionnalités</a></li>
                    <li><a href="#modules">Modules</a></li>
                    <li><a href="#pricing">Tarifs</a></li>
                    <li><a href="#footer">Contact</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                <a href="{{ route('login') }}" class="btn btn-login">Connexion</a>
                <a href="{{ route('register') }}"class="btn btn-primary">S'inscrire</a>

            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container hero-container">
            <div class="hero-content">
                <h1>Plateforme de gestion intégrée pour <span class="highlight">TPE/PME</span> au Maroc</h1>
                <p class="hero-desc">Une solution SaaS complète pour gérer facturation, comptabilité, CRM, ressources humaines et stocks. Conçue pour les entreprises marocaines.</p>
                <div class="hero-cta">
                    <a href="#register" class="btn btn-primary btn-lg">Démarrer votre essai gratuit <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="hero-trust">
            
                    <span class="trust-text"><strong>+500</strong> entreprises marocaines nous font déjà confiance</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Dashboard Mockup Section -->
    <section class="dashboard-mockup">
        <div class="container">
            <div class="browser-mockup">
                <div class="browser-bar"></div>
                <div class="dashboard-card">
                    <h3>Dashboard intuitif et complet</h3>
                    <p>Interface simplifiée donnant accès à tous les modules en un coup d'œil. Visualisation en temps réel des données essentielles de votre entreprise.</p>
                    <div class="dashboard-modules">
                        <button class="module-btn">Facturation</button>
                        <button class="module-btn">Comptabilité</button>
                        <button class="module-btn">CRM</button>
                        <button class="module-btn">RH & Paie</button>
                        <button class="module-btn">Stocks</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Target Audience Section -->
    <section class="audience" id="audience">
        <div class="container">
            <h2>Pour qui ?</h2>
            <div class="audience-grid">
                <div class="audience-card">
                    <i class="fas fa-user-tie"></i>
                    <h3>Auto-entrepreneurs</h3>
                    <p>Gérez facilement votre activité avec des outils adaptés</p>
                </div>
                <div class="audience-card">
                    <i class="fas fa-building"></i>
                    <h3>TPE/PME</h3>
                    <p>Solution complète pour la croissance de votre entreprise</p>
                </div>
                <div class="audience-card">
                    <i class="fas fa-calculator"></i>
                    <h3>Cabinets comptables</h3>
                    <p>Outils professionnels pour la gestion de vos clients</p>
                </div>
                <div class="audience-card">
                    <i class="fas fa-store"></i>
                    <h3>Commerçants</h3>
                    <p>Gestion simplifiée de votre commerce</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Benefits Section -->
    <section class="benefits" id="features">
        <div class="container">
            <h2>Pourquoi choisir notre plateforme ?</h2>
            <div class="benefits-grid">
                <div class="benefit-card">
                    <i class="fas fa-stream"></i>
                    <h3>Gestion simplifiée</h3>
                    <p>Interface intuitive et centralisée pour une gestion efficace de tous vos processus</p>
                </div>
                <div class="benefit-card">
                    <i class="fas fa-cloud"></i>
                    <h3>100% en ligne</h3>
                    <p>Accédez à vos données partout, tout le temps, sur tous vos appareils</p>
                </div>
                <div class="benefit-card">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Adapté au marché marocain</h3>
                    <p>Conforme aux normes locales : PCG, TVA, CNSS, IR</p>
                </div>
                <div class="benefit-card">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Sécurité & conformité</h3>
                    <p>Protection maximale de vos données et conformité RGPD</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Modules Preview Section -->
    <section class="modules" id="modules">
        <div class="container">
            <h2>Nos Modules</h2>
            <div class="modules-grid">
                <div class="module-card">
                    <i class="fas fa-file-invoice"></i>
                    <h3>Facturation</h3>
                    <ul class="module-features">
                        <li>Factures et devis personnalisables</li>
                        <li>Export PDF et envoi WhatsApp</li>
                        <li>Multi-devises et multi-taux TVA</li>
                        <li>Suivi des paiements</li>
                    </ul>
                    <a href="#facturation" class="module-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="module-card">
                    <i class="fas fa-calculator"></i>
                    <h3>Comptabilité</h3>
                    <ul class="module-features">
                        <li>Plan comptable marocain</li>
                        <li>Déclaration TVA automatique</li>
                        <li>Rapprochement bancaire</li>
                        <li>Multi-exercices</li>
                    </ul>
                    <a href="#comptabilite" class="module-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="module-card">
                    <i class="fas fa-users"></i>
                    <h3>RH & Paie</h3>
                    <ul class="module-features">
                        <li>Bulletins de paie automatiques</li>
                        <li>Calcul IR et CNSS</li>
                        <li>Gestion des congés</li>
                        <li>Export DSN</li>
                    </ul>
                    <a href="#rh" class="module-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="module-card">
                    <i class="fas fa-handshake"></i>
                    <h3>CRM</h3>
                    <ul class="module-features">
                        <li>Fiches clients détaillées</li>
                        <li>Suivi des opportunités</li>
                        <li>Relances automatiques</li>
                        <li>Signature électronique</li>
                    </ul>
                    <a href="#crm" class="module-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="module-card">
                    <i class="fas fa-boxes"></i>
                    <h3>Stock & Produits</h3>
                    <ul class="module-features">
                        <li>Gestion multi-entrepôts</li>
                        <li>Alertes de stock</li>
                        <li>Suivi des marges</li>
                        <li>Inventaires automatiques</li>
                    </ul>
                    <a href="#stock" class="module-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing" id="pricing">
        <div class="container">
            <h2 class="pricing-title">Tarification simple et transparente</h2>
            <p class="pricing-subtitle">Choisissez le forfait qui correspond à vos besoins. Tous nos plans incluent un essai gratuit de 14 jours.</p>
            <div class="pricing-grid">
                <!-- Starter Plan -->
                <div class="pricing-card">
                    <h3>Starter</h3>
                    <div class="price"><span class="amount">299</span> DH<span class="period">/mois</span></div>
                    <p class="plan-desc">Idéal pour les auto-entrepreneurs et très petites entreprises</p>
                    <ul class="plan-features">
                        <li>Module de facturation & devis</li>
                        <li>Gestion basique des clients</li>
                        <li>Dashboard simplifié</li>
                        <li>1 utilisateur</li>
                        <li>Exports PDF illimités</li>
                    </ul>
                    <a href="#register" class="btn btn-pricing">Démarrer l'essai gratuit</a>
                </div>
                <!-- Business Plan (highlighted) -->
                <div class="pricing-card popular">
                    <div class="popular-badge">Plan le plus populaire</div>
                    <h3>Business</h3>
                    <div class="price"><span class="amount">499</span> DH<span class="period">/mois</span></div>
                    <p class="plan-desc">Parfait pour les PME avec des besoins de gestion complets</p>
                    <ul class="plan-features">
                        <li>Tous les modules disponibles</li>
                        <li>10 utilisateurs inclus</li>
                        <li>Intégrations (Stripe, PayPal)</li>
                        <li>Support prioritaire</li>
                        <li>Personnalisation avancée</li>
                    </ul>
                    <a href="#register" class="btn btn-pricing popular-btn">Solution recommandée</a>
                </div>
                <!-- Enterprise Plan -->
                <div class="pricing-card">
                    <h3>Enterprise</h3>
                    <div class="price"><span class="amount">999</span> DH<span class="period">/mois</span></div>
                    <p class="plan-desc">Pour les entreprises avec des besoins spécifiques et avancés</p>
                    <ul class="plan-features">
                        <li>Tous les modules disponibles</li>
                        <li>Utilisateurs illimités</li>
                        <li>API complète</li>
                        <li>Accompagnement dédié</li>
                        <li>Formations personnalisées</li>
                    </ul>
                    <a href="#contact" class="btn btn-pricing">Contacter l'équipe commerciale</a>
                </div>
            </div>
        </div>
    </section>
    <!-- End Pricing Section -->

    <!-- Integration Section -->
    

    <!-- Testimonials Section -->
 

    <!-- FAQ Section -->
    <section class="faq" id="faq">
        <div class="container">
            <h2 class="faq-title">Questions fréquentes</h2>
            <div class="faq-grid">
                <div class="faq-item">
                    <div class="faq-icon"><i class="fas fa-question-circle"></i></div>
                    <div>
                        <h4>Puis-je changer de forfait ?</h4>
                        <p>Oui, vous pouvez passer à un niveau supérieur ou inférieur à tout moment, avec un ajustement au prorata de votre facturation.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-icon"><i class="fas fa-credit-card"></i></div>
                    <div>
                        <h4>L'essai gratuit nécessite-t-il une carte bancaire ?</h4>
                        <p>Non, vous pouvez essayer notre solution pendant 14 jours sans aucun engagement ni carte bancaire.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-icon"><i class="fas fa-cogs"></i></div>
                    <div>
                        <h4>Existe-t-il des frais de mise en service ?</h4>
                        <p>Aucun frais de mise en service. Nous proposons cependant des services d'accompagnement optionnels pour les grands comptes.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-icon"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <h4>Mes données sont-elles sécurisées ?</h4>
                        <p>Absolument. Nous utilisons un cryptage SSL avancé et des sauvegardes quotidiennes. Vos données ne quittent jamais nos serveurs sécurisés.</p>
                    </div>
                </div>
            </div>
            <div class="faq-note">TVA non incluse (20%) <i class="fas fa-info-circle"></i></div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <h2 class="contact-title">Contactez-nous</h2>
            <p class="contact-subtitle">Nous sommes là pour répondre à toutes vos questions</p>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="contact-form-container">
                <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nom complet</label>
                        <input type="text" id="name" name="name" required class="form-control" placeholder="Votre nom">
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required class="form-control" placeholder="Votre email">
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" required class="form-control" rows="5" placeholder="Votre message"></textarea>
                        @error('message')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Envoyer le message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="footer-new" id="footer">
        <div class="container footer-container">
            <div class="footer-col company-info">
                <h3>All-in-One <span class="footer-badge">SaaS</span></h3>
                <p class="footer-desc">La plateforme de gestion tout-en-un adaptée aux TPE/PME marocaines. Simplifiez votre administration avec nos 5 modules intégrés.</p>
                <ul class="footer-contact">
                    <li><i class="fas fa-map-marker-alt"></i> Twin Center, Tour A, 10ème étage, Casablanca, Maroc</li>
                    <li><i class="fas fa-phone"></i> +212 522 000 000</li>
                    <li><i class="fas fa-envelope"></i> contact@all-in-one-saas.ma</li>
                </ul>
            </div>
            <div class="footer-col footer-links">
                <h4>Liens utiles</h4>
                <ul>
                    <li><a href="#modules">Nos modules</a></li>
                    <li><a href="#features">Avantages</a></li>
                    <li><a href="#pricing">Tarification</a></li>
                    <li><a href="#faq">FAQ</a></li>
                    <li><a href="#about">À propos</a></li>
                    <li><a href="#terms">Mentions légales</a></li>
                </ul>
            </div>
            <div class="footer-col newsletter">
                <h4>Restez informé</h4>
                <p>Inscrivez-vous à notre newsletter pour recevoir nos actualités et meilleures pratiques.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Votre email" required>
                    <button type="submit">S'abonner</button>
                </form>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <span>&copy; 2025 All-in-One SaaS. Tous droits réservés.</span>
                <div class="footer-bottom-links">
                    <a href="#">Politique de confidentialité</a>
                    <a href="#">Conditions d'utilisation</a>
                </div>
            </div>
        </div>
    </footer>

    <style>
    .contact {
        padding: 80px 0;
        background-color: #f8fafc;
    }

    .contact-title {
        text-align: center;
        font-size: 2.5rem;
        color: #1a237e;
        margin-bottom: 1rem;
    }

    .contact-subtitle {
        text-align: center;
        color: #64748b;
        margin-bottom: 3rem;
    }

    .contact-form-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .contact-form .form-group {
        margin-bottom: 1.5rem;
    }

    .contact-form label {
        display: block;
        margin-bottom: 0.5rem;
        color: #334155;
        font-weight: 500;
    }

    .contact-form .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .contact-form .form-control:focus {
        border-color: #1a237e;
        box-shadow: 0 0 0 2px rgba(26, 35, 126, 0.1);
        outline: none;
    }

    .contact-form textarea.form-control {
        resize: vertical;
        min-height: 120px;
    }

    .contact-form .btn-block {
        width: 100%;
        padding: 1rem;
        font-size: 1.1rem;
        background: #1a237e;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .contact-form .btn-block:hover {
        background: #534bae;
        transform: translateY(-1px);
    }

    .error-message {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .alert {
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 2rem;
        text-align: center;
    }

    .alert-success {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    /* Dark mode support */
    [data-theme="dark"] .contact {
        background-color: #1a1a2e;
    }

    [data-theme="dark"] .contact-title {
        color: #e2e8f0;
    }

    [data-theme="dark"] .contact-subtitle {
        color: #94a3b8;
    }

    [data-theme="dark"] .contact-form-container {
        background: #262640;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }

    [data-theme="dark"] .contact-form label {
        color: #e2e8f0;
    }

    [data-theme="dark"] .contact-form .form-control {
        background: #1f1f3a;
        border-color: #373760;
        color: #e2e8f0;
    }

    [data-theme="dark"] .contact-form .form-control:focus {
        border-color: #534bae;
        box-shadow: 0 0 0 2px rgba(83, 75, 174, 0.2);
    }

    [data-theme="dark"] .contact-form .btn-block {
        background: #534bae;
    }

    [data-theme="dark"] .contact-form .btn-block:hover {
        background: #7871d5;
    }
    </style>

</body>
</html> 