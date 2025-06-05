<div class="products-list-container">
    <div class="list-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="header-text">
                <h2>Liste des Articles et Services</h2>
                <p class="header-subtitle">Gérez votre inventaire et vos services</p>
            </div>
        </div>
        
        <div class="header-actions">
          
           

            <button class="btn btn-primary load-view" data-view="stock/form_produit">
                <i class="fas fa-plus"></i>
                Nouveau
            </button>
        </div>
    </div>

    <div class="table-container">
        <table class="products-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Catégorie</th>
                    <th>Fournisseur</th>
                    <th>Prix d'achat</th>
                    <th>Prix de vente</th>
                    <th>Quantité</th>
                    <th>Actions</th>
                </tr>
            </thead>
           <tbody>
    @forelse($products as $product)
        <tr>
            <td>{{ $product->nom }}</td>
            <td>{{ $product->is_service ? 'Service' : 'Produit' }}</td>
            <td>{{ $product->description }}</td>
            <td>{{ $product->categorie->nom ?? 'Non défini' }}</td>
            <td>{{ $product->fournisseur->fournisseur_name ?? 'Non défini' }}</td>
            <td>{{ number_format($product->prix_achat, 2) }} MAD</td>
            <td>{{ number_format($product->prix_vente, 2) }} MAD</td>
            <td>{{ $product->quantite }}</td>
            <td class="actions-cell">
                <div class="action-buttons">
                    <button class="action-btn edit-btn load-view" title="Modifier" data-view="stock/edit_produit" data-id="{{ $product->id }}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="{{ route('products.delete', $product->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn delete-btn" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit?')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="text-center">
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>Aucun produit trouvé</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>

        </table>
    </div>
</div>

<style>
.products-list-container {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.05);
    margin: 2rem;
}

.list-header {
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #f8f9fa;
}

.header-icon {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #40E0D0, #3498db);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.header-icon i {
    font-size: 1.8rem;
    color: white;
}

.header-text h2 {
    color: #2c3e50;
    font-size: 1.8rem;
    margin-bottom: 0.3rem;
    font-weight: 600;
}

.header-subtitle {
    color: #6c757d;
    font-size: 1rem;
}

.header-actions {
    display: grid;
    grid-template-columns: 1fr auto auto;
    gap: 1.5rem;
    align-items: center;
}

.search-box {
    position: relative;
}

.search-box input {
    width: 100%;
    padding: 0.8rem 1rem 0.8rem 3rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.search-box input:focus {
    border-color: #40E0D0;
    box-shadow: 0 0 0 3px rgba(64, 224, 208, 0.2);
    outline: none;
}

.search-box i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    transition: color 0.3s ease;
}

.search-box input:focus + i {
    color: #40E0D0;
}

.filter-group {
    display: flex;
    gap: 1rem;
}

.select-wrapper {
    position: relative;
}

.select-wrapper i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    z-index: 1;
}

.form-control {
    min-width: 180px;
    padding: 0.8rem 1rem 0.8rem 3rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8fafc;
    appearance: none;
    cursor: pointer;
}

.form-control:focus {
    border-color: #40E0D0;
    box-shadow: 0 0 0 3px rgba(64, 224, 208, 0.2);
    outline: none;
}

.select-wrapper::after {
    content: '\f107';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
}

.btn {
    padding: 0.8rem 1.5rem;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, #40E0D0, #3498db);
    color: white;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.table-container {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 0 2px #f1f5f9;
}

.products-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.products-table th,
.products-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 2px solid #f1f5f9;
}

.products-table th {
    background: #f8fafc;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.05em;
}

.products-table tbody tr {
    transition: all 0.3s ease;
}

.products-table tbody tr:hover {
    background-color: #f8fafc;
}

.type-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.type-product {
    background: #e3f2fd;
    color: #1976d2;
}

.type-product::before {
    content: '\f466';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
}

.type-service {
    background: #f3e5f5;
    color: #7b1fa2;
}

.type-service::before {
    content: '\f085';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

tr:hover .action-buttons {
    opacity: 1;
}

.action-btn {
    background: none;
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
}

.edit-btn {
    color: #0ea5e9;
    background: #e0f2fe;
}

.edit-btn:hover {
    background: #bae6fd;
    transform: translateY(-2px);
}

.delete-btn {
    color: #ef4444;
    background: #fee2e2;
}

.delete-btn:hover {
    background: #fecaca;
    transform: translateY(-2px);
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 3rem;
    color: #64748b;
}

.empty-state i {
    font-size: 3rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
}

/* Loading state */
.loading-spinner {
    display: flex;
    justify-content: center;
    padding: 2rem;
}

.loading-spinner i {
    color: #40E0D0;
    font-size: 2rem;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Responsive Design */
@media (max-width: 1200px) {
    .header-actions {
        grid-template-columns: 1fr;
    }

    .filter-group {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    }
}

@media (max-width: 768px) {
    .products-list-container {
        margin: 1rem;
        padding: 1rem;
    }

    .header-content {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }

    .header-icon {
        margin: 0 auto;
    }

    .filter-group {
        flex-direction: column;
    }

    .table-container {
        overflow-x: auto;
    }

    .products-table {
        min-width: 800px;
    }
}

/* Animation for new rows */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.products-table tbody tr {
    animation: fadeIn 0.3s ease forwards;
}

/* Custom scrollbar */
.table-container::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.table-container::-webkit-scrollbar-track {
    background: #f8fafc;
    border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle filters
    const searchInput = document.getElementById('searchProduct');
    const typeFilter = document.getElementById('typeFilter');
    const categoryFilter = document.getElementById('categoryFilter');

    function handleFilter() {
        const view = 'stock/liste_produits';
        const params = {
            search: searchInput.value,
            type: typeFilter.value,
            category: categoryFilter.value
        };
        
        const queryString = new URLSearchParams(params).toString();
        const url = `${view}?${queryString}`;
        
        document.querySelector('[data-view="' + url + '"]').click();
    }

    searchInput.addEventListener('input', handleFilter);
    typeFilter.addEventListener('change', handleFilter);
    categoryFilter.addEventListener('change', handleFilter);

    // Handle delete
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', function(e) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
                const id = this.dataset.id;
                fetch(`/products/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector('[data-view="stock/liste_produits"]').click();
                    } else {
                        alert('Erreur lors de la suppression du produit');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de la suppression du produit');
                });
            }
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  loadCategoryRows();
});

const categoryIndexUrl = '{{ route('categories.index') }}'; // /stock/categories

async function loadCategoryRows() {
  const tbody = document.getElementById('productsTableBody');
  // Show a spinner while loading
  tbody.innerHTML = `
    <tr><td colspan="3" class="text-center">
      <div class="loading-spinner"><i class="fas fa-circle-notch fa-spin"></i></div>
    </td></tr>
  `;

  try {
    const res = await fetch(categoryIndexUrl, {
      headers: { 'Accept': 'application/json' }
    });
    if (!res.ok) throw new Error('Échec du chargement');

    const categories = await res.json();
    renderCategoryRows(categories);
  } catch (err) {
    console.error(err);
    tbody.innerHTML = `
      <tr><td colspan="3" class="text-center">
        <p class="text-danger">Impossible de charger les catégories.</p>
      </td></tr>
    `;
  }
}

function renderCategoryRows(categories) {
  const tbody = document.getElementById('productsTableBody');
  if (!categories.length) {
    tbody.innerHTML = `
      <tr><td colspan="3" class="text-center">
        <p>Aucune catégorie trouvée.</p>
      </td></tr>
    `;
    return;
  }

  // Build each row
  tbody.innerHTML = ''; 
  categories.forEach(cat => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${cat.nom}</td>
      <td>${cat.description || '-'}</td>
      <td>${new Date(cat.created_at).toLocaleDateString()}</td>
    `;
    tbody.appendChild(tr);
  });
}
</script>
