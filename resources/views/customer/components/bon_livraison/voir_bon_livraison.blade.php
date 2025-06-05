<style>
    .bon-container {
        background: #fff;
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        position: relative;
    }

    .status-badge {
        position: absolute;
        top: -10px;
        left: -10px;
        background: #f3f4f6;
        padding: 5px 15px;
        transform: rotate(-15deg);
        font-size: 0.9rem;
        color: #666;
        border: 1px solid #ddd;
        box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
    }

    .print-button {
        position: absolute;
        top: 1rem;
        right: 1rem;
    }

    .btn-print {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #40E0D0;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 0.9rem;
        transition: background-color 0.2s;
    }

    .btn-print:hover {
        background: #3BC1B3;
    }

    .company-header {
        display: flex;
        align-items: flex-start;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .company-logo {
        width: 100px;
        height: 100px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e9ecef;
    }

    .company-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .company-info {
        flex: 1;
    }

    .company-name {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .bon-title {
        text-align: right;
        font-size: 1.8rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 2rem;
    }

    .meta-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
        padding: 1rem;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 4px;
    }

    .meta-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
    }

    .meta-label {
        color: #666;
        font-weight: 500;
    }

    .meta-value {
        font-weight: 600;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
    }

    .items-table th,
    .items-table td {
        padding: 0.75rem;
        border: 1px solid #dee2e6;
    }

    .items-table th {
        background: #f8f9fa;
        font-weight: 600;
    }

    .items-table td {
        vertical-align: top;
    }

    .signature-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid #dee2e6;
    }

    .signature-box {
        border: 1px dashed #dee2e6;
        padding: 2rem;
        text-align: center;
        min-height: 150px;
    }

    .signature-title {
        font-weight: 500;
        margin-bottom: 1rem;
        color: #666;
    }

    .footer-note {
        margin-top: 3rem;
        text-align: center;
        color: #666;
        font-style: italic;
    }

    @media print {
        .bon-container {
            box-shadow: none;
            margin: 0;
            padding: 1rem;
        }

        .status-badge,
        .print-button {
            display: none;
        }
    }
</style>

<div class="bon-container">
    <div class="status-badge">{{ ucfirst($bon_livraison->statut ?? 'En cours') }}</div>
    
    <div class="print-button">
        <a href="{{ route('bonlivraison.print', $bon_livraison) }}" target="_blank" class="btn btn-print">
            <i class="fas fa-print"></i> Imprimer
        </a>
    </div>

    <div class="company-header">
        <div class="company-logo">
            @php
                $customer = App\Models\Customer::where('email', Auth::user()->email)->first();
            @endphp
            @if($customer && $customer->photo)
                <img src="{{ asset('storage/' . $customer->photo) }}" alt="Logo de l'entreprise">
            @else
                <img src="{{ asset('images/profile.jpg') }}" alt="Logo de l'entreprise">
            @endif
        </div>
        <div class="company-info">
            @php
                $customer = App\Models\Customer::where('email', Auth::user()->email)->first();
            @endphp
            <div class="company-name">{{ $customer->entreprise ?? 'Votre Entreprise' }}</div>
            <div>{{ $customer->adresse_entreprise ?? 'Adresse non disponible' }}</div>
        </div>
    </div>

    <div class="bon-title">
        Bon de Livraison N° {{ $bon_livraison->numero }}
    </div>

    <div class="meta-grid">
        <div class="meta-item">
            <span class="meta-label">Client:</span>
            <span class="meta-value">{{ $bon_livraison->client_name }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Date:</span>
            <span class="meta-value">{{ $bon_livraison->date_livraison ? $bon_livraison->date_livraison->format('d/m/Y') : 'N/A' }}</span>
        </div>
        
        <div class="meta-item">
            <span class="meta-label">Mode de livraison:</span>
            <span class="meta-value">{{ $bon_livraison->mode_livraison ?? 'Standard' }}</span>
        </div>
        
        <div class="meta-item">
            <span class="meta-label">Adresse de livraison:</span>
            <span class="meta-value">{{ $bon_livraison->adresse_livraison ?? $bon_livraison->client->adresse ?? 'N/A' }}</span>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Référence</th>
                <th>Article</th>
                <th>Description</th>
                <th>Quantité</th>
                <th>Unité</th>
                <th>Remarque</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bon_livraison->products as $product)
            <tr>
                <td>{{ $product->reference }}</td>
                <td>{{ $product->nom }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->pivot->quantity }}</td>
                <td>{{ $product->unite ?? 'Unité' }}</td>
                <td>{{ $product->pivot->remarque ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Aucun produit trouvé</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-title">Signature & Cachet de l'entreprise</div>
        </div>
        <div class="signature-box">
            <div class="signature-title">Signature du client</div>
        </div>
    </div>

    <div class="footer-note">
        {{ $bon_livraison->notes ?? 'Merci de votre confiance - Veuillez signer ce bon de livraison pour confirmer la réception des articles en bon état.' }}
    </div>
</div> 