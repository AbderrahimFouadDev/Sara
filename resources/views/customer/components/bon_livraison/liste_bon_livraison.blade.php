<div id="bonLivraisonListContainer">

  <div class="list-header">
    <div class="filter-links">
      <a href="#" class="filter-link active" data-filter="all">Tous</a>
      <a href="#" class="filter-link" data-filter="pending">En attente</a>
      <a href="#" class="filter-link" data-filter="delivered">Livrés</a>
      <a href="#" class="filter-link" data-filter="cancelled">Annulés</a>
    </div>
    <div class="header-actions">
      <a href="{{ route('bons_livraison.export', ['format' => 'csv']) }}" class="btn btn-export">
        <i class="fas fa-file-export"></i> Exporter en CSV
      </a>
      <button class="btn btn-new">
        <i class="fas fa-plus"></i> Nouveau Bon de Livraison
      </button>
    </div>
  </div>

  <div class="table-responsive">
    <table class="bon-livraison-table">
      <thead>
        <tr>
          <th>N° BL</th>
          <th>Date</th>
          <th>Client</th>
          <th>Montant</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($bons as $bl)
          <tr>
            <td>{{ $bl->numero_bl }}</td>
            <td>{{ $bl->date_livraison->format('Y-m-d') }}</td>
            <td>{{ $bl->client_name }}</td>
            <td>{{ number_format($bl->montant ?? 0, 2) }} MAD</td>
            <td>
              <span class="status-badge status-{{ $bl->etat }}">
                {{ ucfirst($bl->etat) }}
              </span>
            </td>
            <td class="actions-cell">
              <div class="action-buttons">
                <button class="action-btn view-btn load-view" data-view="bon_livraison/voir_bon_livraison" title="Voir le bon" data-id="{{ $bl->id }}">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="action-btn edit-btn load-view" title="Modifier" data-view="bon_livraison/edit_bon_livraison" data-id="{{ $bl->id }}">
                  <i class="fas fa-edit"></i>
                </button>
                <a href="{{ route('bon_livraison.print', $bl->id) }}" class="action-btn print-btn" title="Imprimer">
                  <i class="fas fa-print"></i>
                </a>
                <form method="POST" action="{{ route('bon_livraison.destroy', $bl->id) }}" style="display:inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce bon de livraison?')">
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
                <i class="fas fa-truck"></i>
                <p>Aucun bon de livraison trouvé</p>
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
    color: #666;
    text-decoration: none;
    transition: all 0.3s ease;
}

.filter-link:hover {
    background: #40E0D0;
    color: white;
}

.filter-link.active {
    background: #40E0D0;
    color: white;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-export {
    background: #6c757d;
    color: white;
}

.btn-new {
    background: #40E0D0;
    color: white;
}

.btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

.bon-livraison-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.bon-livraison-table th,
.bon-livraison-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.bon-livraison-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #495057;
}

.bon-livraison-table tr:hover {
    background-color: #f8f9fa;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-pending { background: #ffd700; color: #000; }
.status-delivered { background: #28a745; color: white; }
.status-cancelled { background: #dc3545; color: white; }

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
    transform: translateY(-2px);
}

.view-btn { color: #17a2b8; }
.view-btn:hover { 
    color: #138496; 
    background-color: rgba(23, 162, 184, 0.1);
}

.edit-btn { color: #ffc107; }
.edit-btn:hover { 
    color: #e0a800; 
    background-color: rgba(255, 193, 7, 0.1);
}

.print-btn { color: #6c757d; }
.print-btn:hover { 
    color: #545b62; 
    background-color: rgba(108, 117, 125, 0.1);
}

.delete-btn { color: #dc3545; }
.delete-btn:hover { 
    color: #c82333; 
    background-color: rgba(220, 53, 69, 0.1);
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
    
    .bon-livraison-table {
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
    const container = document.getElementById('bonLivraisonListContainer');
    const newBtn = container.querySelector('.btn-new');

    // Filter handling
    container.querySelectorAll('.filter-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            container.querySelectorAll('.filter-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            loadBonLivraisonList(this.dataset.filter);
        });
    });

    // New button
    if (newBtn) {
        newBtn.addEventListener('click', function() {
            document.dispatchEvent(new CustomEvent('loadView', {
                detail: { view: 'creer_bon_livraison' }
            }));
        });
    }

    // Action buttons for view and edit only
    container.querySelectorAll('.action-btn.view-btn, .action-btn.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if (this.classList.contains('view-btn')) {
                document.dispatchEvent(new CustomEvent('loadView', {
                    detail: { view: 'voir_bon_livraison', id: id }
                }));
            } else if (this.classList.contains('edit-btn')) {
                document.dispatchEvent(new CustomEvent('loadView', {
                    detail: { view: 'edit_bon_livraison', id: id }
                }));
            }
        });
    });

    // Load bon livraison list
    async function loadBonLivraisonList(filter = 'all') {
        try {
            const response = await fetch(`/dashboard/component/liste_bon_livraison?filter=${filter}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (response.ok) {
                const html = await response.text();
                container.innerHTML = html;
                // Reattach event listeners
                attachHandlers();
            }
        } catch (error) {
            console.error('Error loading bon livraison list:', error);
        }
    }

    // Load bon livraison list is still needed for filter functionality
});
</script>
@endpush
