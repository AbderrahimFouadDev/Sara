{{-- resources/views/customer/components/rh/voir_salarie.blade.php --}}
<div class="employee-details-container">
    <div class="header">
        <h2 class="title">
            <i class="fas fa-user"></i>
            Détails du Salarié
        </h2>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="modifierSalarie({{ $salarie->id }})">
                <i class="fas fa-edit"></i> Modifier
            </button>
            <button class="btn btn-secondary" onclick="genererBulletin({{ $salarie->id }})">
                <i class="fas fa-file-invoice-dollar"></i> Bulletin de Paie
            </button>
            <button class="btn btn-outline" onclick="document.dispatchEvent(new CustomEvent('loadView',{detail:{view:'rh.salaries'}}))">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
        </div>
    </div>

    <div class="employee-profile">
        <div class="profile-header">
            <div class="profile-image">
                @if($salarie->photo)
                    <img src="{{ asset('storage/' . $salarie->photo) }}" alt="Photo de {{ $salarie->nom_complet }}">
                @else
                    <div class="placeholder-image">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            </div>
            <div class="profile-info">
                <h3>{{ $salarie->nom_complet }}</h3>
                <p class="status {{ $salarie->statut }}">
                    <i class="fas fa-circle"></i>
                    {{ ucfirst($salarie->statut) }}
                </p>
                <p class="position">{{ $salarie->poste }} - {{ $salarie->departement }}</p>
            </div>
        </div>

        <div class="info-sections">
            <!-- Informations Personnelles -->
            <div class="info-section">
                <h4><i class="fas fa-id-card"></i> Informations Personnelles</h4>
                <div class="info-grid">
                    <div class="info-item">
                        <label>CIN</label>
                        <p>{{ $salarie->cin }}</p>
                    </div>
                    <div class="info-item">
                        <label>CNSS</label>
                        <p>{{ $salarie->cnss ?? 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>

            <!-- Informations Professionnelles -->
            <div class="info-section">
                <h4><i class="fas fa-briefcase"></i> Informations Professionnelles</h4>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Date d'embauche</label>
                        <p>{{ $salarie->date_embauche->format('d/m/Y') }}</p>
                    </div>
                    <div class="info-item">
                        <label>Type de Contrat</label>
                        <p>{{ $salarie->type_contrat }}</p>
                    </div>
                    <div class="info-item">
                        <label>Date Début Contrat</label>
                        <p>{{ $salarie->date_debut_contrat->format('d/m/Y') }}</p>
                    </div>
                    <div class="info-item">
                        <label>Date Fin Contrat</label>
                        <p>{{ $salarie->date_fin_contrat ? $salarie->date_fin_contrat->format('d/m/Y') : 'CDI' }}</p>
                    </div>
                    <div class="info-item">
                        <label>Salaire de Base</label>
                        <p>{{ number_format($salarie->salaire_base, 2, ',', ' ') }} DH</p>
                    </div>
                    <div class="info-item">
                        <label>Dernier Net Payé</label>
                        <p>{{ $salarie->dernier_net_paye ? number_format($salarie->dernier_net_paye, 2, ',', ' ') . ' DH' : 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>

            <!-- Congés et Absences -->
            <div class="info-section">
                <h4><i class="fas fa-calendar-alt"></i> Congés et Absences</h4>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Jours de Congés Restants</label>
                        <p>{{ $salarie->jours_conges_restants }} jours</p>
                    </div>
                    <div class="info-item">
                        <label>Absences Non Justifiées</label>
                        <p>{{ $salarie->jours_absences_non_justifiees }} jours</p>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="info-section">
                <h4><i class="fas fa-file-alt"></i> Documents</h4>
                <div class="documents-grid">
                    @if($salarie->document_cin)
                    <div class="document-item">
                        <i class="fas fa-id-card"></i>
                        <div class="document-actions">
                            <a href="{{ asset('storage/' . $salarie->document_cin) }}" 
                               target="_blank" 
                               class="document-preview">
                                <span>CIN</span>
                            </a>
                            <a href="{{ asset('storage/' . $salarie->document_cin) }}" 
                               class="download-button"
                               download="CIN_{{ $salarie->nom_complet }}"
                               onclick="trackDownload('CIN')">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    @if($salarie->document_cnss)
                    <div class="document-item">
                        <i class="fas fa-file-medical"></i>
                        <div class="document-actions">
                            <a href="{{ asset('storage/' . $salarie->document_cnss) }}" 
                               target="_blank" 
                               class="document-preview">
                                <span>CNSS</span>
                            </a>
                            <a href="{{ asset('storage/' . $salarie->document_cnss) }}" 
                               class="download-button"
                               download="CNSS_{{ $salarie->nom_complet }}"
                               onclick="trackDownload('CNSS')">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    @if($salarie->document_contrat)
                    <div class="document-item">
                        <i class="fas fa-file-contract"></i>
                        <div class="document-actions">
                            <a href="{{ asset('storage/' . $salarie->document_contrat) }}" 
                               target="_blank" 
                               class="document-preview">
                                <span>Contrat</span>
                            </a>
                            <a href="{{ asset('storage/' . $salarie->document_contrat) }}" 
                               class="download-button"
                               download="Contrat_{{ $salarie->nom_complet }}"
                               onclick="trackDownload('Contrat')">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.employee-details-container {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.title {
    font-size: 1.5rem;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary {
    background: #3b82f6;
    color: white;
    border: none;
}

.btn-secondary {
    background: #10b981;
    color: white;
    border: none;
}

.btn-outline {
    background: white;
    color: #6b7280;
    border: 1px solid #d1d5db;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 2rem;
    padding: 2rem;
    background: #f8fafc;
    border-radius: 12px;
}

.profile-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #9ca3af;
}

.profile-info h3 {
    font-size: 1.5rem;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.status.actif {
    background: #dcfce7;
    color: #16a34a;
}

.status.congé {
    background: #fff7ed;
    color: #ea580c;
}

.status.quitté {
    background: #fef2f2;
    color: #dc2626;
}

.position {
    color: #4b5563;
    font-size: 1rem;
}

.info-sections {
    display: grid;
    gap: 2rem;
}

.info-section {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1.5rem;
}

.info-section h4 {
    color: #1f2937;
    font-size: 1.1rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.info-item label {
    display: block;
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.info-item p {
    color: #1f2937;
    font-size: 1rem;
    font-weight: 500;
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.document-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #f9fafb;
    border-radius: 6px;
    transition: all 0.2s;
    border: 1px solid #e5e7eb;
}

.document-item:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
}

.document-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex: 1;
    gap: 0.5rem;
}

.document-preview {
    color: #2563eb;
    text-decoration: none;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s;
    flex: 1;
}

.document-preview:hover {
    background-color: #eff6ff;
    color: #1d4ed8;
}

.download-button {
    color: #6b7280;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s;
}

.download-button:hover {
    color: #2563eb;
    background-color: #eff6ff;
}

.download-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #10b981;
    color: white;
    padding: 12px 24px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    animation: slideIn 0.3s ease-out;
    z-index: 1000;
}

.download-notification i {
    font-size: 1.2em;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@media (max-width: 768px) {
    .employee-details-container {
        margin: 1rem;
        padding: 1rem;
    }

    .header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .header-actions {
        width: 100%;
        flex-direction: column;
    }

    .profile-header {
        flex-direction: column;
        text-align: center;
        padding: 1rem;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function trackDownload(documentType) {
    // Show a notification when download starts
    const notification = document.createElement('div');
    notification.className = 'download-notification';
    notification.innerHTML = `
        <i class="fas fa-check-circle"></i>
        Téléchargement du document ${documentType} en cours...
    `;
    document.body.appendChild(notification);

    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script> 