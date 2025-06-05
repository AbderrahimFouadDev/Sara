<!-- Historique des Remboursements -->
<div class="remboursements-container">
    <div class="section-header">
        <h2><i class="fas fa-history"></i> Historique des Remboursements</h2>
        
        <!-- Search and Filter Section -->
        <div class="filters-section">
            <div class="search-box">
                <input type="text" id="searchRemboursement" placeholder="Rechercher par n° facture...">
                <i class="fas fa-search"></i>
            </div>
            
            <div class="filter-box">
                <select id="statutFilter">
                    <option value="">Tous les statuts</option>
                    <option value="en_cours">En cours</option>
                    <option value="termine">Terminé</option>
                    <option value="refuse">Refusé</option>
                </select>
                
                <input type="date" id="dateFilter" class="date-filter">
            </div>
        </div>
    </div>

    <!-- Table des remboursements -->
    <div class="table-responsive">
        <table class="remboursements-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>N° Facture</th>
                    <th>Montant</th>
                    <th>Motif</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="remboursementsBody">
                @forelse($remboursements as $remboursement)
                <tr>
                    <td>{{ date('d/m/Y', strtotime($remboursement->date_remboursement)) }}</td>
                    <td>{{ $remboursement->facture_id }}</td>
                    <td>{{ number_format($remboursement->montant, 2) }} MAD</td>
                    <td>{{ $remboursement->motif }}</td>
                    <td>
                        <span class="status-badge {{ $remboursement->statut }}">
                            {{ ucfirst($remboursement->statut) }}
                        </span>
                    </td>
                    <td>
                        <button class="btn-details" onclick="showDetails({{ $remboursement->id }})">
                            <i class="fas fa-eye"></i> Détails
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="no-data">
                        <div class="no-data-message">
                            <i class="fas fa-info-circle"></i>
                            <p>Aucun remboursement enregistré pour l'instant.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        {{ $remboursements->links() }}
    </div>
</div>

<!-- Modal Détails -->
<div id="detailsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Détails du Remboursement</h3>
        <div id="remboursementDetails">
            <!-- Les détails seront chargés ici dynamiquement -->
        </div>
    </div>
</div>

<style>
.remboursements-container {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.section-header {
    margin-bottom: 20px;
}

.section-header h2 {
    color: #2d3748;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.filters-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
    flex-wrap: wrap;
    gap: 15px;
}

.search-box {
    position: relative;
    flex: 1;
    max-width: 300px;
}

.search-box input {
    width: 100%;
    padding: 10px 35px 10px 15px;
    border: 1px solid #e2e8f0;
    border-radius: 5px;
    font-size: 0.9rem;
}

.search-box i {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #718096;
}

.filter-box {
    display: flex;
    gap: 10px;
}

.filter-box select,
.filter-box input {
    padding: 8px 15px;
    border: 1px solid #e2e8f0;
    border-radius: 5px;
    font-size: 0.9rem;
}

.remboursements-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.remboursements-table th,
.remboursements-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.remboursements-table th {
    background-color: #f7fafc;
    font-weight: 600;
    color: #4a5568;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 500;
}

.status-badge.en_cours {
    background-color: #fef3c7;
    color: #92400e;
}

.status-badge.termine {
    background-color: #dcfce7;
    color: #166534;
}

.status-badge.refuse {
    background-color: #fee2e2;
    color: #991b1b;
}

.btn-details {
    padding: 6px 12px;
    border: none;
    border-radius: 5px;
    background-color: #3b82f6;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.9rem;
}

.btn-details:hover {
    background-color: #2563eb;
}

.no-data {
    text-align: center;
    padding: 40px 0;
}

.no-data-message {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    color: #64748b;
}

.no-data-message i {
    font-size: 2rem;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fff;
    margin: 10% auto;
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 600px;
    position: relative;
}

.close {
    position: absolute;
    right: 20px;
    top: 15px;
    font-size: 24px;
    cursor: pointer;
    color: #64748b;
}

.close:hover {
    color: #1f2937;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.detail-item .label {
    font-weight: 600;
    color: #4a5568;
    font-size: 0.9rem;
}

.detail-item .value {
    color: #1f2937;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .filters-section {
        flex-direction: column;
        align-items: stretch;
    }

    .search-box {
        max-width: 100%;
    }

    .filter-box {
        flex-wrap: wrap;
    }

    .filter-box select,
    .filter-box input {
        flex: 1;
        min-width: 150px;
    }

    .modal-content {
        width: 95%;
        margin: 5% auto;
    }

    .details-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- Include the JavaScript file -->
@vite('resources/js/remboursements.js')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchRemboursement');
    const statutFilter = document.getElementById('statutFilter');
    const dateFilter = document.getElementById('dateFilter');
    const modal = document.getElementById('detailsModal');
    const closeBtn = document.querySelector('.close');

    // Filter functionality
    function applyFilters() {
        const search = searchInput.value;
        const statut = statutFilter.value;
        const date = dateFilter.value;

        // Load the view with filters
        const view = 'clients/historique_remboursements';
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (statut) params.append('statut', statut);
        if (date) params.append('date', date);

        fetch(`/dashboard/component/${view}?${params.toString()}`, {
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
            alert('Erreur lors du chargement des données');
        });
    }

    // Add event listeners for filters
    searchInput.addEventListener('input', debounce(applyFilters, 500));
    statutFilter.addEventListener('change', applyFilters);
    dateFilter.addEventListener('change', applyFilters);

    // Modal functionality
    window.showDetails = function(id) {
        fetch(`/remboursements/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const detailsContainer = document.getElementById('remboursementDetails');
            detailsContainer.innerHTML = `
                <div class="details-content">
                    <p><strong>ID Remboursement:</strong> ${data.id}</p>
                    <p><strong>Date:</strong> ${data.date_remboursement}</p>
                    <p><strong>Montant:</strong> ${formatMoney(data.montant)} MAD</p>
                    <p><strong>Motif:</strong> ${data.motif}</p>
                    <p><strong>Statut:</strong> <span class="status-badge ${data.statut}">${data.statut}</span></p>
                    <p><strong>Informations supplémentaires:</strong></p>
                    <ul>
                        <li>Client: ${data.client ? data.client.client_name : 'N/A'}</li>
                        <li>Facture: ${data.facture ? data.facture.numero : 'N/A'}</li>
                        <li>Date de la demande: ${data.created_at}</li>
                    </ul>
                </div>
            `;
            modal.style.display = "block";
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors du chargement des détails');
        });
    };

    // Close modal
    closeBtn.onclick = function() {
        modal.style.display = "none";
    };

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
});

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function formatMoney(amount) {
    return new Intl.NumberFormat('fr-FR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(amount);
}
</script> 