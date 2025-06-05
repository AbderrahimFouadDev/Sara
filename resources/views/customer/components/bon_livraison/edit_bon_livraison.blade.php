{{-- No @extends needed since this is loaded as a component --}}

<div class="edit-bon-livraison-container">
    <div class="form-header">
        <h2><i class="fas fa-edit"></i> Modifier le Bon de Livraison {{ $bon_livraison->numero_bl }}</h2>
    </div>

    <form id="editBonLivraisonForm" 
          class="bon-livraison-form" 
          data-bon-livraison-id="{{ $bon_livraison->id }}"
          data-products="{{ json_encode($bon_livraison->products->map(function($product) {
              return [
                  'id' => $product->id,
                  'name' => $product->nom,
                  'description' => $product->description,
                  'price_ht' => number_format((float)$product->pivot->prix_unitaire, 2, '.', ''),
                  'quantity' => (int)$product->pivot->quantity,
                  'remise_percent' => number_format((float)$product->pivot->remise_percent, 2, '.', ''),
                  'remise_amount' => number_format((float)$product->pivot->remise_amount, 2, '.', ''),
                  'total_ht' => number_format((float)$product->pivot->total_ht, 2, '.', ''),
                  'tva_amount' => number_format((float)$product->pivot->tva_amount, 2, '.', ''),
                  'total_ttc' => number_format((float)$product->pivot->total_ttc, 2, '.', '')
              ];
          })) }}"
          data-client-name="{{ $bon_livraison->client->client_name }}">
        @csrf
        <!-- Client Information -->
        <div class="form-section">
            <h3>Information Client</h3>
            <div class="form-group">
                <label>Client</label>
                <input type="text" class="form-control" value="{{ $bon_livraison->client->client_name }}" readonly>
            </div>
        </div>

        <!-- Bon de Livraison Details -->
        <div class="form-section">
            <h3>Détails du Bon de Livraison</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="date_livraison">Date du Bon de Livraison</label>
                    <input type="date" id="date_livraison" name="date_livraison" class="form-control" 
                           value="{{ $bon_livraison->date_livraison ? $bon_livraison->date_livraison->format('Y-m-d') : date('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label for="etat">Statut</label>
                    <select id="etat" name="etat" class="form-control">
                        <option value="pending" {{ $bon_livraison->etat == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="delivered" {{ $bon_livraison->etat == 'delivered' ? 'selected' : '' }}>Livré</option>
                        <option value="cancelled" {{ $bon_livraison->etat == 'cancelled' ? 'selected' : '' }}>Annulé</option>
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
                                    data-name="{{ $product->nom }}"
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
                        <th>Remise %</th>
                        <th>Montant Remise</th>
                        <th>Total HT</th>
                        <th>TVA (20%)</th>
                        <th>Total TTC</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="selectedProducts">
                    <tr class="no-products">
                        <td colspan="10" class="text-center">Aucun article sélectionné</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total HT:</strong></td>
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
                         placeholder="Conditions de livraison, instructions spéciales, etc.">{{ $bon_livraison->notes }}</textarea>
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
.edit-bon-livraison-container {
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
    overflow-x: auto;
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
    font-size: 0.9rem;
    max-width: 100%;
    overflow-x: auto;
}

.products-table th,
.products-table td {
    padding: 0.6rem;
    border: 1px solid #dee2e6;
    white-space: nowrap;
}

.products-table th {
    background: #f8f9fa;
    font-size: 0.85rem;
    font-weight: 600;
}

.products-table th:nth-child(4),
.products-table td:nth-child(4) {
    width: 80px;
    min-width: 80px;
}

.products-table th:last-child,
.products-table td:last-child {
    width: 60px;
    min-width: 60px;
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
    background: #dc3545;
    color: white;
    border: none;
}

.products-table .form-control {
    padding: 0.4rem;
    width: 70px;
}
</style>

<script type="text/javascript">
// Add debug log
console.log('BON_LIVRAISON_DATA:', window.BON_LIVRAISON_DATA);
</script> 