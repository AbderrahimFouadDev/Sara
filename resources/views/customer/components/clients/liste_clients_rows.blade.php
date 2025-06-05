@forelse($clients as $client)
<tr>
    <td>{{ $client->created_at->format('Y-m-d') }}</td>
    <td>{{ $client->client_name }}</td>
    <td>{{ $client->email }}</td>
    <td>{{ $client->phone_code }} {{ $client->telephone }}</td>
    <td>{{ number_format($client->solde, 2) }} MAD</td>
    <td>
        @if($client->client_actif)
            <i class="fas fa-check-circle text-success" title="Actif"></i>
        @else
            <i class="fas fa-times-circle text-danger" title="Inactif"></i>
        @endif
    </td>
    <td class="actions-cell">
        <div class="action-buttons">
            <button class="action-btn edit-btn" title="Éditer">
                <i class="fas fa-edit"></i>
            </button>
            <button class="action-btn devis-btn" title="Créer un devis">
                <i class="fas fa-file-alt"></i>
            </button>
            <button class="action-btn facture-btn" title="Créer une facture">
                <i class="fas fa-file-invoice-dollar"></i>
            </button>
            <button class="action-btn bon-btn" title="Créer un bon de commande">
                <i class="fas fa-receipt"></i>
            </button>
            <button class="action-btn delete-btn" title="Supprimer">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center">Aucun client trouvé</td>
</tr>
@endforelse 