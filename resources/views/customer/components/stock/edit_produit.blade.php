<!-- edit_produit.blade.php -->
<div class="edit-product-container">
    <div class="edit-product-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-edit"></i>
            </div>
            <div class="header-text">
                <h2>Modifier le Produit</h2>
                <p class="header-subtitle">Modifier les informations du produit</p>
            </div>
        </div>
    </div>

    <div class="edit-product-form">
        <form id="editProductForm" class="product-form" method="POST" action="/products/{{ $product->id }}">
            @csrf
            <input type="hidden" name="id" value="{{ $product->id }}">
        
        <div class="form-group">
                <label for="nom">Nom du Produit</label>
                <input type="text" class="form-control" id="nom" name="nom" value="{{ $product->nom ?? '' }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description">{{ $product->description ?? '' }}</textarea>
            </div>

        <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="categorie_id">Catégorie</label>
                    <select class="form-control" id="categorie_id" name="categorie_id">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ ($product->categorie_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

            <div class="form-group col-md-6">
                    <label for="fournisseur_id">Fournisseur</label>
                    <select class="form-control" id="fournisseur_id" name="fournisseur_id">
                        <option value="">Sélectionner un fournisseur</option>
                        @foreach($fournisseurs as $fournisseur)
                            <option value="{{ $fournisseur->id }}" {{ ($product->fournisseur_id ?? '') == $fournisseur->id ? 'selected' : '' }}>
                                {{ $fournisseur->fournisseur_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
        </div>

        <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="prix_achat">Prix d'achat</label>
                    <div class="input-group">
                        <input type="number" step="0.01" class="form-control" id="prix_achat" name="prix_achat" value="{{ $product->prix_achat ?? '' }}" required>
                        <div class="input-group-append">
                            <span class="input-group-text">MAD</span>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="prix_vente">Prix de vente</label>
                    <div class="input-group">
                        <input type="number" step="0.01" class="form-control" id="prix_vente" name="prix_vente" value="{{ $product->prix_vente ?? '' }}" required>
                        <div class="input-group-append">
                            <span class="input-group-text">MAD</span>
                        </div>
                    </div>
                </div>
            </div>

        <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="quantite">Quantité</label>
                    <input type="number" class="form-control" id="quantite" name="quantite" value="{{ $product->quantite ?? '' }}" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="is_service" class="d-block">Type</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_service" name="is_service" {{ ($product->is_service ?? false) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_service">Est un service</label>
                    </div>
                </div>
            </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <button type="button" class="btn btn-secondary load-view" data-view="stock/liste_produits">Annuler</button>
        </div>
    </form>
</div>

<style>
.edit-product-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.edit-product-header {
    background: linear-gradient(135deg, #40E0D0, #3bcdc0);
    padding: 2rem;
    border-radius: 8px 8px 0 0;
    color: white;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
    background: rgba(255, 255, 255, 0.2);
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.header-icon i {
    font-size: 24px;
}

.header-text h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.header-subtitle {
    margin: 0.5rem 0 0;
    opacity: 0.8;
}

.edit-product-form {
    padding: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-control {
    border: 1px solid #e2e8f0;
    padding: 0.75rem;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #40E0D0;
    box-shadow: 0 0 0 3px rgba(64, 224, 208, 0.2);
}

.form-row {
    display: flex;
    margin: 0 -0.75rem;
    margin-bottom: 1rem;
}

.form-row > div {
    padding: 0 0.75rem;
    flex: 1;
}

.input-group-text {
    background-color: #f8fafc;
    border-color: #e2e8f0;
    color: #64748b;
}

.custom-control-input:checked ~ .custom-control-label::before {
    background-color: #40E0D0;
    border-color: #40E0D0;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.btn {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #40E0D0;
    border-color: #40E0D0;
    color: white;
}

.btn-primary:hover {
    background-color: #3bcdc0;
    border-color: #3bcdc0;
    transform: translateY(-1px);
}

.btn-secondary {
    background-color: #f1f5f9;
    border-color: #e2e8f0;
    color: #64748b;
}

.btn-secondary:hover {
    background-color: #e2e8f0;
    color: #1e293b;
}

label {
    color: #475569;
    font-weight: 500;
    margin-bottom: 0.5rem;
    display: block;
}

.custom-switch {
    padding-left: 3.25rem;
    padding-top: 0.5rem;
}

.custom-control-label {
    color: #64748b;
    font-weight: normal;
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up form handler');
    const form = document.getElementById('editProductForm');
    
    if (!form) {
        console.error('Form not found!');
        return;
    }
    
    console.log('Form found:', form);
    console.log('Form action:', form.action);
    
    // Add click handler to submit button for debugging
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            console.log('Submit button clicked');
        });
    }
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Form submitted');
        
        // Get form data and log it
        const formData = new FormData(this);
        console.log('Form action URL:', this.action);
        
        // Log all form values
        formData.forEach((value, key) => {
            console.log(`${key}:`, value);
        });
        
        // Handle checkbox
        const isService = document.getElementById('is_service').checked;
        formData.set('is_service', isService);
        console.log('is_service value:', isService);
        
        // Convert numeric fields
        ['prix_achat', 'prix_vente', 'quantite'].forEach(field => {
            const input = document.getElementById(field);
            const value = input ? parseFloat(input.value) || 0 : 0;
            formData.set(field, value);
            console.log(`${field} value:`, value);
        });
        
        // Log CSRF token
        const token = document.querySelector('meta[name="csrf-token"]');
        console.log('CSRF token found:', !!token);
        
        // Send request
        console.log('Sending fetch request to:', this.action);
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token ? token.content : ''
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', [...response.headers.entries()]);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                // Show success message
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success';
                successAlert.style.position = 'fixed';
                successAlert.style.top = '20px';
                successAlert.style.right = '20px';
                successAlert.style.zIndex = '9999';
                successAlert.style.padding = '1rem 2rem';
                successAlert.style.borderRadius = '4px';
                successAlert.style.backgroundColor = '#40E0D0';
                successAlert.style.color = 'white';
                successAlert.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                document.body.appendChild(successAlert);
                
                console.log('Success! Redirecting in 2 seconds...');
                
                // Redirect after delay
                setTimeout(() => {
                    successAlert.remove();
                    const listBtn = document.querySelector('[data-view="stock/liste_produits"]');
                    if (listBtn) {
                        console.log('Clicking list button');
                        listBtn.click();
                    } else {
                        console.error('List button not found!');
                    }
                }, 2000);
            } else {
                console.error('Server returned error:', data.error);
                alert(data.error || 'Erreur lors de la modification du produit');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Erreur lors de la modification du produit: ' + error.message);
        });
    });
});</script>
