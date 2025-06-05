console.log('Facture script loading...');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    
    // Get DOM elements and verify they exist
    const form = document.getElementById('editFactureForm');
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
        return;
    }

    const selectedProducts = new Map();

    function calculateProductTotals(price_ht, quantity) {
        const total_ht = price_ht * quantity;
        const tva_rate = 20.00;
        const tva_amount = total_ht * (tva_rate / 100);
        const total_ttc = total_ht + tva_amount;
        
        return {
            total_ht,
            tva_rate,
            tva_amount,
            total_ttc
        };
    }

    function addProductToTable(product) {
        console.log('Adding product to table:', product);
        
        // Remove "no products" placeholder
        const noProducts = selectedProductsTable.querySelector('.no-products');
        if (noProducts) {
            console.log('Removing no products placeholder');
            noProducts.remove();
        }

        // Calculate totals if not provided
        if (!product.total_ht) {
            const totals = calculateProductTotals(product.price_ht, product.quantity);
            product = { ...product, ...totals };
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
            const totals = calculateProductTotals(prod.price_ht, newQty);
            Object.assign(prod, totals);

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
    if (typeof initialProducts !== 'undefined' && initialProducts.length > 0) {
        console.log('Initializing with existing products:', initialProducts);
        initialProducts.forEach(product => addProductToTable(product));
    }

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
        const totals = calculateProductTotals(price_ht, qty);

        console.log('Adding new product:', {
            id,
            name: opt.text.split(' - ')[0],
            price_ht,
            ...totals
        });

        addProductToTable({
            id: id,
            name: opt.text.split(' - ')[0],
            description: opt.dataset.description,
            price_ht: price_ht,
            quantity: qty,
            ...totals
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
            date_facture: document.getElementById('date_facture').value,
            groupe_facture: document.getElementById('groupe_facture').value,
            notes: document.getElementById('notes').value,
            products: Array.from(selectedProducts.values()).map(product => ({
                id: product.id,
                quantity: product.quantity,
                price_ht: product.price_ht,
                total_ht: product.total_ht,
                tva_rate: product.tva_rate,
                tva_amount: product.tva_amount,
                total_ttc: product.total_ttc
            }))
        };

        console.log('Submitting form data:', formData);

        try {
            const response = await fetch(`/factures/${factureId}`, {
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
                alert('Facture mise à jour avec succès');
                loadComponent('factures/liste_factures');
            } else {
                throw new Error(result.message || 'Erreur lors de la mise à jour de la facture');
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
            loadComponent('factures/liste_factures');
        }
    });
});

window.addEventListener('error', function(e) {
    console.error('Global error:', e.error);
}); 