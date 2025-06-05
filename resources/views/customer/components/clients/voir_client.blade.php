{{-- resources/views/customer/components/clients/voir_client.blade.php --}}
<div class="client-details-container">
    <div class="details-header">
        <h2><i class="fas fa-user"></i> Détails du client</h2>
        <button class="btn btn-back load-view" data-view="clients/liste_clients">
            <i class="fas fa-arrow-left"></i> Retour
        </button>
    </div>

    <div class="client-info-card">
        <div class="info-sections">
            {{-- Basic Information Section --}}
            <div class="info-section">
                <h3><i class="fas fa-info-circle"></i> Informations de base</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Nom du client</label>
                        <span>{{ $client->client_name }}</span>
                    </div>
                    <div class="info-item">
                        <label>Statut</label>
                        <span class="status-badge {{ $client->client_actif ? 'active' : 'inactive' }}">
                            {{ $client->client_actif ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <label>TIN/TVA</label>
                        <span>{{ $client->has_tin ? 'Oui' : 'Non' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Numéro TIN</label>
                        <span>{{ $client->tin ?: 'Non spécifié' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Points de fidélité</label>
                        <span>{{ $client->points ?: '0' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Solde</label>
                        <span class="solde {{ $client->solde > 0 ? 'positive' : ($client->solde < 0 ? 'negative' : '') }}">
                            {{ number_format($client->solde, 2) }} MAD
                        </span>
                    </div>
                </div>
            </div>

            {{-- Contact Information Section --}}
            <div class="info-section">
                <h3><i class="fas fa-address-book"></i> Coordonnées</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Personne de contact</label>
                        <span>{{ $client->contact_personne ?: 'Non spécifié' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Téléphone</label>
                        <span>{{ $client->phone_code }} {{ $client->telephone ?: 'Non spécifié' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Fax</label>
                        <span>{{ $client->fax_code }} {{ $client->fax ?: 'Non spécifié' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Email</label>
                        <span>{{ $client->email ?: 'Non spécifié' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Site web</label>
                        <span>{{ $client->website ?: 'Non spécifié' }}</span>
                    </div>
                </div>
            </div>

            {{-- Address Section --}}
            <div class="info-section">
                <h3><i class="fas fa-map-marker-alt"></i> Adresse</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Adresse principale</label>
                        <span>{{ $client->adresse ?: 'Non spécifié' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Complément</label>
                        <span>{{ $client->complement ?: 'Non spécifié' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Immeuble</label>
                        <span>{{ $client->immeuble ?: 'Non spécifié' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Ville</label>
                        <span>{{ $client->ville ?: 'Non spécifié' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Code postal</label>
                        <span>{{ $client->code_postal ?: 'Non spécifié' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Pays</label>
                        <span>{{ $client->pays ?: 'Non spécifié' }}</span>
                    </div>
                </div>
            </div>

            {{-- Social Media Section --}}
            <div class="info-section">
                <h3><i class="fas fa-share-alt"></i> Réseaux sociaux</h3>
                <div class="social-links">
                    @if($client->linkedin)
                        <a href="{{ $client->linkedin }}" target="_blank" class="social-link linkedin">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    @endif
                    @if($client->facebook)
                        <a href="{{ $client->facebook }}" target="_blank" class="social-link facebook">
                            <i class="fab fa-facebook"></i>
                        </a>
                    @endif
                    @if($client->twitter)
                        <a href="{{ $client->twitter }}" target="_blank" class="social-link twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                    @if($client->google)
                        <a href="{{ $client->google }}" target="_blank" class="social-link google">
                            <i class="fab fa-google"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.client-details-container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.details-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.details-header h2 {
    font-size: 24px;
    color: #333;
    margin: 0;
}

.btn-back {
    padding: 8px 16px;
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background-color: #e9ecef;
}

.client-info-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 20px;
}

.info-sections {
    display: grid;
    gap: 30px;
}

.info-section {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.info-section h3 {
    color: #333;
    font-size: 18px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-section h3 i {
    color: #4f46e5;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-item label {
    color: #666;
    font-size: 14px;
}

.info-item span {
    color: #333;
    font-size: 16px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
}

.status-badge.active {
    background-color: #d1fae5;
    color: #065f46;
}

.status-badge.inactive {
    background-color: #fee2e2;
    color: #991b1b;
}

.solde {
    font-weight: 600;
}

.solde.positive {
    color: #059669;
}

.solde.negative {
    color: #dc2626;
}

.social-links {
    display: flex;
    gap: 15px;
}

.social-link {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: white;
    font-size: 20px;
    transition: all 0.3s ease;
}

.social-link:hover {
    transform: translateY(-2px);
}

.social-link.linkedin {
    background-color: #0077b5;
}

.social-link.facebook {
    background-color: #1877f2;
}

.social-link.twitter {
    background-color: #1da1f2;
}

.social-link.google {
    background-color: #ea4335;
}
</style> 