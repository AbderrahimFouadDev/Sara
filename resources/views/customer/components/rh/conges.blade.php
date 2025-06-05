@csrf
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="main-container">
    <div class="header">
        <h2 class="title">
            <i class="fas fa-calendar-alt icon"></i>Gestion des Congés
        </h2>
        <div class="header-buttons">
            <button class="btn btn-primary" onclick="document.dispatchEvent(new CustomEvent('loadView', { detail: { view: 'rh/nouveau_conge' } }))">
                <i class="fas fa-plus"></i>Nouveau Congé
            </button>
            <button class="btn btn-secondary" onclick="exportConges()">
                <i class="fas fa-file-export"></i>Exporter
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="stat-card total-leaves">
            <div class="card-content">
                <div class="card-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="card-info">
                    <h3>Total Congés</h3>
                    <p>{{ $stats['totalConges'] }} jours</p>
                </div>
            </div>
        </div>

        <div class="stat-card current-leaves">
            <div class="card-content">
                <div class="card-icon">
                    <i class="fas fa-umbrella-beach"></i>
                </div>
                <div class="card-info">
                    <h3>En Congé</h3>
                    <p>{{ $stats['enConge'] }} employés</p>
                </div>
            </div>
        </div>

        <div class="stat-card pending-leaves">
            <div class="card-content">
                <div class="card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="card-info">
                    <h3>En Attente</h3>
                    <p>{{ $stats['enAttente'] }} demandes</p>
                </div>
            </div>
        </div>

        <div class="stat-card balance">
            <div class="card-content">
                <div class="card-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <div class="card-info">
                    <h3>Solde Moyen</h3>
                    <p>{{ $soldeMoyen ?? 18 }} jours</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar View -->
   

    <!-- Filter Section -->
    <div class="filter-container">
        <div class="filter-links">
            <a href="#" 
               class="filter-link load-view" 
               data-view="rh/conges"
               data-filter="tous"
               style="--filter-color: #343a40">
                Tous
            </a>
            <a href="#" 
               class="filter-link load-view" 
               data-view="rh/conges"
               data-filter="approved"
               style="--filter-color: #28a745">
                Approuvé
            </a>
            <a href="#" 
               class="filter-link load-view" 
               data-view="rh/conges"
               data-filter="pending"
               style="--filter-color: #ffc107">
                En attente
            </a>
            <a href="#" 
               class="filter-link load-view" 
               data-view="rh/conges"
               data-filter="rejected"
               style="--filter-color: #dc3545">
                Refusé
            </a>
        </div>
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Rechercher un employé...">
        </div>
    </div>

    <!-- Leave Requests Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Type</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Durée</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($conges as $conge)
                <tr>
                    <td>
                        <div class="employee-info">
                            <div class="employee-details">
                                <div class="employee-name">{{ $conge->salarie->nom_complet }}</div>

                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="leave-type {{ $conge->type }}">
                            @switch($conge->type)
                                @case('paid')
                                    Congé payé
                                    @break
                                @case('sick')
                                    Congé maladie
                                    @break
                                @case('unpaid')
                                    Congé sans solde
                                    @break
                                @default
                                    Autre
                            @endswitch
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($conge->date_debut)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($conge->date_fin)->format('d/m/Y') }}</td>
                    <td>{{ $conge->duree }} jours</td>
                    <td>
                        <span class="status-badge {{ $conge->status ?? 'pending' }}">
                            @switch($conge->status)
                                @case('approved')
                                    Approuvé
                                    @break
                                @case('rejected')
                                    Refusé
                                    @break
                                @default
                                    En attente
                            @endswitch
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button onclick="document.dispatchEvent(new CustomEvent('loadView', { detail: { view: 'rh/voir_conge_details', params: { conge_id: {{ $conge->id }} } } }))" class="action-btn view-btn" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($conge->status === 'pending')
                            <form action="{{ route('conges.update-status', $conge->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="action-btn approve-btn" title="Approuver">
                                <i class="fas fa-check"></i>
                            </button>
                            </form>

                            <form action="{{ route('conges.update-status', $conge->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="action-btn reject-btn" title="Refuser">
                                <i class="fas fa-times"></i>
                            </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-calendar empty-icon"></i>
                            <p>Aucune demande de congé</p>
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

.btn-icon {
    padding: 8px;
    background: none;
    color: #4b5563;
}

