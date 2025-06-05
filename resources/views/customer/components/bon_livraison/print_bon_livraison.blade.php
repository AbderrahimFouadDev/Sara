<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon de Livraison #{{ $bon_livraison->numero_bl }}</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
            }
            .print-container {
                padding: 20px;
            }
            .header {
                display: flex;
                justify-content: space-between;
                margin-bottom: 30px;
            }
            .company-info {
                flex: 1;
            }
            .bon-info {
                text-align: right;
            }
            .client-info {
                margin-bottom: 30px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f8f9fa;
            }
            .signature-section {
                display: flex;
                justify-content: space-between;
                margin-top: 50px;
            }
            .signature-box {
                border: 1px solid #ddd;
                padding: 20px;
                width: 45%;
                height: 100px;
            }
            .footer {
                margin-top: 30px;
                text-align: center;
                font-style: italic;
                color: #666;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="print-container">
        <div class="header">
            <div class="company-info">
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
                <h2>{{ $customer->entreprise ?? config('app.name', 'FacturePro') }}</h2>
                <p>{{ $customer->adresse_entreprise ?? 'Adresse non disponible' }}</p>
                <p>Téléphone: {{ $customer->telephone ?? 'Non disponible' }}</p>
                <p>Email: {{ $customer->email ?? Auth::user()->email }}</p>
            </div>
            <div class="bon-info">
                <h1>Bon de Livraison</h1>
                <p>N° {{ $bon_livraison->numero_bl }}</p>
                <p>Date: {{ $bon_livraison->date_livraison->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="client-info">
            <h3>Client</h3>
            <p>{{ $bon_livraison->client->entreprise ?? $bon_livraison->client_name }}</p>
            <p>{{ $bon_livraison->adresse_livraison ?? $bon_livraison->client->adresse ?? 'N/A' }}</p>
            <p>ICE: {{ $bon_livraison->client->ice ?? 'N/A' }}</p>
        </div>

        <table>
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
                <p>Signature & Cachet de l'entreprise</p>
            </div>
            <div class="signature-box">
                <p>Signature du client</p>
            </div>
        </div>

        <div class="footer">
            <p>{{ $bon_livraison->remarques ?? 'Merci de votre confiance - Veuillez signer ce bon de livraison pour confirmer la réception des articles en bon état.' }}</p>
        </div>
    </div>

    <script>
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html> 