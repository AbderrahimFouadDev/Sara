<div class="main-container">
    <div class="header">
        <h2 class="title">
            <i class="fas fa-file-contract icon"></i>Gestion des Contrats
        </h2>
        <div class="header-buttons">
            <button class="btn btn-primary" onclick="ajouterContrat()">
                <i class="fas fa-plus"></i>Nouveau Contrat
            </button>
            <button class="btn btn-secondary" onclick="exportContrats()">
                <i class="fas fa-file-export"></i>Exporter
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="stat-card active-contracts">
            <div class="card-content">
                <div class="card-icon">
                    <i class="fas fa-file-signature"></i>
                </div>
                <div class="card-info">
                    <h3>Contrats Actifs</h3>
                    <p>{{ $activeContracts ?? 42 }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card expiring-contracts">
            <div class="card-content">
                <div class="card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="card-info">
                    <h3>Contrats à Renouveler</h3>
                    <p>{{ $expiringContracts ?? 3 }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card trial-period">
            <div class="card-content">
                <div class="card-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="card-info">
                    <h3>Période d'Essai</h3>
                    <p>{{ $trialPeriod ?? 5 }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card ended-contracts">
            <div class="card-content">
                <div class="card-icon">
                    <i class="fas fa-file-archive"></i>
                </div>
                <div class="card-info">
                    <h3>Contrats Terminés</h3>
                    <p>{{ $endedContracts ?? 8 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-container">
        <div class="filters">
            <select onchange="filterByType(this.value)">
                <option value="">Type de contrat</option>
                <option value="cdi">CDI</option>
                <option value="cdd">CDD</option>
                <option value="stage">Stage</option>
                <option value="interim">Intérim</option>
            </select>
            <select onchange="filterByStatus(this.value)">
                <option value="">Statut</option>
                <option value="active">Actif</option>
                <option value="trial">Période d'essai</option>
                <option value="expiring">À renouveler</option>
                <option value="ended">Terminé</option>
            </select>
            <select onchange="filterByDepartment(this.value)">
                <option value="">Département</option>
                <option value="it">IT</option>
                <option value="rh">RH</option>
                <option value="finance">Finance</option>
                <option value="commercial">Commercial</option>
            </select>
        </div>
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Rechercher un employé..." onkeyup="searchContracts(this.value)">
        </div>
    </div>

    <!-- Contracts Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Type</th>
                    <th>Date Début</th>
                    <th>Date Fin</th>
                    <th>Salaire</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contrats ?? [] as $contrat)
                <tr>
                    <td>
                        <div class="employee-info">
                            <div class="employee-image">
                                <img src="{{ $contrat->employee->photo ?? 'https://via.placeholder.com/32' }}" 
                                     alt="Photo employé">
                            </div>
                            <div class="employee-details">
                                <div class="employee-name">{{ $contrat->employee->nom ?? 'John Doe' }}</div>
                                <div class="employee-id">ID: {{ $contrat->employee->matricule ?? 'EMP001' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="contract-type {{ $contrat->type ?? 'cdi' }}">
                            {{ $contrat->type == 'cdi' ? 'CDI' : 
                               ($contrat->type == 'cdd' ? 'CDD' : 
                               ($contrat->type == 'stage' ? 'Stage' : 'Intérim')) }}
                        </span>
                    </td>
                    <td>{{ $contrat->date_debut ?? '2024-01-15' }}</td>
                    <td>{{ $contrat->date_fin ?? '2025-01-14' }}</td>
                    <td>{{ number_format($contrat->salaire ?? 10000, 2) }} DH</td>
                    <td>
                        <span class="status-badge {{ $contrat->status ?? 'active' }}">
                            {{ $contrat->status == 'active' ? 'Actif' : 
                               ($contrat->status == 'trial' ? 'Période d\'essai' : 
                               ($contrat->status == 'expiring' ? 'À renouveler' : 'Terminé')) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn view-btn" onclick="voirContrat('{{ $contrat->id ?? 1 }}')" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn edit-btn" onclick="modifierContrat('{{ $contrat->id ?? 1 }}')" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn print-btn" onclick="imprimerContrat('{{ $contrat->id ?? 1 }}')" title="Imprimer">
                                <i class="fas fa-print"></i>
                            </button>
                            <button class="action-btn archive-btn" onclick="archiverContrat('{{ $contrat->id ?? 1 }}')" title="Archiver">
                                <i class="fas fa-archive"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-file-contract empty-icon"></i>
                            <p>Aucun contrat trouvé</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
/* Reset and base styles */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/* Main container */
.main-container {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 24px;
    margin: 20px;
}

/* Header styles */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.title {
    font-size: 24px;
    font-weight: 600;
    color: #1f2937;
}

.icon {
    margin-right: 8px;
    color: #3b82f6;
}

.header-buttons {
    display: flex;
    gap: 12px;
}

/* Button styles */
.btn {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
}

.btn i {
    margin-right: 8px;
}

.btn-primary {
    background-color: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background-color: #2563eb;
    transform: translateY(-1px);
}

.btn-secondary {
    background-color: #4b5563;
    color: white;
}

.btn-secondary:hover {
    background-color: #374151;
    transform: translateY(-1px);
}

/* Stats Cards */
.summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    border-radius: 8px;
    padding: 16px;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.card-content {
    display: flex;
    align-items: center;
}

.card-icon {
    font-size: 24px;
    margin-right: 12px;
}

.card-info h3 {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 4px;
}

.card-info p {
    font-size: 20px;
    font-weight: 600;
}

/* Card variations */
.active-contracts {
    background-color: #f0fdf4;
    border: 1px solid #dcfce7;
}
.active-contracts .card-icon { color: #16a34a; }
.active-contracts .card-info h3 { color: #166534; }
.active-contracts .card-info p { color: #16a34a; }

.expiring-contracts {
    background-color: #fff7ed;
    border: 1px solid #ffedd5;
}
.expiring-contracts .card-icon { color: #ea580c; }
.expiring-contracts .card-info h3 { color: #9a3412; }
.expiring-contracts .card-info p { color: #ea580c; }

.trial-period {
    background-color: #eff6ff;
    border: 1px solid #dbeafe;
}
.trial-period .card-icon { color: #3b82f6; }
.trial-period .card-info h3 { color: #1e40af; }
.trial-period .card-info p { color: #3b82f6; }

.ended-contracts {
    background-color: #f3f4f6;
    border: 1px solid #e5e7eb;
}
.ended-contracts .card-icon { color: #6b7280; }
.ended-contracts .card-info h3 { color: #374151; }
.ended-contracts .card-info p { color: #6b7280; }

/* Filter Section */
.filter-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    gap: 16px;
}

.filters {
    display: flex;
    gap: 12px;
}

.filters select {
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 14px;
    color: #374151;
}

.search-box {
    flex: 1;
    position: relative;
    max-width: 300px;
}

.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
}

.search-box input {
    width: 100%;
    padding: 8px 12px 8px 36px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 14px;
}

/* Table styles */
.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.data-table th {
    background-color: #f9fafb;
    padding: 12px;
    text-align: left;
    font-weight: 500;
    color: #6b7280;
    border-bottom: 1px solid #e5e7eb;
}

.data-table td {
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
    color: #374151;
}

.data-table tr:hover {
    background-color: #f9fafb;
}

/* Employee info styles */
.employee-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.employee-image img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.employee-name {
    font-weight: 500;
    color: #1f2937;
}

.employee-id {
    color: #6b7280;
    font-size: 12px;
}

/* Contract type styles */
.contract-type {
    display: inline-flex;
    padding: 4px 8px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 500;
}

.contract-type.cdi {
    background-color: #f0fdf4;
    color: #16a34a;
}

.contract-type.cdd {
    background-color: #eff6ff;
    color: #3b82f6;
}

.contract-type.stage {
    background-color: #fff7ed;
    color: #ea580c;
}

.contract-type.interim {
    background-color: #f3f4f6;
    color: #6b7280;
}

/* Status badge styles */
.status-badge {
    display: inline-flex;
    padding: 4px 8px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.active {
    background-color: #f0fdf4;
    color: #16a34a;
}

.status-badge.trial {
    background-color: #eff6ff;
    color: #3b82f6;
}

.status-badge.expiring {
    background-color: #fff7ed;
    color: #ea580c;
}

.status-badge.ended {
    background-color: #f3f4f6;
    color: #6b7280;
}

/* Action buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    font-size: 14px;
    transition: all 0.2s;
}

.view-btn { color: #3b82f6; }
.view-btn:hover { color: #2563eb; transform: scale(1.1); }

.edit-btn { color: #f59e0b; }
.edit-btn:hover { color: #d97706; transform: scale(1.1); }

.print-btn { color: #10b981; }
.print-btn:hover { color: #059669; transform: scale(1.1); }

.archive-btn { color: #6b7280; }
.archive-btn:hover { color: #4b5563; transform: scale(1.1); }

/* Empty state */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 32px;
    color: #6b7280;
}

.empty-icon {
    font-size: 48px;
    color: #d1d5db;
    margin-bottom: 12px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }

    .header-buttons {
        width: 100%;
        flex-direction: column;
    }

    .filter-container {
        flex-direction: column;
    }

    .filters {
        width: 100%;
        flex-direction: column;
    }

    .search-box {
        width: 100%;
        max-width: none;
    }

    .action-buttons {
        flex-direction: column;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add new contract
    window.ajouterContrat = function() {
        document.dispatchEvent(new CustomEvent('loadView', {
            detail: { view: 'rh.form_contrat' }
        }));
    }

    // Export contracts
    window.exportContrats = function() {
        // Implement export logic
        alert('Fonctionnalité d\'export à implémenter');
    }

    // View contract details
    window.voirContrat = function(contractId) {
        document.dispatchEvent(new CustomEvent('loadView', {
            detail: { view: 'rh.details_contrat', contractId: contractId }
        }));
    }

    // Edit contract
    window.modifierContrat = function(contractId) {
        document.dispatchEvent(new CustomEvent('loadView', {
            detail: { view: 'rh.form_contrat', contractId: contractId }
        }));
    }

    // Print contract
    window.imprimerContrat = function(contractId) {
        // Implement print logic
        alert('Fonctionnalité d\'impression à implémenter');
    }

    // Archive contract
    window.archiverContrat = function(contractId) {
        // Implement archive logic
        alert('Fonctionnalité d\'archivage à implémenter');
    }

    // Filter by contract type
    window.filterByType = function(type) {
        // Implement type filter logic
        console.log('Filtering by type:', type);
    }

    // Filter by status
    window.filterByStatus = function(status) {
        // Implement status filter logic
        console.log('Filtering by status:', status);
    }

    // Filter by department
    window.filterByDepartment = function(department) {
        // Implement department filter logic
        console.log('Filtering by department:', department);
    }

    // Search contracts
    window.searchContracts = function(query) {
        // Implement search logic
        console.log('Searching:', query);
    }
});
</script>
@endpush 