.btn-icon:hover {
    color: #1f2937;
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
.total-leaves {
    background-color: #eff6ff;
    border: 1px solid #dbeafe;
}
.total-leaves .card-icon { color: #3b82f6; }
.total-leaves .card-info h3 { color: #1e40af; }
.total-leaves .card-info p { color: #3b82f6; }

.current-leaves {
    background-color: #fff7ed;
    border: 1px solid #ffedd5;
}
.current-leaves .card-icon { color: #ea580c; }
.current-leaves .card-info h3 { color: #9a3412; }
.current-leaves .card-info p { color: #ea580c; }

.pending-leaves {
    background-color: #fef2f2;
    border: 1px solid #fee2e2;
}
.pending-leaves .card-icon { color: #dc2626; }
.pending-leaves .card-info h3 { color: #991b1b; }
.pending-leaves .card-info p { color: #dc2626; }

.balance {
    background-color: #f0fdf4;
    border: 1px solid #dcfce7;
}
.balance .card-icon { color: #16a34a; }
.balance .card-info h3 { color: #166534; }
.balance .card-info p { color: #16a34a; }

/* Calendar styles */
.calendar-container {
    background-color: #f9fafb;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 24px;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.calendar-header h3 {
    font-size: 18px;
    font-weight: 500;
    color: #1f2937;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
}

/* Filter Section */
.filter-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    gap: 16px;
}

.filter-links {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.filter-link {
    padding: 6px 12px;
    border-radius: 20px;
    text-decoration: none;
    color: var(--filter-color);
    border: 1px solid var(--filter-color);
    transition: all 0.2s ease;
    cursor: pointer;
}

.filter-link:hover,
.filter-link.active {
    background-color: var(--filter-color);
    color: white;
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

/* Leave type styles */
.leave-type {
    display: inline-flex;
    padding: 4px 8px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 500;
}

.leave-type.paid {
    background-color: #f0fdf4;
    color: #16a34a;
}

.leave-type.sick {
    background-color: #fff7ed;
    color: #ea580c;
}

.leave-type.unpaid {
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

.status-badge.approved {
    background-color: #f0fdf4;
    color: #16a34a;
}

.status-badge.pending {
    background-color: #fff7ed;
    color: #ea580c;
}

.status-badge.rejected {
    background-color: #fef2f2;
    color: #dc2626;
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

.approve-btn { color: #16a34a; }
.approve-btn:hover { color: #059669; transform: scale(1.1); }

.reject-btn { color: #dc2626; }
.reject-btn:hover { color: #b91c1c; transform: scale(1.1); }

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
    // Initialize calendar
    initializeCalendar();

    // Add new leave request
    window.ajouterConge = function() {
        console.log('ajouterConge clicked');
        try {
            document.dispatchEvent(new CustomEvent('loadView', {
                detail: { 
                    view: 'rh/nouveau_conge',
                    params: {}
                }
            }));
            console.log('loadView event dispatched');
        } catch (error) {
            console.error('Error dispatching loadView event:', error);
        }
    }

    // Export leave data
    window.exportConges = function() {
        window.location.href = '/conges/export';
    }

    // View leave details
    window.voirConge = function(leaveId) {
        document.dispatchEvent(new CustomEvent('loadView', {
            detail: { view: 'rh/voir_conge', params: { id: leaveId } }
        }));
    }

    // Add confirmation dialogs to approve/reject forms
    document.querySelectorAll('.action-buttons form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const status = this.querySelector('input[name="status"]').value;
            const message = status === 'approved' ? 
                'Êtes-vous sûr de vouloir approuver cette demande de congé ?' : 
                'Êtes-vous sûr de vouloir refuser cette demande de congé ?';
            
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Calendar navigation
    let currentDate = new Date();
    
    window.previousMonth = function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        updateCalendar();
    }

    window.nextMonth = function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        updateCalendar();
    }

    // Initialize calendar
    function initializeCalendar() {
        updateCalendar();
    }

    async function updateCalendar() {
        const month = currentDate.getMonth() + 1;
        const year = currentDate.getFullYear();
        
        try {
            const response = await fetch(`/conges/calendar?month=${month}&year=${year}`);
            const data = await response.json();
            
            document.getElementById('currentMonth').textContent = 
                currentDate.toLocaleString('fr-FR', { month: 'long', year: 'numeric' });

            const calendar = document.querySelector('.calendar-grid');
            calendar.innerHTML = ''; // Clear existing content

            // Add day headers
            const days = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
            days.forEach(day => {
                const dayHeader = document.createElement('div');
                dayHeader.className = 'calendar-day-header';
                dayHeader.textContent = day;
                calendar.appendChild(dayHeader);
            });

            // Get first day of month and total days
            const firstDay = new Date(year, month - 1, 1).getDay();
            const totalDays = new Date(year, month, 0).getDate();

            // Add empty cells for days before start of month
            for (let i = 0; i < firstDay; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'calendar-day empty';
                calendar.appendChild(emptyDay);
            }

            // Add days of month
            for (let i = 1; i <= totalDays; i++) {
                const dayCell = document.createElement('div');
                dayCell.className = 'calendar-day';
                dayCell.textContent = i;

                // Check if day has leaves
                const hasLeave = data.conges.some(conge => {
                    const start = new Date(conge.date_debut);
                    const end = new Date(conge.date_fin);
                    const current = new Date(year, month - 1, i);
                    return current >= start && current <= end;
                });

                if (hasLeave) {
                    dayCell.classList.add('has-leave');
                }

                // Mark today
                const today = new Date();
                if (today.getDate() === i && 
                    today.getMonth() === month - 1 && 
                    today.getFullYear() === year) {
                    dayCell.classList.add('today');
                }

                calendar.appendChild(dayCell);
            }
        } catch (error) {
            console.error('Error updating calendar:', error);
        }
    }

        // Handle filters
    console.log('Setting up filter handlers');
    document.querySelectorAll('.filter-link.load-view').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Filter clicked:', this.dataset.filter);
            
            // Update active state
            document.querySelectorAll('.filter-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            // Get current search value
            const search = document.getElementById('searchInput')?.value || '';
            
            // Dispatch loadView event
            document.dispatchEvent(new CustomEvent('loadView', {
                detail: {
                    view: this.dataset.view,
                    params: {
                        filter: this.dataset.filter,
                        search: search
                    }
                }
            }));
        });
    });
        // Handle search
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const search = this.value;
            const activeFilter = document.querySelector('.filter-link.active');
            
            console.log('Search triggered:', { 
                search, 
                activeFilter: activeFilter?.dataset.filter 
            });
            
            // Dispatch loadView event with current filter and search
            document.dispatchEvent(new CustomEvent('loadView', {
                detail: {
                    view: 'rh/conges',
                    params: {
                        filter: activeFilter?.dataset.filter || 'tous',
                        search: search
                    }
                }
            }));
        }, 300);
    });

    function updateTable(conges) {
        console.log('Updating table with conges:', conges);
        const tbody = document.querySelector('.data-table tbody');
        if (!tbody) {
            console.error('Table body not found');
            return;
        }

        if (!Array.isArray(conges) || conges.length === 0) {
            console.log('No leaves found');
            tbody.innerHTML = `
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-calendar empty-icon"></i>
                            <p>Aucune demande de congé</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        try {
            tbody.innerHTML = conges.map(conge => {
                if (!conge.salarie) {
                    console.error('Invalid conge data:', conge);
                    return '';
                }

                return `
            <tr>
                <td>
                    <div class="employee-info">
                        <div class="employee-details">
                                    <div class="employee-name">${conge.salarie.nom_complet}</div>
                                    <div class="employee-id">ID: ${conge.salarie.matricule || 'N/A'}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="leave-type ${conge.type}">
                        ${conge.type === 'paid' ? 'Congé payé' : 
                          conge.type === 'sick' ? 'Maladie' : 
                          conge.type === 'unpaid' ? 'Sans solde' : 'Autre'}
                    </span>
                </td>
                <td>${new Date(conge.date_debut).toLocaleDateString('fr-FR')}</td>
                <td>${new Date(conge.date_fin).toLocaleDateString('fr-FR')}</td>
                <td>${conge.duree} jours</td>
                <td>
                    <span class="status-badge ${conge.status}">
                        ${conge.status === 'approved' ? 'Approuvé' : 
                          conge.status === 'pending' ? 'En attente' : 'Refusé'}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view-btn" onclick="voirConge(${conge.id})" title="Voir détails">
                            <i class="fas fa-eye"></i>
                        </button>
                        ${conge.status === 'pending' ? `
                                    <form action="/conges/${conge.id}/update-status" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="action-btn approve-btn" title="Approuver">
                                <i class="fas fa-check"></i>
                            </button>
                                    </form>

                                    <form action="/conges/${conge.id}/update-status" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="action-btn reject-btn" title="Refuser">
                                <i class="fas fa-times"></i>
                            </button>
                                    </form>
                        ` : ''}
                    </div>
                </td>
            </tr>
                `;
            }).join('');
        } catch (error) {
            console.error('Error updating table:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-exclamation-triangle empty-icon"></i>
                            <p>Une erreur est survenue lors de l'affichage des congés</p>
                        </div>
                    </td>
                </tr>
            `;
        }
    }
});
</script>

<style>
/* Additional calendar styles */
.calendar-day-header {
    text-align: center;
    font-weight: 500;
    color: #6b7280;
    padding: 8px;
}

.calendar-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: white;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}

.calendar-day:hover {
    background-color: #eff6ff;
    border-color: #3b82f6;
}

.calendar-day.has-leave {
    background-color: #f0fdf4;
    border-color: #16a34a;
}

.calendar-day.today {
    font-weight: 600;
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px #eff6ff;
}
</style>
@endpush 