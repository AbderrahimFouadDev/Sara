<div class="edit-facture-container">
    <div class="form-header">
        <h2><i class="fas fa-edit"></i> Modifier la Facture {{ $facture->numero }}</h2>
    </div>

    <form id="editFactureForm" class="facture-form" data-facture-id="{{ $facture->id }}">
        @csrf
        <!-- Fournisseur Information -->
        <div class="form-section">
            <h3>Information Fournisseur</h3>
            <div class="form-group">
                <label>Fournisseur</label>
                <input type="text" class="form-control" value="{{ $facture->fournisseur->fournisseur_name }}" readonly>
            </div>
        </div>

        <!-- Facture Details -->
        <div class="form-section">
            <h3>Détails de la Facture</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="date_facture">Date de la Facture</label>
                    <input type="date" id="date_facture" name="date_facture" class="form-control" 
                           value="{{ $facture->date_facture->format('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label for="groupe_facture">Type de Facture</label>
                    <select id="groupe_facture" name="groupe_facture" class="form-control">
                        <option value="standard" {{ $facture->groupe_facture == 'standard' ? 'selected' : '' }}>Facture Standard</option>
                        <option value="proforma" {{ $facture->groupe_facture == 'proforma' ? 'selected' : '' }}>Facture Proforma</option>
                        <option value="acompte" {{ $facture->groupe_facture == 'acompte' ? 'selected' : '' }}>Facture d'Acompte</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="statut">Statut</label>
                    <select id="statut" name="statut" class="form-control">
                        <option value="pending" {{ $facture->statut == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="paid" {{ $facture->statut == 'paid' ? 'selected' : '' }}>Payée</option>
                        <option value="overdue" {{ $facture->statut == 'overdue' ? 'selected' : '' }}>En retard</option>
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
                    @if($facture->products->isEmpty())
                        <tr class="no-products">
                            <td colspan="8" class="text-center">Aucun article sélectionné</td>
                        </tr>
                    @else
                    @foreach($facture->products as $product)
                    <tr data-product-id="{{ $product->id }}">
                        <td>{{ $product->nom }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ number_format($product->pivot->price_ht, 2) }} MAD</td>
                        <td>
                            <input type="number" class="form-control quantity-input" 
                                   value="{{ $product->pivot->quantity }}" min="1" style="width: 80px">
                        </td>
                            <td class="total-ht-line">{{ number_format($product->pivot->price_ht * $product->pivot->quantity, 2) }} MAD</td>
                            <td class="tva-amount">{{ number_format($product->pivot->tva_amount, 2) }} MAD</td>
                            <td class="total-ttc-line">{{ number_format($product->pivot->total_ttc, 2) }} MAD</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm delete-product">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Total HT:</strong></td>
                        <td class="total-ht">{{ number_format($facture->montant_ht ?? 0, 2) }} MAD</td>
                        <td class="total-tva">{{ number_format($facture->montant_tva ?? 0, 2) }} MAD</td>
                        <td class="total-ttc">{{ number_format($facture->montant_ttc ?? 0, 2) }} MAD</td>
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
                         placeholder="Conditions de paiement, délai de livraison, etc.">{{ $facture->notes }}</textarea>
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
.edit-facture-container {
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
}

.products-table th,
.products-table td {
    padding: 0.75rem;
    border: 1px solid #dee2e6;
}

.products-table th {
    background: #f8f9fa;
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
</style>

<script>
// Initialize factureId and products for the facture.js script
const factureId = {{ $facture->id }};
const initialProducts = [
    @foreach($facture->products as $product)
    {
        id: {{ $product->id }},
        name: "{{ $product->nom }}",
        description: "{{ $product->description }}",
        price_ht: {{ $product->pivot->price_ht }},
        quantity: {{ $product->pivot->quantity }},
        total_ht: {{ $product->pivot->price_ht * $product->pivot->quantity }},
        tva_rate: 20.00,
        tva_amount: {{ $product->pivot->tva_amount }},
        total_ttc: {{ $product->pivot->total_ttc }}
    },
    @endforeach
];
</script>
</div>