{{-- resources/views/customer/components/stock/liste_categories.blade.php --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="edit-category-container" style="display: none;"></div>
<div class="categories-list-container">
    <div class="list-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-folder"></i>
            </div>
            <div class="header-text">
                <h2>Liste des Catégories</h2>
                <p class="header-subtitle">Gérez vos catégories d'articles et services</p>
            </div>
        </div>
        
        <div class="header-actions">
           

            <a href="#" class="btn btn-primary" onclick="loadView('stock/ajouter_categorie')">
                <i class="fas fa-plus"></i>
                Nouvelle Catégorie
            </a>
        </div>
    </div>

    <div class="table-container">
        <table class="categories-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    @forelse($categories as $category)
        <tr>
            <td>{{ $category->nom }}</td>
            <td>{{ $category->description }}</td>
            <td>{{ $category->created_at->format('Y-m-d') }}</td>
            <td class="actions-cell">
                <div class="action-buttons">
                   
                    <button type="button" class="action-btn edit-btn load-view" title="Modifier" data-view="stock/edit_categorie/{{ $category->id }}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie?');">
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
            <td colspan="4" class="text-center">
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>Aucune catégorie trouvée</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>

        </table>
    </div>
</div>


<style>
#edit-category-container {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.05);
    margin: 2rem;
    margin-bottom: 2rem;
    transition: all 0.3s ease;
}

.loading-spinner {
    text-align: center;
    padding: 2rem;
    color: #3498db;
    font-size: 1.2rem;
}

.loading-spinner i {
    margin-right: 0.5rem;
}

.error-message {
    background-color: #fee2e2;
    color: #ef4444;
    padding: 1rem;
    border-radius: 8px;
    text-align: center;
}

.categories-list-container {
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
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.search-box {
    position: relative;
    flex: 1;
    max-width: 400px;
}

.search-box input {
    width: 100%;
    padding: 0.8rem 1rem 0.8rem 2.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.search-box i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
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

.categories-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.categories-table th,
.categories-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 2px solid #f1f5f9;
}

.categories-table th {
    background: #f8fafc;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.05em;
}

.categories-table tbody tr {
    transition: all 0.3s ease;
}

.categories-table tbody tr:hover {
    background-color: #f8fafc;
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
    padding: 0.5rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
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

.delete-btn {
    color: #ef4444;
    background: #fee2e2;
}

.action-btn:hover {
    transform: translateY(-2px);
}

@media (max-width: 1024px) {
    .header-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .search-box {
        max-width: none;
    }
}

@media (max-width: 768px) {
    .categories-list-container {
        margin: 1rem;
        padding: 1rem;
    }

    .header-content {
        flex-direction: column;
        text-align: center;
    }

    .header-icon {
        margin: 0 auto;
    }

    .table-container {
        overflow-x: auto;
    }

    .categories-table {
        min-width: 800px;
    }
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Check if searchCategory element exists before adding event listener
        const searchElement = document.getElementById('searchCategory');
        if (searchElement) {
            searchElement.addEventListener('input', filterCategories);
        }
        loadCategories();
    });
    
    function editCategory(categoryId) {
        // Show loading state
        const editContainer = document.getElementById('edit-category-container');
        editContainer.innerHTML = '<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Chargement...</div>';
        editContainer.style.display = 'block';
        
        // Scroll to the edit form
        editContainer.scrollIntoView({ behavior: 'smooth' });
        
        // Fetch the edit form via AJAX
        fetch(`/categories/${categoryId}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Display the edit form
            editContainer.innerHTML = html;
            
            // Add event listener to the cancel button
            const cancelButton = editContainer.querySelector('.btn-secondary');
            if (cancelButton) {
                cancelButton.addEventListener('click', function() {
                    editContainer.style.display = 'none';
                });
            }
            
            // Add event listener to the form submission
            const form = editContainer.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Submit the form via AJAX
                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Hide the edit form
                            editContainer.style.display = 'none';
                            
                            // Reload the categories list
                            loadCategories();
                            
                            // Show success message
                            alert('Catégorie mise à jour avec succès!');
                        } else {
                            alert('Erreur lors de la mise à jour de la catégorie.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erreur lors de la mise à jour de la catégorie.');
                    });
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            editContainer.innerHTML = '<div class="error-message">Erreur lors du chargement du formulaire.</div>';
        });
    }

    // Updated to match your web routes
    const indexUrl = '/stock/categories';
    const deleteUrlBase = '/stock/categories';

    async function loadCategories() {
        const tbody = document.getElementById('categoriesTableBody');
        tbody.innerHTML = `
            <tr>
                <td colspan="4">
                    <div class="loading-spinner">
                        <i class="fas fa-circle-notch"></i>
                    </div>
                </td>
            </tr>
        `;

        try {
            const response = await fetch(indexUrl, {
                headers: { 'Accept': 'application/json' }
            });
            if (!response.ok) throw new Error('Failed to load categories');
            const categories = await response.json();
            displayCategories(categories);
        } catch (error) {
            console.error(error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>Une erreur est survenue lors du chargement des catégories</p>
                        </div>
                    </td>
                </tr>
            `;
        }
    }

    function displayCategories(categories) {
        const tbody = document.getElementById('categoriesTableBody');
        if (!categories || categories.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <p>Aucune catégorie trouvée</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }
        tbody.innerHTML = '';
        categories.forEach((cat, i) => {
            const tr = document.createElement('tr');
            tr.style.animationDelay = `${i * 0.05}s`;
            tr.innerHTML = `
                <td>${cat.nom}</td>
                <td>${cat.description || '-'}</td>
                <td>${new Date(cat.created_at).toLocaleDateString()}</td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn edit-btn" onclick="loadView('stock/edit_categorie/${cat.id}')" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete-btn" onclick="deleteCategory(${cat.id})" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function filterCategories() {
        const term = document.getElementById('searchCategory').value.toLowerCase();
        document.querySelectorAll('#categoriesTableBody tr').forEach(row => {
            const [nameCell, descCell] = row.cells;
            const match = nameCell.textContent.toLowerCase().includes(term)
                       || descCell.textContent.toLowerCase().includes(term);
            row.style.display = match ? '' : 'none';
        });
    }

    async function deleteCategory(id) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')) return;
        try {
            const response = await fetch(`${deleteUrlBase}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            if (!response.ok) throw new Error('Error deleting category');
            loadCategories();
        } catch (error) {
            console.error(error);
            alert('Une erreur est survenue lors de la suppression');
        }
    }
</script>
