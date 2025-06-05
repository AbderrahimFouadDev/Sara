<div class="main-container">
    <div class="header">
        <h2 class="title">
            <i class="fas fa-file-invoice-dollar icon"></i>Bulletins de Paie
        </h2>
        <div class="header-buttons">
            <button class="btn btn-primary" onclick="genererBulletins()">
                <i class="fas fa-plus"></i>Générer les Bulletins
            </button>
            <button class="btn btn-secondary" onclick="exportBulletins()">
                <i class="fas fa-file-export"></i>Export CNSS
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="stat-card total-payslips">
            <div class="card-content">
                <div class="card-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="card-info">
                    <h3>Total Bulletins</h3>
                    <p>{{ $totalBulletins ?? 45 }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card total-salary">
            <div class="card-content">
                <div class="card-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="card-info">
                    <h3>Masse Salariale</h3>
                    <p>{{ number_format($totalSalary ?? 450000, 2) }} DH</p>
                </div>
            </div>
        </div>

        <div class="stat-card total-tax">
            <div class="card-content">
                <div class="card-icon">
                    <i class="fas fa-percent"></i>
                </div>
                <div class="card-info">
                    <h3>Total IR</h3>
                    <p>{{ number_format($totalTax ?? 45000, 2) }} DH</p>
                </div>
            </div>
        </div>

        <div class="stat-card total-cnss">
            <div class="card-content">
                <div class="card-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="card-info">
                    <h3>Total CNSS</h3>
                    <p>{{ number_format($totalCNSS ?? 22500, 2) }} DH</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-container">
        <div class="period-selector">
            <label>Période :</label>
            <select id="month" onchange="filterBulletins()">
                <option value="1">Janvier</option>
                <option value="2">Février</option>
                <option value="3">Mars</option>
                <option value="4">Avril</option>
                <option value="5">Mai</option>
                <option value="6">Juin</option>
                <option value="7">Juillet</option>
                <option value="8">Août</option>
                <option value="9">Septembre</option>
                <option value="10">Octobre</option>
                <option value="11">Novembre</option>
                <option value="12">Décembre</option>
            </select>
            <select id="year" onchange="filterBulletins()">
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
            </select>
        </div>
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Rechercher un salarié..." onkeyup="searchBulletins(this.value)">
        </div>
    </div>

    <!-- Payslips Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Salarié</th>
                    <th>Période</th>
                    <th>Salaire Brut</th>
                    <th>IR</th>
                    <th>CNSS</th>
                    <th>Net à Payer</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bulletins ?? [] as $bulletin)
                <tr>
                    <td>
                        <div class="employee-info">
                            <div class="employee-image">
                                <img src="{{ $bulletin->employee->photo ?? 'https://via.placeholder.com/32' }}" 
                                     alt="Photo employé">
                            </div>
                            <div class="employee-details">
                                <div class="employee-name">{{ $bulletin->employee->nom ?? 'John Doe' }}</div>
                                <div class="employee-id">ID: {{ $bulletin->employee->matricule ?? 'EMP001' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $bulletin->periode ?? 'Janvier 2024' }}</td>
                    <td>{{ number_format($bulletin->salaire_brut ?? 10000, 2) }} DH</td>
                    <td>{{ number_format($bulletin->ir ?? 1000, 2) }} DH</td>
                    <td>{{ number_format($bulletin->cnss ?? 500, 2) }} DH</td>
                    <td class="net-salary">{{ number_format($bulletin->net ?? 8500, 2) }} DH</td>
                    <td>
                        <span class="status-badge {{ $bulletin->status ?? 'generated' }}">
                            {{ $bulletin->status == 'generated' ? 'Généré' : 
                               ($bulletin->status == 'sent' ? 'Envoyé' : 'En attente') }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn view-btn" onclick="voirBulletin('{{ $bulletin->id ?? 1 }}')" title="Voir">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn download-btn" onclick="telechargerBulletin('{{ $bulletin->id ?? 1 }}')" title="Télécharger">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="action-btn send-btn" onclick="envoyerBulletin('{{ $bulletin->id ?? 1 }}')" title="Envoyer">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-file-invoice empty-icon"></i>
                            <p>Aucun bulletin de paie pour cette période</p>
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
.total-payslips {
    background-color: #eff6ff;
    border: 1px solid #dbeafe;
}
.total-payslips .card-icon { color: #3b82f6; }
.total-payslips .card-info h3 { color: #1e40af; }
.total-payslips .card-info p { color: #3b82f6; }

.total-salary {
    background-color: #f0fdf4;
    border: 1px solid #dcfce7;
}
.total-salary .card-icon { color: #16a34a; }
.total-salary .card-info h3 { color: #166534; }
.total-salary .card-info p { color: #16a34a; }

.total-tax {
    background-color: #fff7ed;
    border: 1px solid #ffedd5;
}
.total-tax .card-icon { color: #ea580c; }
.total-tax .card-info h3 { color: #9a3412; }
.total-tax .card-info p { color: #ea580c; }

.total-cnss {
    background-color: #fdf4ff;
    border: 1px solid #fae8ff;
}
.total-cnss .card-icon { color: #c026d3; }
.total-cnss .card-info h3 { color: #86198f; }
.total-cnss .card-info p { color: #c026d3; }

/* Filter Section */
.filter-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    gap: 16px;
}

.period-selector {
    display: flex;
    align-items: center;
    gap: 8px;
}

.period-selector label {
    font-weight: 500;
    color: #374151;
}

.period-selector select {
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

/* Status badge styles */
.status-badge {
    display: inline-flex;
    padding: 4px 8px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.generated {
    background-color: #f0fdf4;
    color: #16a34a;
}

.status-badge.sent {
    background-color: #eff6ff;
    color: #3b82f6;
}

.status-badge.pending {
    background-color: #fff7ed;
    color: #ea580c;
}

/* Net salary highlight */
.net-salary {
    font-weight: 600;
    color: #16a34a;
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

.download-btn { color: #10b981; }
.download-btn:hover { color: #059669; transform: scale(1.1); }

.send-btn { color: #f59e0b; }
.send-btn:hover { color: #d97706; transform: scale(1.1); }

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

    .period-selector {
        width: 100%;
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
    // Set current month and year
    const now = new Date();
    document.getElementById('month').value = now.getMonth() + 1;
    document.getElementById('year').value = now.getFullYear();

    // Generate payslips
    window.genererBulletins = function() {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;
        document.dispatchEvent(new CustomEvent('loadView', {
            detail: { 
                view: 'rh.generer_bulletins',
                month: month,
                year: year
            }
        }));
    }

    // Export payslips
    window.exportBulletins = function() {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;
        // Implement export logic
        alert('Fonctionnalité d\'export à implémenter');
    }

    // View payslip
    window.voirBulletin = function(bulletinId) {
        document.dispatchEvent(new CustomEvent('loadView', {
            detail: { 
                view: 'rh.voir_bulletin',
                bulletinId: bulletinId
            }
        }));
    }

    // Download payslip
    window.telechargerBulletin = function(bulletinId) {
        // Implement download logic
        alert('Fonctionnalité de téléchargement à implémenter');
    }

    // Send payslip
    window.envoyerBulletin = function(bulletinId) {
        // Implement send logic
        alert('Fonctionnalité d\'envoi à implémenter');
    }

    // Filter payslips
    window.filterBulletins = function() {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;
        // Implement filter logic
        console.log('Filtering for:', month, year);
    }

    // Search payslips
    window.searchBulletins = function(query) {
        // Implement search logic
        console.log('Searching:', query);
    }
});
</script>
@endpush 