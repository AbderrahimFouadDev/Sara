<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="list-header">
    <h2>Fournisseurs</h2>

    <div class="search-section">
        <div class="search-input-group">
            <div class="search-box">
                <input type="text" 
                       id="searchInput" 
                       class="form-control" 
                       placeholder="Rechercher par nom, contact..."
                       style="width: 250px;">
                <button class="search-btn" id="searchBtn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
            <div class="date-search-box">
                <input type="date" 
                       id="dateSearch" 
                       class="form-control"
                       style="width: 150px;">
                <button class="search-btn" id="dateSearchBtn">
                    <i class="fas fa-calendar-search"></i>
                </button>
            </div>

            <button class="btn btn-secondary" id="clearSearch">
                <i class="fas fa-times"></i> Effacer
            </button>
        </div>
    </div>

    <div class="filter-links">
        <a href="#" class="filter-link load-view" data-view="fournisseurs/liste_fournisseurs" data-filter="actif" style="--filter-color: #28a745">Actif</a>
        <a href="#" class="filter-link load-view" data-view="fournisseurs/liste_fournisseurs" data-filter="inactif" style="--filter-color: #6c757d">Inactif</a>
        <a href="#" class="filter-link load-view" data-view="fournisseurs/liste_fournisseurs" data-filter="equilibre" style="--filter-color: #007bff">Équilibré</a>
        <a href="#" class="filter-link load-view" data-view="fournisseurs/liste_fournisseurs" data-filter="deseq" style="--filter-color: #dc3545">Déséquilibré</a>
        <a href="#" class="filter-link load-view" data-view="fournisseurs/liste_fournisseurs" data-filter="tous" style="--filter-color: #343a40">Tous</a>
    </div>

    <div class="header-actions">
        <a href="{{ route('fournisseurs.export', ['format' => 'csv']) }}" class="btn-export-custom">
            <i class="fas fa-file-export"></i>
            Exporter en CSV
        </a>
        <button class="btn btn-new load-view" data-view="fournisseurs/ajouter_fournisseur">
            <i class="fas fa-plus-circle"></i>
            Nouveau
        </button>
    </div>
</div>

<div class="table-responsive">
    <table class="fournisseur-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Fournisseur</th>
                <th>Contact</th>
                <th>Balance</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fournisseurs as $fournisseur)
            <tr>
                <td>{{ $fournisseur->created_at ? $fournisseur->created_at->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $fournisseur->fournisseur_name }}</td>
                <td>
                    @if($fournisseur->email)
                        <span class="contact-email">{{ $fournisseur->email }}</span><br>
                    @endif
                    @if($fournisseur->telephone)
                        <span class="contact-phone">{{ $fournisseur->phone_code }} {{ $fournisseur->telephone }}</span>
                    @endif
                </td>
                <td>{{ number_format($fournisseur->debut_balance_fournisseur, 2) }} MAD</td>
                <td class="text-center">
                    @if($fournisseur->fournisseur_actif)
                        <i class="fas fa-check-circle text-success" title="Actif"></i>
                    @else
                        <i class="fas fa-times-circle text-danger" title="Inactif"></i>
                    @endif
                </td>
                <td class="actions-cell">
                    <div class="action-buttons">
                        <button class="action-btn view-btn load-view" data-view="fournisseurs/voir_fournisseur" data-id="{{ $fournisseur->id }}" title="Voir" style="color: #8B5CF6;">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn edit-btn load-view" data-view="fournisseurs/edit_fournisseur" data-id="{{ $fournisseur->id }}" title="Éditer">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn order-btn load-view" data-view="factures/creer_facture" data-id="{{ $fournisseur->id }}" title="Créer une facture">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </button>
                        <button class="action-btn delete-btn" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">
                    <div class="empty-state">
                        <i class="fas fa-truck"></i>
                        <p>Aucun fournisseur trouvé</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
.search-section {
    margin: 1rem 0;
}

