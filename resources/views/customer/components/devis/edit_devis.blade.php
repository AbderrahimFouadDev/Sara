<div class="edit-devis-container">
    <div class="form-header">
        <h2><i class="fas fa-edit"></i> Modifier le Devis {{ $devis->numero }}</h2>
    </div>

    <form id="editDevisForm" class="devis-form">
        @csrf
        <!-- Client Information -->
        <div class="form-section">
            <h3>Information Client</h3>
            <div class="form-group">
                <label>Client</label>
                <input type="text" class="form-control" value="{{ $devis->client->client_name }}" readonly>
            </div>
        </div>

        <!-- Devis Details -->
        <div class="form-section">
            <h3>Détails du Devis</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="date_devis">Date du Devis</label>
                    <input type="date" id="date_devis" name="date_devis" class="form-control" 
                           value="{{ $devis->date_devis->format('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label for="validite">Validité (jours)</label>
                    <input type="number" id="validite" name="validite" class="form-control" 
                           value="15" min="1">
                </div>
                <div class="form-group">
                    <label for="statut">Statut</label>
                    <select id="statut" name="statut" class="form-control">
                        <option value="en attente" {{ $devis->statut === 'en attente' ? 'selected' : '' }}>En attente</option>
                        <option value="accepte" {{ $devis->statut === 'accepte' ? 'selected' : '' }}>Accepté</option>
                        <option value="rejete" {{ $devis->statut === 'rejete' ? 'selected' : '' }}>Rejeté</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="form-section">
            <h3>Articles</h3>
            <div class="add-product-row">
                <div class="form-group">
                    <label for="product_id">Sélectionner un Article</label>
                    <select id="product_id" class="form-control">
                        <option value="">Choisir un article...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-price="{{ $product->prix_vente }}"
                                    data-description="{{ $product->description }}">
                                {{ $product->nom }} - {{ number_format($product->prix_vente, 2) }} MAD
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantité</label>
                    <input type="number" id="quantity" class="form-control" value="1" min="1">
                </div>
                <button type="button" id="addProductBtn" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter
                </button>
            </div>

            <table class="products-table">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>Description</th>
                        <th>Prix HT</th>
                        <th>Quantité</th>
                        <th>Prix Total HT</th>
                        <th>TVA (20%)</th>
                        <th>Total TTC</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="selectedProducts">
                    <tr class="no-products">
                        <td colspan="8" class="text-center">Aucun article sélectionné</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Total HT:</strong></td>
                        <td class="total-ht">0.00 MAD</td>
                        <td class="total-tva">0.00 MAD</td>
                        <td class="total-ttc">0.00 MAD</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Notes Section -->
        <div class="form-section">
            <h3>Notes et Conditions</h3>
            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" class="form-control" rows="3"
                         placeholder="Conditions de paiement, délai de livraison, etc.">{{ $devis->notes }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-cancel" id="annulerBtn">
                <i class="fas fa-times"></i> Annuler
            </button>
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </form>
</div>

<style>
.edit-devis-container {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin: 2rem;
}

.form-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e0e0e0;
}

.form-header h2 {
    color: #333;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.form-section h3 {
    color: #40E0D0;
    margin-bottom: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.add-product-row {
    display: grid;
    grid-template-columns: 2fr 1fr auto;
    gap: 1rem;
    align-items: end;
    margin-bottom: 1.5rem;
}

.products-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
    background: white;
}

.products-table th,
.products-table td {
    padding: 12px;
    border: 1px solid #dee2e6;
    text-align: left;
}

.products-table th {
    background: #f8f9fa;
    font-weight: bold;
}

.products-table .no-products td {
    text-align: center;
    color: #6c757d;
    padding: 20px;
    font-style: italic;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: #40E0D0;
    color: white;
    border: none;
}

.btn-cancel {
    background: #dc3545;
    color: white;
    border: none;
}

.btn-submit {
    background: #40E0D0;
    color: white;
    border: none;
}

