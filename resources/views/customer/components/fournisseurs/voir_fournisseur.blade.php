<div class="supplier-details-container">
    <div class="details-header">
        <h2><i class="fas fa-truck"></i> Détails du Fournisseur</h2>
        <button class="btn-retour" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </button>
    </div>

    <div class="details-content">
        <div class="info-section">
            <div class="section-header">
                <i class="fas fa-info-circle"></i>
                <h3>Informations Générales</h3>
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Nom du Fournisseur</label>
                    <p>{{ $fournisseur->fournisseur_name }}</p>
                </div>
                <div class="info-item">
                    <label>Statut</label>
                    <p class="status {{ $fournisseur->fournisseur_actif ? 'active' : 'inactive' }}">
                        <i class="fas {{ $fournisseur->fournisseur_actif ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $fournisseur->fournisseur_actif ? 'Actif' : 'Inactif' }}
                    </p>
                </div>
                <div class="info-item">
                    <label>Balance</label>
                    <p class="balance">{{ number_format($fournisseur->debut_balance_fournisseur, 2) }} MAD</p>
                </div>
                @if($fournisseur->has_tin)
                <div class="info-item">
                    <label>Numéro d'Identification Fiscale</label>
                    <p>{{ $fournisseur->tin }}</p>
                </div>
                @endif
                @if($fournisseur->autre_id_vendeur)
                <div class="info-item">
                    <label>Autre ID Vendeur</label>
                    <p>{{ $fournisseur->autre_id_vendeur }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="info-section">
            <div class="section-header">
                <i class="fas fa-address-card"></i>
                <h3>Contact</h3>
            </div>
            <div class="info-grid">
                @if($fournisseur->contact_personne)
                <div class="info-item">
                    <label>Personne de Contact</label>
                    <p><i class="fas fa-user"></i> {{ $fournisseur->contact_personne }}</p>
                </div>
                @endif
                @if($fournisseur->telephone)
                <div class="info-item">
                    <label>Téléphone</label>
                    <p><i class="fas fa-phone"></i> {{ $fournisseur->phone_code }} {{ $fournisseur->telephone }}</p>
                </div>
                @endif
                @if($fournisseur->fax)
                <div class="info-item">
                    <label>Fax</label>
                    <p><i class="fas fa-fax"></i> {{ $fournisseur->fax_code }} {{ $fournisseur->fax }}</p>
                </div>
                @endif
                @if($fournisseur->email)
                <div class="info-item">
                    <label>Email</label>
                    <p><i class="fas fa-envelope"></i> <a href="mailto:{{ $fournisseur->email }}">{{ $fournisseur->email }}</a></p>
                </div>
                @endif
                @if($fournisseur->website)
                <div class="info-item">
                    <label>Site Web</label>
                    <p><i class="fas fa-globe"></i> <a href="{{ $fournisseur->website }}" target="_blank">{{ $fournisseur->website }}</a></p>
                </div>
                @endif
            </div>
        </div>

        <div class="info-section">
            <div class="section-header">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Adresse</h3>
            </div>
            <div class="info-grid">
                @if($fournisseur->adresse)
                <div class="info-item">
                    <label>Adresse</label>
                    <p><i class="fas fa-home"></i> {{ $fournisseur->adresse }}</p>
                </div>
                @endif
                @if($fournisseur->complement)
                <div class="info-item">
                    <label>Complément</label>
                    <p>{{ $fournisseur->complement }}</p>
                </div>
                @endif
                @if($fournisseur->adresse_sup)
                <div class="info-item">
                    <label>Adresse Supplémentaire</label>
                    <p>{{ $fournisseur->adresse_sup }}</p>
                </div>
                @endif
                @if($fournisseur->immeuble)
                <div class="info-item">
                    <label>Immeuble</label>
                    <p><i class="fas fa-building"></i> {{ $fournisseur->immeuble }}</p>
                </div>
                @endif
                <div class="info-item location">
                    <label>Localisation</label>
                    <p>
                        <i class="fas fa-map-pin"></i>
                        {{ $fournisseur->ville }}
                        @if($fournisseur->code_postal), {{ $fournisseur->code_postal }}@endif
                        @if($fournisseur->region), {{ $fournisseur->region }}@endif
                        @if($fournisseur->pays), {{ $fournisseur->pays }}@endif
                    </p>
                </div>
            </div>
        </div>

        @if($fournisseur->linkedin || $fournisseur->facebook || $fournisseur->twitter || $fournisseur->google)
        <div class="info-section social-section">
            <div class="section-header">
                <i class="fas fa-share-alt"></i>
                <h3>Réseaux Sociaux</h3>
            </div>
            <div class="social-links">
                @if($fournisseur->linkedin)
                <a href="{{ $fournisseur->linkedin }}" target="_blank" class="social-link linkedin">
                    <i class="fab fa-linkedin"></i>
                    <span>LinkedIn</span>
                </a>
                @endif
                @if($fournisseur->facebook)
                <a href="{{ $fournisseur->facebook }}" target="_blank" class="social-link facebook">
                    <i class="fab fa-facebook"></i>
                    <span>Facebook</span>
                </a>
                @endif
                @if($fournisseur->twitter)
                <a href="{{ $fournisseur->twitter }}" target="_blank" class="social-link twitter">
                    <i class="fab fa-twitter"></i>
                    <span>Twitter</span>
                </a>
                @endif
                @if($fournisseur->google)
                <a href="{{ $fournisseur->google }}" target="_blank" class="social-link google">
                    <i class="fab fa-google"></i>
                    <span>Google</span>
                </a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.supplier-details-container {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin: 25px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.details-header {
    background: linear-gradient(135deg, #8B5CF6, #6D28D9);
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.details-header h2 {
    color: #ffffff;
    font-size: 1.8rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.details-header h2 i {
    font-size: 1.6rem;
}

.btn-retour {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 0.95rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-retour:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

.details-content {
    padding: 30px;
}

.info-section {
    background: #ffffff;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.info-section:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f1f5f9;
}

.section-header i {
    font-size: 1.4rem;
    color: #8B5CF6;
}

.section-header h3 {
    color: #1e293b;
    font-size: 1.3rem;
    font-weight: 600;
    margin: 0;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.info-item {
    padding: 20px;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: #ffffff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transform: translateY(-2px);
}

.info-item label {
    display: block;
    color: #64748b;
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 8px;
}

.info-item p {
    color: #1e293b;
    font-size: 1.1rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-item p i {
    color: #8B5CF6;
    font-size: 1rem;
}

.info-item a {
    color: #8B5CF6;
    text-decoration: none;
    transition: color 0.2s;
}

.info-item a:hover {
    color: #6D28D9;
    text-decoration: underline;
}

.status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 500;
}

.status.active {
    background: #ecfdf5;
    color: #059669;
}

.status.inactive {
    background: #fef2f2;
    color: #dc2626;
}

.balance {
    color: #8B5CF6 !important;
    font-weight: 600;
    font-size: 1.2rem !important;
}

.location p {
    line-height: 1.5;
}

.social-section {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
}

.social-links {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    padding: 10px;
}

.social-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 10px;
    color: #ffffff;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.social-link i {
    font-size: 1.2rem;
}

.social-link span {
    font-size: 0.95rem;
}

.social-link.linkedin {
    background: #0077b5;
}

.social-link.facebook {
    background: #1877f2;
}

.social-link.twitter {
    background: #1da1f2;
}

.social-link.google {
    background: #ea4335;
}

.social-link:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

@media (max-width: 768px) {
    .supplier-details-container {
        margin: 15px;
    }

    .details-header {
        padding: 20px;
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .details-header h2 {
        font-size: 1.5rem;
    }

    .details-content {
        padding: 20px;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .social-links {
        justify-content: center;
    }
}
</style>

<script>
function returnToList() {
    document.getElementById('dynamic-content').style.display = 'none';
    document.getElementById('default-dashboard').style.display = 'block';
}
</script> 