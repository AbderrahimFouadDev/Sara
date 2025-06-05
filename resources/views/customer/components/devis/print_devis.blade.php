<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Devis #{{ $devis->numero }}</title>
    <style>
    .devis-container {
        background: #fff;
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        position: relative;
    }

    .brouillon-badge {
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

    .devis-title {
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

    .totals-section {
        margin-left: auto;
        width: fit-content;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        gap: 3rem;
    }

    .total-row.final {
        font-weight: bold;
        font-size: 1.1rem;
        border-top: 2px solid #333;
        margin-top: 0.5rem;
        padding-top: 0.5rem;
    }

    .footer-note {
        margin-top: 3rem;
        text-align: center;
        color: #666;
        font-style: italic;
    }

    @media print {
        .devis-container {
            box-shadow: none;
            margin: 0;
            padding: 1rem;
        }

        .brouillon-badge {
            display: none;
        }
    }
    </style>
</head>
<body>
    <div class="devis-container">
        <div class="brouillon-badge">{{ ucfirst($devis->statut) }}</div>

        <div class="company-header">
            <div class="company-logo">
                @php
                    $customer = App\Models\Customer::where('email', Auth::user()->email)->first();
                @endphp
                @if($customer && $customer->photo)
                    <img src="{{ asset('storage/' . $customer->photo) }}" alt="Company Logo">
                @else
                    <img src="{{ asset('images/profile.jpg') }}" alt="Company Logo">
                @endif
            </div>
            <div class="company-info">
                <div class="company-name">{{ $devis->client->entreprise ?? 'N/A' }}</div>
                <div>{{ $devis->client->adresse_entreprise ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="devis-title">
            Devis {{ $devis->numero }}
        </div>

        <div class="meta-grid">
            <div class="meta-item">
                <span class="meta-label">Devis pour:</span>
                <span class="meta-value">{{ $devis->client_name }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Créé par:</span>
                <span class="meta-value">{{ $devis->client->nom }} {{ $devis->client->prenom }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Date Devis:</span>
                <span class="meta-value">{{ $devis->date_devis->format('d/m/Y') }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Expire le:</span>
                <span class="meta-value">{{ $devis->date_devis->addDays(15)->format('d/m/Y') }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Total:</span>
                <span class="meta-value">{{ number_format($devis->montant_ttc, 2) }} MAD</span>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Description</th>
                    <th>Qté</th>
                    <th>Prix</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                @forelse($devis->products as $product)
                <tr>
                    <td>{{ $product->nom }}</td>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td>{{ number_format($product->pivot->price_ht, 2) }} MAD</td>
                    <td>{{ number_format($product->pivot->price_ht * $product->pivot->quantity, 2) }} MAD</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Aucun produit trouvé</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="totals-section">
            <div class="total-row">
                <span>Sous-total HT:</span>
                <span>{{ number_format($devis->montant_ht, 2) }} MAD</span>
            </div>
            <div class="total-row">
                <span>TVA (20%):</span>
                <span>{{ number_format($devis->montant_tva, 2) }} MAD</span>
            </div>
            <div class="total-row final">
                <span>Total TTC:</span>
                <span>{{ number_format($devis->montant_ttc, 2) }} MAD</span>
            </div>
        </div>

        <div class="footer-note">
            {{ $devis->notes ?? 'Merci de votre confiance' }}
        </div>
    </div>

    <script>
        // Automatically open print dialog when the page loads
        window.onload = function() {
            // Add a small delay to ensure the page is fully loaded
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html> 