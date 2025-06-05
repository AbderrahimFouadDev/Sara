{{-- resources/views/customer/components/rh/salaries.blade.php --}}
@php
    $salaries = $salaries ?? collect();
@endphp

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="main-container">
    <div class="header">
        <h2 class="title">
            <i class="fas fa-users icon"></i>Gestion des Salariés
        </h2>
        <div class="header-buttons">
            <button class="btn btn-primary" onclick="ajouterSalarie()">
                <i class="fas fa-plus"></i>Nouveau Salarié
            </button>
            <a href="{{ route('rh.salaries.export') }}" class="btn btn-secondary">
                <i class="fas fa-file-export"></i>Exporter
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-cards">
        <div class="stat-card total-employees">
            <div class="card-content">
                <i class="fas fa-users card-icon"></i>
                <div class="card-info">
                    <h3>Total Salariés</h3>
                    <p>{{ isset($totalEmployees) ? $totalEmployees : $salaries->count() }}</p>
                </div>
            </div>
        </div>
        <div class="stat-card active-employees">
            <div class="card-content">
                <i class="fas fa-user-check card-icon"></i>
                <div class="card-info">
                    <h3>Salariés Actifs</h3>
                    <p>{{ isset($activeEmployees) ? $activeEmployees : $salaries->where('statut', 'actif')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="stat-card on-leave">
            <div class="card-content">
                <i class="fas fa-calendar-alt card-icon"></i>
                <div class="card-info">
                    <h3>En Congé</h3>
                    <p>{{ isset($onLeave) ? $onLeave : $salaries->where('statut', 'congé')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="stat-card quit">
            <div class="card-content">
                <i class="fas fa-user-times card-icon"></i>
                <div class="card-info">
                    <h3>Salariés Quittés</h3>
                    <p>{{ isset($quitEmployees) ? $quitEmployees : $salaries->where('statut', 'quitté')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="stat-card payroll">
            <div class="card-content">
                <i class="fas fa-money-bill-wave card-icon"></i>
                <div class="card-info">
                    <h3>Masse Salariale</h3>
                    <p>{{ isset($totalPayroll) ? number_format($totalPayroll, 2) : number_format($salaries->sum('salaire_base'), 2) }} DH</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="search-filter-container">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Rechercher un salarié..." onkeyup="searchEmployees(this.value)">
        </div>
        <div class="filters">
            <select onchange="filterByStatus(this.value)">
                <option value="">Tous les statuts</option>
                <option value="actif">Actif</option>
                <option value="congé">En congé</option>
                <option value="quitté">Quitté</option>
            </select>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom Complet</th>
                    <th>CIN</th>
                    <th>Poste</th>
                    <th>Département</th>
                    <th>Statut</th>
                    <th>Date d'embauche</th>
                    <th>Salaire Base</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="employeesTableBody">
                @forelse($salaries as $salarie)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $salarie->nom_complet }}</td>
                        <td>{{ $salarie->cin }}</td>
                        <td>{{ $salarie->poste }}</td>
                        <td>{{ $salarie->departement }}</td>
                        <td>
                            @if ($salarie->statut === 'actif')
                                <span class="status-badge active">Actif</span>
                            @elseif ($salarie->statut === 'congé')
                                <span class="status-badge conge">En congé</span>
                            @else
                                <span class="status-badge inactive">{{ ucfirst($salarie->statut) }}</span>
                            @endif
                        </td>
                        <td>{{ $salarie->date_embauche ? \Carbon\Carbon::parse($salarie->date_embauche)->format('d/m/Y') : '—' }}</td>
                        <td>{{ number_format($salarie->salaire_base, 2) }} DH</td>
                        <td class="actions-cell">
                            <div class="action-buttons">
                                <button class="action-btn view-btn load-view" data-view="rh/voir_salarie" data-employee-id="{{ $salarie->id }}" title="Voir le salarié">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn edit-btn load-view" data-view="rh/edit_salarie" data-employee-id="{{ $salarie->id }}" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            <div class="empty-state">
                                <i class="fas fa-box-open"></i>
                                <p>Aucun salarié trouvé</p>
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
.stats-cards {
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
.total-employees {
    background-color: #eff6ff;
    border: 1px solid #dbeafe;
}
.total-employees .card-icon { color: #3b82f6; }
.total-employees .card-info h3 { color: #1e40af; }
.total-employees .card-info p { color: #3b82f6; }

.active-employees {
    background-color: #f0fdf4;
    border: 1px solid #dcfce7;
}
.active-employees .card-icon { color: #16a34a; }
.active-employees .card-info h3 { color: #166534; }
.active-employees .card-info p { color: #16a34a; }

.on-leave {
    background-color: #fff7ed;
    border: 1px solid #ffedd5;
}
.on-leave .card-icon { color: #ea580c; }
.on-leave .card-info h3 { color: #9a3412; }
.on-leave .card-info p { color: #ea580c; }

.quit {
    background-color: #fef2f2;
    border: 1px solid #fee2e2;
}
.quit .card-icon { color: #dc2626; }
.quit .card-info h3 { color: #991b1b; }
.quit .card-info p { color: #dc2626; }

.payroll {
    background-color: #fdf4ff;
    border: 1px solid #fae8ff;
}
.payroll .card-icon { color: #c026d3; }
.payroll .card-info h3 { color: #86198f; }
.payroll .card-info p { color: #c026d3; }

/* Search and Filter Section */
.search-filter-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    gap: 16px;
}

.search-box {
    flex: 1;
    position: relative;
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

.status-badge.active {
    background-color: #f0fdf4;
    color: #16a34a;
}

.status-badge.conge {
    background-color: #fff7ed;
    color: #ea580c;
}

.status-badge.inactive {
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

.payslip-btn { color: #10b981; }
.payslip-btn:hover { color: #059669; transform: scale(1.1); }

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

    .search-filter-container {
        flex-direction: column;
    }

    .filters {
        width: 100%;
        flex-direction: column;
    }

    .action-buttons {
        flex-direction: column;
    }
}
</style>

@push('scripts')
<script>
let currentQuery = '';
let currentDept = '';
let currentStatus = '';

// Add click handler for view buttons
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for view buttons
    document.querySelectorAll('.load-view').forEach(button => {
        button.addEventListener('click', function() {
            const view = this.dataset.view;
            const employeeId = this.dataset.employeeId;
            
            document.dispatchEvent(new CustomEvent('loadView', {
                detail: { 
                    view: view,
                    employeeId: employeeId
                }
            }));
        });
    });

    // Function to handle employee search
    function searchEmployees(query) {
        fetch(`/rh/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                renderEmployees(data.employees);
            })
            .catch(error => console.error('Error:', error));
    }

    // Function to handle department filter
    function filterByDepartment(department) {
        fetch(`/rh/search?department=${encodeURIComponent(department)}`)
            .then(response => response.json())
            .then(data => {
                renderEmployees(data.employees);
            })
            .catch(error => console.error('Error:', error));
    }

    // Function to handle status filter
    function filterByStatus(status) {
        fetch(`/rh/search?status=${encodeURIComponent(status)}`)
            .then(response => response.json())
            .then(data => {
                renderEmployees(data.employees);
            })
            .catch(error => console.error('Error:', error));
    }

    // Function to generate payslip
    function genererBulletin(employeeId) {
        document.dispatchEvent(new CustomEvent('loadView', {
            detail: { 
                view: 'rh/bulletin_paie',
                employeeId: employeeId
            }
        }));
    }

    // Function to add new employee
    function ajouterSalarie() {
        document.dispatchEvent(new CustomEvent('loadView', {
            detail: { view: 'rh/ajouter_salarie' }
        }));
    }

    // Function to modify employee
    function modifierSalarie(employeeId) {
        document.dispatchEvent(new CustomEvent('loadView', {
            detail: { 
                view: 'rh/edit_salarie',
                employeeId: employeeId
            }
        }));
    }

    // Make functions available globally
    window.searchEmployees = searchEmployees;
    window.filterByDepartment = filterByDepartment;
    window.filterByStatus = filterByStatus;
    window.genererBulletin = genererBulletin;
    window.ajouterSalarie = ajouterSalarie;
    window.modifierSalarie = modifierSalarie;
});

async function loadEmployees() {
    const tbody = document.getElementById('employeesTableBody');
    tbody.innerHTML = `
        <tr><td colspan="9" class="text-center">
            <i class="fas fa-circle-notch fa-spin"></i> Chargement...
        </td></tr>
    `;
    const params = new URLSearchParams();
    if (currentQuery) params.append('q', currentQuery);
    if (currentDept) params.append('department', currentDept);
    if (currentStatus) params.append('status', currentStatus);

    try {
        const res = await fetch(`/rh/salaries/search?${params.toString()}`, {
            headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error('Erreur chargement');
        const json = await res.json();
        renderEmployees(json.employees);
        updateStats(json.employees);
    } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="9" class="text-center text-danger">Erreur lors du chargement.</td></tr>`;
    }
}

function updateStats(employees) {
    const totalEmployees = employees.length;
    const activeEmployees = employees.filter(e => e.statut === 'actif').length;
    const onLeave = employees.filter(e => e.statut === 'congé').length;
    const quitEmployees = employees.filter(e => e.statut === 'quitté').length;
    const totalPayroll = employees.reduce((sum, e) => sum + parseFloat(e.salaire_base), 0);

    document.querySelector('.total-employees .card-info p').textContent = totalEmployees;
    document.querySelector('.active-employees .card-info p').textContent = activeEmployees;
    document.querySelector('.on-leave .card-info p').textContent = onLeave;
    document.querySelector('.quit .card-info p').textContent = quitEmployees;
    document.querySelector('.payroll .card-info p').textContent = `${totalPayroll.toFixed(2)} DH`;
}

function renderEmployees(employees) {
    const tbody = document.getElementById('employeesTableBody');
    if (!employees.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center">
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <p>Aucun salarié trouvé</p>
                    </div>
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = '';
    employees.forEach((emp, index) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${index + 1}</td>
            <td>${emp.nom_complet}</td>
            <td>${emp.cin}</td>
            <td>${emp.poste}</td>
            <td>${emp.departement}</td>
            <td>
                <span class="status-badge ${emp.statut}">
                    ${emp.statut === 'actif' ? 'Actif' : emp.statut === 'congé' ? 'En congé' : 'Quitté'}
                </span>
            </td>
            <td>${new Date(emp.date_embauche).toLocaleDateString()}</td>
            <td>${Number(emp.salaire_base).toFixed(2)} DH</td>
            <td class="actions-cell">
                <div class="action-buttons">
                    <button class="action-btn view-btn load-view" data-view="rh/voir_salarie" data-employee-id="${emp.id}" title="Voir le salarié">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn edit-btn load-view" data-view="rh/edit_salarie" data-employee-id="${emp.id}" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn payslip-btn" title="Bulletin de paie" onclick="genererBulletin(${emp.id})">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
}
</script>
@endpush

