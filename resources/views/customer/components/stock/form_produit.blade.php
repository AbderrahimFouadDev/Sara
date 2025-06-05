<div class="product-form-container">
    <div class="form-header">
        <div class="header-icon">
            <i class="fas fa-box"></i>
        </div>
        <h2>Ajouter un Article/Service</h2>
        <p class="header-subtitle">Remplissez les informations ci-dessous pour ajouter un nouvel article ou service</p>
    </div>

    <form id="productForm" class="product-form"  method="POST"
      action="{{ route('products.store') }}">
          @csrf

        <div class="form-grid">
            <!-- Nom Article -->
            <div class="form-group full-width">
                <label for="nom_article">
                    <i class="fas fa-tag input-icon"></i>
                    Nom Article/Service *
                </label>
                <input type="text" id="nom_article" name="nom_article" required class="form-control" placeholder="Entrez le nom de l'article ou du service">
            </div>

            <!-- Type Selection -->
            <div class="form-group">
                <label class="switch-label">C'est un service ?</label>
                <div class="toggle-container">
                    <input type="checkbox" id="is_service" name="is_service" class="toggle-input">
                    <label for="is_service" class="toggle-label">
                        <span class="toggle-button"></span>
                        <span class="toggle-text"></span>
                    </label>
                </div>
            </div>

            <!-- Fournisseur (Only for products) -->
            <div class="form-group product-only">
                <label for="fournisseur">
                    <i class="fas fa-truck input-icon"></i>
                    Fournisseur
                </label>
                <select id="fournisseur" name="fournisseur_id" class="form-control">
    <option value="">Sélectionner un fournisseur</option>
    @foreach($fournisseurs as $fourni)
        <option value="{{ $fourni->id }}">
            {{ $fourni->fournisseur_name }}
        </option>
    @endforeach
</select>

            </div>

            <!-- Quantity (Only for products) -->
            <div class="form-group product-only">
                <label for="quantite">
                    <i class="fas fa-cubes input-icon"></i>
                    Quantité
                </label>
                <input type="number" id="quantite" name="quantite" min="0" class="form-control" placeholder="0">
            </div>

            <!-- Prix d'achat -->
            <div class="form-group">
                <label for="prix_achat">
                    <i class="fas fa-tags input-icon"></i>
                    Prix d'achat
                </label>
                <div class="input-group">
                    <input type="number" id="prix_achat" name="prix_achat" step="0.01" min="0" class="form-control" placeholder="0.00">
                    <span class="input-group-text">MAD</span>
                </div>
            </div>

            <!-- Prix de vente -->
            <div class="form-group">
                <label for="prix_vente">
                    <i class="fas fa-money-bill-wave input-icon"></i>
                    Prix de vente
                </label>
                <div class="input-group">
                    <input type="number" id="prix_vente" name="prix_vente" step="0.01" min="0" class="form-control" placeholder="0.00">
                    <span class="input-group-text">MAD</span>
                </div>
            </div>

            <!-- Catégorie -->
            <div class="form-group full-width">
                <label for="categorie">
                    <i class="fas fa-folder input-icon"></i>
                    Catégorie
                </label>
                <div class="category-group">
                   <select id="categorie" name="categorie_id" class="form-control">
  <option value="">Sélectionner une catégorie</option>
  @foreach($categories as $cat)
    <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
  @endforeach
</select>

                    <button type="button" id="addCategoryBtn" class="btn btn-icon">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>

            <!-- Description -->
            <div class="form-group full-width">
                <label for="description">
                    <i class="fas fa-align-left input-icon"></i>
                    Description
                </label>
                <textarea id="description" name="description" class="form-control" rows="4" placeholder="Décrivez votre article ou service..."></textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Enregistrer
            </button>
            <button type="button" class="btn btn-secondary" onclick="history.back()">
                <i class="fas fa-times"></i>
                Annuler
            </button>
        </div>
    </form>
</div>



<style>
.product-form-container {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.05);
    max-width: 1000px;
    margin: 2rem auto;
}

.form-header {
    text-align: center;
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid #f8f9fa;
}

.header-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #40E0D0, #3498db);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.header-icon i {
    font-size: 2.5rem;
    color: white;
}

