<div id="factureListContainer">

<div class="list-header">
    <div class="filter-links">
        <a href="#" class="filter-link load-view" data-view="factures/liste_factures" data-filter="all" style="--filter-color: #343a40">Toutes</a>
        <a href="#" class="filter-link load-view" data-view="factures/liste_factures" data-filter="pending" style="--filter-color: #ffd700">En attente</a>
        <a href="#" class="filter-link load-view" data-view="factures/liste_factures" data-filter="paid" style="--filter-color: #28a745">Payées</a>
        <a href="#" class="filter-link load-view" data-view="factures/liste_factures" data-filter="overdue" style="--filter-color: #dc3545">En retard</a>
    </div>
    <div class="header-actions">
        <a href="{{ route('factures.export', ['format' => 'csv']) }}" class="btn btn-export">
            <i class="fas fa-file-export"></i> Exporter en CSV
        </a>
        <button class="btn btn-new load-view" data-view="factures/creer_facture">
            <i class="fas fa-plus"></i> Nouvelle Facture
        </button>
    </div>
</div>

<div class="table-responsive">
    <table class="facture-table">
        <thead>
            <tr>
                <th>N° Facture</th>
                <th>Date</th>
                <th>Fournisseur</th>
                <th>Montant</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($factures as $facture)
          <tr>
            <td>{{ $facture->numero }}</td>
            <td>{{ $facture->date_facture->format('Y-m-d') }}</td>
            <td>{{ $facture->fournisseur_name }}</td>
            <td>{{ number_format($facture->montant_ttc ?? 0, 2) }} MAD</td>
            <td>
              <span class="status-badge status-{{ $facture->statut }}">
                {{ ucfirst($facture->statut) }}
              </span>
            </td>
            <td class="actions-cell">
              <div class="action-buttons">
                <button class="action-btn view-btn load-view" data-view="factures/voir_facture" title="Voir la facture" data-id="{{ $facture->id }}">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="action-btn edit-btn load-view" title="Modifier la facture" data-view="factures/edit_facture" data-id="{{ $facture->id }}">
                  <i class="fas fa-edit"></i>
                </button>
                <a href="{{ route('factures.print', $facture) }}" target="_blank" class="action-btn print-btn" title="Imprimer la facture">
                  <i class="fas fa-print"></i>
                </a>
                <form method="POST" action="{{ route('factures.destroy', $facture) }}" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette facture?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="action-btn delete-btn" title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center">
              <div class="empty-state">
                <i class="fas fa-file-invoice-dollar"></i>
                <p>Aucune facture trouvée</p>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
</div>
</div>


<style>
/* Similar styling as liste_devis.blade.php */
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
    text-decoration: none;
    color: var(--filter-color);
    border: 1px solid var(--filter-color);
    transition: all 0.2s ease;
}

.filter-link:hover {
    background-color: var(--filter-color);
    color: white;
}

.filter-link.active {
    background-color: var(--filter-color);
    color: white;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-export {
    background: linear-gradient(45deg, #6c757d, #495057);
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-export:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    background: linear-gradient(45deg, #495057, #343a40);
    color: white;
    text-decoration: none;
}

.btn-export i {
    font-size: 1.1rem;
    transition: transform 0.3s ease;
}

.btn-export:hover i {
    transform: translateX(-2px);
}

.btn-new {
    background: linear-gradient(45deg, #40E0D0, #3CCEA0);
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    cursor: pointer;
}

.btn-new:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    background: linear-gradient(45deg, #3CCEA0, #36BA90);
}

.btn-new i {
    font-size: 1.1rem;
    transition: transform 0.3s ease;
}

.btn-new:hover i {
    transform: rotate(90deg);
}

.facture-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.facture-table th,
.facture-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.facture-table th {
    background: #f8f9fa;
    font-weight: 600;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
    font-size: 0.875rem;
}

.status-pending { background: #ffd700; color: #000; }
.status-paid { background: #28a745; color: white; }
.status-overdue { background: #dc3545; color: white; }

.actions-cell {
    white-space: nowrap;
    width: 1%;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-start;
    align-items: center;
}

.action-btn {
    background: none;
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-btn:hover {
    background: rgba(0, 0, 0, 0.05);
}

.view-btn { color: #17a2b8; }
.view-btn:hover { color: #138496; }

.edit-btn { color: #007bff; }
.edit-btn:hover { color: #0056b3; }

.print-btn { color: #6c757d; }
.print-btn:hover { color: #545b62; }

.delete-btn { color: #dc3545; }
.delete-btn:hover { color: #c82333; }

.action-btn i {
    font-size: 1rem;
}

.action-btn:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

/* Tooltip styles */
.action-btn[title] {
    position: relative;
}

.action-btn[title]:hover:after {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    white-space: nowrap;
    z-index: 1000;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .list-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .filter-links {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .header-actions {
        width: 100%;
        justify-content: center;
    }
    
    .facture-table {
        font-size: 0.875rem;
    }
    
    .actions-cell {
        width: auto;
    }
    
    .action-buttons {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .action-btn {
        padding: 0.35rem;
    }
    
    .action-btn i {
        font-size: 0.875rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add active class to current filter
    const currentFilter = new URLSearchParams(window.location.search).get('filter') || 'all';
    document.querySelectorAll('.filter-link').forEach(link => {
        if (link.dataset.filter === currentFilter) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
    
    // Handle filter clicks
    document.querySelectorAll('.filter-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const filter = this.dataset.filter;
            const view = this.dataset.view;
            
            // Remove active class from all links
            document.querySelectorAll('.filter-link').forEach(l => {
                l.classList.remove('active');
            });
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Trigger the loadView event with the filter parameter
            document.dispatchEvent(new CustomEvent('loadView', {
                detail: {
                    view: view,
                    params: { filter: filter }
                }
            }));
        });
    });
});
</script>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('factureListContainer');
    const newBtn = document.querySelector('.btn-new');

    // 1) Fetch & render the list
    async function loadFactureList(filter = 'all') {
        try {
            const response = await fetch(`/factures/component/liste_factures?filter=${filter}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const html = await response.text();
            container.innerHTML = html;
            attachHandlers();
        } catch (err) {
            console.error('Erreur chargement factures:', err);
        }
    }

    // 2) (Re-)bind events inside the list container
    function attachHandlers() {
        // Filter links inside the newly injected HTML
        container.querySelectorAll('.filter-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                container.querySelectorAll('.filter-link')
                         .forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                loadFactureList(this.dataset.filter);
            });
        });

        // Delete functionality now handled by Laravel form
    }

    // 3) "Nouvelle facture" button (outside of the container)
    if (newBtn) {
        newBtn.addEventListener('click', function() {
            document.dispatchEvent(new CustomEvent('loadView', {
                detail: { view: 'creer_facture' }
            }));
        });
    }

    // 4) Initial load with filter "all"
    loadFactureList();
});
</script>

@endpush 