.btn-danger {
    padding: 5px 10px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-danger:hover {
    background: #c82333;
}

.text-right {
    text-align: right;
}

.text-center {
    text-align: center;
}

.total-ht, .total-tva, .total-ttc {
    font-weight: bold;
}
</style>

<script>
console.log('Devis script loading...');

// Initialize devisId for use in the script
const devisId = {{ $devis->id }};

// Get DOM elements and verify they exist
    const form = document.getElementById('editDevisForm');
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const addProductBtn = document.getElementById('addProductBtn');
    const selectedProductsTable = document.getElementById('selectedProducts');

console.log('DOM Elements found:', {
    form: !!form,
    productSelect: !!productSelect,
    quantityInput: !!quantityInput,
    addProductBtn: !!addProductBtn,
    selectedProductsTable: !!selectedProductsTable
});

if (!form || !productSelect || !quantityInput || !addProductBtn || !selectedProductsTable) {
    console.error('Required DOM elements not found!');
    throw new Error('Required DOM elements not found!');
        }

const selectedProducts = new Map();

function addProductToTable(product) {
    console.log('Adding product to table:', product);

    // Remove "no products" placeholder
    const noProducts = selectedProductsTable.querySelector('.no-products');
    if (noProducts) {
        console.log('Removing no products placeholder');
        noProducts.remove();
    }

    // Build the <tr>
        const row = document.createElement('tr');
        row.dataset.productId = product.id;
        row.innerHTML = `
            <td>${product.name}</td>
            <td>${product.description}</td>
        <td class="price-ht-cell">${product.price_ht.toFixed(2)} MAD</td>
            <td>
            <input type="number" class="form-control row-quantity-input" 
                       value="${product.quantity}" min="1" style="width: 80px">
            </td>
            <td class="total-ht-line">${product.total_ht.toFixed(2)} MAD</td>
            <td class="tva-amount">${product.tva_amount.toFixed(2)} MAD</td>
            <td class="total-ttc-line">${product.total_ttc.toFixed(2)} MAD</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm delete-product">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

    console.log('Row created, appending to table');
        selectedProductsTable.appendChild(row);

    // Wire up row-specific inputs
    const rowQtyInput = row.querySelector('.row-quantity-input');
        const deleteBtn = row.querySelector('.delete-product');

    if (!rowQtyInput || !deleteBtn) {
        console.error('Failed to find row controls for product:', product.id);
                return;
            }

    rowQtyInput.addEventListener('change', () => {
        console.log('Quantity changed for product:', product.id);
        let newQty = parseInt(rowQtyInput.value) || 1;
        if (newQty < 1) newQty = 1;
        rowQtyInput.value = newQty;

        const prod = selectedProducts.get(product.id);
        if (!prod) {
            console.error('Product not found in map:', product.id);
            return;
        }

        prod.quantity = newQty;
        prod.total_ht = prod.price_ht * newQty;
        prod.tva_amount = prod.total_ht * 0.20;
        prod.total_ttc = prod.total_ht + prod.tva_amount;

        row.querySelector('.total-ht-line').textContent = prod.total_ht.toFixed(2) + ' MAD';
        row.querySelector('.tva-amount').textContent = prod.tva_amount.toFixed(2) + ' MAD';
        row.querySelector('.total-ttc-line').textContent = prod.total_ttc.toFixed(2) + ' MAD';

            updateTotals();
        });

    deleteBtn.addEventListener('click', () => {
        console.log('Deleting product:', product.id);
        selectedProducts.delete(product.id);
            row.remove();
            
            if (selectedProducts.size === 0) {
            console.log('No products left, adding placeholder');
            const placeholder = document.createElement('tr');
            placeholder.className = 'no-products';
            placeholder.innerHTML = '<td colspan="8" class="text-center">Aucun article sélectionné</td>';
            selectedProductsTable.appendChild(placeholder);
            }
            
            updateTotals();
        });

    selectedProducts.set(product.id, product);
        updateTotals();
    console.log('Product added successfully:', product.id);
}

    function updateTotals() {
    console.log('Updating totals');
    let totalHT = 0, totalTVA = 0, totalTTC = 0;

    for (const prod of selectedProducts.values()) {
        totalHT += prod.total_ht;
        totalTVA += prod.tva_amount;
        totalTTC += prod.total_ttc;
        }

    document.querySelector('.total-ht').textContent = totalHT.toFixed(2) + ' MAD';
    document.querySelector('.total-tva').textContent = totalTVA.toFixed(2) + ' MAD';
    document.querySelector('.total-ttc').textContent = totalTTC.toFixed(2) + ' MAD';
    
    console.log('New totals:', { totalHT, totalTVA, totalTTC });
}

// Initialize with existing products
@foreach($devis->products as $product)
    addProductToTable({
        id: {{ $product->id }},
        name: "{{ $product->nom }}",
        description: "{{ $product->description }}",
        price_ht: {{ $product->pivot->price_ht }},
        quantity: {{ $product->pivot->quantity }},
        total_ht: {{ $product->pivot->price_ht * $product->pivot->quantity }},
        tva_amount: {{ $product->pivot->tva_amount }},
        total_ttc: {{ $product->pivot->total_ttc }}
    });
@endforeach

// "Ajouter" button logic
addProductBtn.addEventListener('click', () => {
    console.log('Add button clicked');
    const opt = productSelect.selectedOptions[0];
    if (!opt.value) {
        console.log('No product selected');
        return alert('Veuillez sélectionner un article');
    }
    
    const id = parseInt(opt.value);
    const qty = parseInt(quantityInput.value) || 1;
    
    console.log('Selected product:', { id, qty });
    
    if (selectedProducts.has(id)) {
        console.log('Product already in list:', id);
        return alert('Cet article est déjà dans la liste');
    }

    const price_ht = parseFloat(opt.dataset.price);
    const total_ht = price_ht * qty;
    const tva_amount = total_ht * 0.20;
    const total_ttc = total_ht + tva_amount;

    console.log('Adding new product:', {
        id,
        name: opt.text.split(' - ')[0],
        price_ht,
        total_ht,
        tva_amount,
        total_ttc
    });

    addProductToTable({
        id: id,
        name: opt.text.split(' - ')[0],
        description: opt.dataset.description,
        price_ht: price_ht,
        quantity: qty,
        total_ht: total_ht,
        tva_amount: tva_amount,
        total_ttc: total_ttc
    });

    // reset your form inputs
    productSelect.value = '';
    quantityInput.value = '1';
    console.log('Form inputs reset');
});

    // Form Submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
    console.log('Form submitted');

        if (selectedProducts.size === 0) {
        console.log('No products selected');
            alert('Veuillez ajouter au moins un produit');
            return;
        }

        const formData = {
            date_devis: document.getElementById('date_devis').value,
            validite: document.getElementById('validite').value,
            notes: document.getElementById('notes').value,
        statut: document.getElementById('statut').value,
            products: Array.from(selectedProducts.values()).map(product => ({
                id: product.id,
                quantity: product.quantity,
                price_ht: product.price_ht,
                total_ht: product.total_ht,
            tva_rate: 20.00,
                tva_amount: product.tva_amount,
                total_ttc: product.total_ttc
            }))
        };

    console.log('Submitting form data:', formData);

        try {
        const response = await fetch(`/devis/${devisId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();
        console.log('Server response:', result);

            if (response.ok) {
                alert('Devis mis à jour avec succès');
            window.location.href = `/devis/${devisId}`;
            } else {
                throw new Error(result.message || 'Erreur lors de la mise à jour du devis');
            }
        } catch (error) {
        console.error('Error submitting form:', error);
            alert(error.message || 'Une erreur est survenue');
        }
    });

    // Cancel Button
    document.getElementById('annulerBtn').addEventListener('click', function() {
    console.log('Cancel button clicked');
        if (confirm('Êtes-vous sûr de vouloir annuler les modifications ?')) {
        window.location.href = `/devis/${devisId}`;
        }
    });

window.addEventListener('error', function(e) {
    console.error('Global error:', e.error);
});
</script>