.form-header h2 {
    color: #2c3e50;
    font-size: 2rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.header-subtitle {
    color: #6c757d;
    font-size: 1rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 0;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    color: #2c3e50;
    font-weight: 500;
    font-size: 0.95rem;
}

.input-icon {
    color: #40E0D0;
    width: 20px;
}

.form-control {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.form-control:focus {
    border-color: #40E0D0;
    box-shadow: 0 0 0 3px rgba(64, 224, 208, 0.2);
    outline: none;
}

.input-group {
    display: flex;
    align-items: stretch;
}

.input-group .form-control {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-right: none;
}

.input-group-text {
    padding: 0.8rem 1.2rem;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-left: none;
    border-radius: 0 10px 10px 0;
    color: #64748b;
    font-weight: 500;
    display: flex;
    align-items: center;
}

.toggle-container {
    position: relative;
    width: 60px;
    height: 30px;
}

.toggle-input {
    display: none;
}

.toggle-label {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #e2e8f0;
    transition: .4s;
    border-radius: 30px;
}

.toggle-button {
    position: absolute;
    content: "";
    height: 24px;
    width: 24px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

.toggle-input:checked + .toggle-label {
    background: linear-gradient(135deg, #40E0D0, #3498db);
}

.toggle-input:checked + .toggle-label .toggle-button {
    transform: translateX(30px);
}

.category-group {
    display: flex;
    gap: 1rem;
}

.category-group .form-control {
    flex: 1;
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
}

.btn-primary {
    background: linear-gradient(135deg, #40E0D0, #3498db);
    color: white;
}

.btn-secondary {
    background: #64748b;
    color: white;
}

.btn-icon {
    padding: 0.8rem;
    background: #40E0D0;
    color: white;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 2px solid #f8f9fa;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    backdrop-filter: blur(5px);
}

.modal-content {
    background: white;
    width: 90%;
    max-width: 500px;
    margin: 4rem auto;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    transform: translateY(20px);
    animation: modalSlideIn 0.3s ease forwards;
}

@keyframes modalSlideIn {
    to {
        transform: translateY(0);
    }
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 2px solid #f8f9fa;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    color: #2c3e50;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-header h3 i {
    color: #40E0D0;
}

.modal-body {
    padding: 2rem;
}

.close-modal {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #64748b;
    transition: color 0.3s ease;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.close-modal:hover {
    color: #2c3e50;
    background: #f8f9fa;
}

.modal-actions {
    margin-top: 2rem;
    padding-top: 1.5rem;
}

/* Error Styles */
.error {
    border-color: #dc3545 !important;
}

.error-message {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.error-message::before {
    content: "\f071";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
}

/* Responsive Design */
@media (max-width: 768px) {
    .product-form-container {
        margin: 1rem;
        padding: 1.5rem;
    }

    .form-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }

    .header-icon {
        width: 60px;
        height: 60px;
    }

    .header-icon i {
        font-size: 2rem;
    }

    .form-header h2 {
        font-size: 1.5rem;
    }
}

/* Animation for form elements */
.form-group {
    opacity: 0;
    transform: translateY(10px);
    animation: fadeInUp 0.5s ease forwards;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Stagger animation for form groups */
.form-group:nth-child(1) { animation-delay: 0.1s; }
.form-group:nth-child(2) { animation-delay: 0.2s; }
.form-group:nth-child(3) { animation-delay: 0.3s; }
.form-group:nth-child(4) { animation-delay: 0.4s; }
.form-group:nth-child(5) { animation-delay: 0.5s; }
.form-group:nth-child(6) { animation-delay: 0.6s; }

/* Hover effects */
.form-control:hover {
    border-color: #cbd5e1;
}

.btn:active {
    transform: translateY(0);
}

/* Focus styles */
.form-control:focus::placeholder {
    opacity: 0.5;
}

/* Custom scrollbar for textarea */
textarea.form-control {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f8fafc;
}

textarea.form-control::-webkit-scrollbar {
    width: 8px;
}

textarea.form-control::-webkit-scrollbar-track {
    background: #f8fafc;
    border-radius: 4px;
}

textarea.form-control::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

textarea.form-control::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('productForm');
    const isServiceToggle = document.getElementById('is_service');
    const productOnlyFields = document.querySelectorAll('.product-only');
    const addCategoryBtn = document.getElementById('addCategoryBtn');
    const modal = document.getElementById('addCategoryModal');
    const categoryForm = document.getElementById('categoryForm');
    const closeModalBtns = document.querySelectorAll('.close-modal');

    // Function to toggle product-only fields
    function toggleProductFields(isService) {
        productOnlyFields.forEach(field => {
            if(isService) {
                field.style.display = 'none';
            } else {
                field.style.display = 'block';
            }
        });
    }

    // Set initial state
    toggleProductFields(isServiceToggle.checked);

    // Toggle fields when service checkbox changes
    isServiceToggle.addEventListener('change', function() {
        toggleProductFields(this.checked);
    });

    // Show modal
    addCategoryBtn.addEventListener('click', function() {
        modal.style.display = 'block';
    });

    // Close modal
    closeModalBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Handle category form submission
    categoryForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        try {
            const response = await fetch('/api/categories', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    nom: document.getElementById('categorie_nom').value
                })
            });

            if (response.ok) {
                const data = await response.json();
                // Add new category to select options
                const option = new Option(data.nom, data.id);
                document.getElementById('categorie').add(option);
                // Select the new category
                document.getElementById('categorie').value = data.id;
                // Close modal
                modal.style.display = 'none';
                // Reset form
                categoryForm.reset();
            } else {
                throw new Error('Error adding category');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Une erreur est survenue lors de l\'ajout de la catégorie');
        }
    });

    // Load suppliers
    loadSuppliers();
    // Load categories
    loadCategories();
});

async function loadSuppliers() {
    try {
        const response = await fetch('/api/fournisseurs');
        if (response.ok) {
            const suppliers = await response.json();
            const select = document.getElementById('fournisseur');
            suppliers.forEach(supplier => {
                const option = new Option(supplier.nom, supplier.id);
                select.add(option);
            });
        }
    } catch (error) {
        console.error('Error loading suppliers:', error);
    }
}

async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        if (response.ok) {
            const categories = await response.json();
            const select = document.getElementById('categorie');
            categories.forEach(category => {
                const option = new Option(category.nom, category.id);
                select.add(option);
            });
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}
</script>