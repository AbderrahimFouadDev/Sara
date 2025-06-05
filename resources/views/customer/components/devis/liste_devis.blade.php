<div id="devisListContainer">

<div class="list-header">
    <div class="filter-links">
        <a href="#" class="filter-link load-view" data-view="devis/liste_devis" data-filter="all" style="--filter-color: #343a40">Tous</a>
        <a href="#" class="filter-link load-view" data-view="devis/liste_devis" data-filter="en attente" style="--filter-color: #ffd700">En attente</a>
        <a href="#" class="filter-link load-view" data-view="devis/liste_devis" data-filter="accepte" style="--filter-color: #28a745">Acceptés</a>
        <a href="#" class="filter-link load-view" data-view="devis/liste_devis" data-filter="rejete" style="--filter-color: #dc3545">Rejetés</a>
        <a href="#" class="filter-link load-view" data-view="devis/liste_devis" data-filter="expire" style="--filter-color: #6c757d">Expirés</a>
    </div>
    <div class="header-actions">
        <a href="{{ route('devis.export', ['format' => 'csv']) }}" class="btn btn-export">
            <i class="fas fa-file-export"></i> Exporter en CSV
        </a>
        <button class="btn btn-new load-view" data-view="devis/creer_devis">
            <i class="fas fa-plus"></i> Nouveau Devis
        </button>
    </div>
</div>

<div class="table-responsive">
    <table class="devis-table">
        <thead>
            <tr>
                <th>N° Devis</th>
                <th>Date</th>
                <th>Client</th>
                <th>Montant</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($devis as $devisItem)
          <tr>
            <td>{{ $devisItem->numero }}</td>
            <td>{{ $devisItem->date_devis->format('Y-m-d') }}</td>
            <td>{{ $devisItem->client_name }}</td>
            <td>{{ number_format($devisItem->montant_ttc, 2) }} MAD</td>
            <td>
              <span class="status-badge status-{{ $devisItem->statut }}">
                {{ ucfirst($devisItem->statut) }}
              </span>
            </td>
            <td class="actions-cell">
              <div class="action-buttons">
                <button type="button" class="action-btn view-btn load-view" title="Voir le devis" data-view="devis/voir_devis" data-id="{{ $devisItem->id }}" >
                  <i class="fas fa-eye"></i>
                </button>
                <button type="button" class="action-btn edit-btn load-view" title="Modifier le devis" data-view="devis/edit_devis" data-id="{{ $devisItem->id }}">
                  <i class="fas fa-edit"></i>
                </button>
                <a href="{{ route('devis.print', $devisItem) }}" target="_blank" class="action-btn print-btn" title="Imprimer le devis">
                  <i class="fas fa-print"></i>
                </a>
                <button class="action-btn delete-btn" title="Supprimer" data-id="{{ $devisItem->id }}">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center">
              <div class="empty-state">
                <i class="fas fa-file-invoice-dollar"></i>
                <p>Aucun devis trouvé</p>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
</div>
</div>

<style>
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

.devis-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.devis-table th,
.devis-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.devis-table th {
    background: #f8f9fa;
    font-weight: 600;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
    font-size: 0.875rem;
}

.status-en-attente { background: #ffd700; color: #000; }
.status-accepte { background: #28a745; color: white; }
.status-rejete { background: #dc3545; color: white; }
.status-expire { background: #6c757d; color: white; }

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

.empty-state {
    padding: 2rem;
    text-align: center;
    color: #6c757d;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
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
    
    .devis-table {
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add active class to current filter
    const currentFilter = new URLSearchParams(window.location.search).get('filter') || 'all';
    document.querySelectorAll('.filter-link').forEach(link => {
        if (link.dataset.filter === currentFilter) {
            link.classList.add('active');
        }
    });

    // Delete button handlers
    document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const devisId = this.dataset.id;
                if (confirm('Êtes-vous sûr de vouloir supprimer ce devis ?')) {
                    try {
                        const response = await fetch(`/devis/${devisId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (response.ok) {
                        // Refresh the current view
                        document.dispatchEvent(new CustomEvent('loadView', {
                            detail: { 
                                view: 'devis/liste_devis',
                                params: { filter: currentFilter }
                            }
                        }));
                        } else {
                            const data = await response.json();
                            alert(data.message || 'Erreur lors de la suppression');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Une erreur est survenue lors de la suppression');
                    }
                }
            });
        });
});
</script>
@endpush 