.search-input-group {
    display: flex;
    gap: 15px;
    align-items: center;
    margin-bottom: 1rem;
}

.search-box, .date-search-box {
    position: relative;
    display: flex;
    align-items: center;
}

.search-box input, .date-search-box input {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.search-box input:focus, .date-search-box input:focus {
    border-color: #40E0D0;
    outline: none;
    box-shadow: 0 0 0 2px rgba(64, 224, 208, 0.2);
}

.search-btn {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 5px;
    transition: color 0.3s ease;
}

.search-btn:hover {
    color: #40E0D0;
}

#clearSearch {
    padding: 8px 16px;
    background-color: #6c757d;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: background-color 0.3s ease;
}

#clearSearch:hover {
    background-color: #5a6268;
}

.highlight {
    background-color: rgba(64, 224, 208, 0.1);
    transition: background-color 0.3s ease;
}

.btn-export-custom {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: #4f46e5;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    font-weight: 500;
}

.btn-export-custom:hover {
    background-color: #4338ca;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    color: white;
    text-decoration: none;
}

.list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1rem;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.filter-links {
    display: flex;
    gap: 1rem;
}

.filter-link {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    color: var(--filter-color);
    border: 1px solid var(--filter-color);
    text-decoration: none;
    transition: all 0.3s ease;
}

.filter-link:hover {
    background: var(--filter-color);
    color: white;
}

.filter-link.active {
    background: var(--filter-color);
    color: white;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn-new {
    padding: 8px 16px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn-new:hover {
    background-color: #218838;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.fournisseur-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.fournisseur-table th,
.fournisseur-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.fournisseur-table th {
    background: #f8f9fa;
    font-weight: 600;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    padding: 0.25rem 0.5rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.edit-btn { color: #007bff; }
.order-btn { color: #28a745; }
.invoice-btn { color: #fd7e14; }
.bon-btn { color: #6f42c1; }
.delete-btn { color: #dc3545; }

.action-btn:hover {
    background: rgba(0,0,0,0.1);
}

.empty-state {
    padding: 2rem;
    text-align: center;
    color: #6c757d;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.contact-email {
    color: #007bff;
}

.contact-phone {
    color: #6c757d;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View and edit buttons click handler
    document.querySelectorAll('.load-view').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const view = this.dataset.view;
            const id = this.dataset.id;
            const filter = this.dataset.filter;
            
            let url = `/dashboard/component/${view}`;
            const params = new URLSearchParams();
            
            if (id) params.append('id', id);
            if (filter) params.append('filter', filter);
            
            if (params.toString()) {
                url += `?${params.toString()}`;
            }
            
            // Load the view using the loadComponent route
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const dynamicContent = document.getElementById('dynamic-content');
                if (dynamicContent) {
                    document.getElementById('default-dashboard').style.display = 'none';
                    dynamicContent.style.display = 'block';
                    dynamicContent.innerHTML = html;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors du chargement de la vue');
            });
        });
    });

    // Add active class to current filter
    const currentFilter = new URLSearchParams(window.location.search).get('filter') || 'tous';
    document.querySelectorAll('.filter-link').forEach(link => {
        if (link.dataset.filter === currentFilter) {
            link.classList.add('active');
        }
    });

    // Export button
    const exportBtn = document.querySelector('.btn-export-custom');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            // Add your export logic here
            alert('Fonctionnalité d\'export à implémenter');
        });
    }

    // New button
    const newBtn = document.querySelector('.btn-new');
    if (newBtn) {
        newBtn.addEventListener('click', function() {
            // Navigate to ajouter_fournisseur
            const event = new CustomEvent('loadView', {
                detail: { view: 'ajouter_fournisseur' }
            });
            document.dispatchEvent(event);
        });
    }

    // Action buttons
    document.querySelectorAll('.action-btn:not(.view-btn)').forEach(btn => {
        btn.addEventListener('click', function() {
            // Add your action logic here
            alert('Fonctionnalité à implémenter');
        });
    });
});
</script>
@endpush 