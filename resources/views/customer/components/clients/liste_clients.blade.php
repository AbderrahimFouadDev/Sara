{{-- resources/views/customer/components/clients/liste_clients.blade.php --}}

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="list-header">
  <h2>Clients</h2>

  {{-- Add search section --}}
  <div class="search-section">
    <div class="search-input-group">
      <div class="search-box">
        <input type="text" 
               id="searchInput" 
               class="form-control" 
               placeholder="Rechercher par nom, email..."
               style="width: 250px;">
        <button class="search-btn" id="searchBtn">
          <i class="fas fa-search"></i>
        </button>
      </div>
      
      <div class="date-search-box">
        <input type="date" 
               id="dateSearch" 
               class="form-control"
               style="width: 150px;">
        <button class="search-btn" id="dateSearchBtn">
          <i class="fas fa-calendar-search"></i>
        </button>
      </div>

      <button class="btn btn-secondary" id="clearSearch">
        <i class="fas fa-times"></i> Effacer
      </button>
    </div>
  </div>

  <div class="filter-links">
    <a href="#" 
       class="filter-link load-view" 
       data-view="clients/liste_clients"
       data-filter="actif"
       style="--filter-color: #28a745">Actif</a>
       
    <a href="#" 
       class="filter-link load-view" 
       data-view="clients/liste_clients"
       data-filter="inactif"
       style="--filter-color: #6c757d">Inactif</a>
       
    <a href="#" 
       class="filter-link load-view" 
       data-view="clients/liste_clients"
       data-filter="equilibre"
       style="--filter-color: #007bff">Équilibré</a>
       
    <a href="#" 
       class="filter-link load-view" 
       data-view="clients/liste_clients"
       data-filter="deseq"
       style="--filter-color: #dc3545">Déséquilibré</a>
       
    <a href="#" 
       class="filter-link load-view" 
       data-view="clients/liste_clients"
       data-filter="credit"
       style="--filter-color: #fd7e14">Avec crédit</a>
       
    <a href="#" 
       class="filter-link load-view" 
       data-view="clients/liste_clients"
       data-filter="tous"
       style="--filter-color: #343a40">Tous</a>
  </div>

  <div class="header-actions">
    <a href="{{ route('clients.export', ['format' => 'csv']) }}" class="btn-export-custom">
      <i class="fas fa-file-export"></i>
      Exporter en CSV
    </a>
    <button class="btn btn-new load-view" data-view="clients/ajouter_client">
      <i class="fas fa-plus-circle"></i>
      Nouveau
    </button>
  </div>
</div>

<div class="table-responsive">
  <table class="client-table">
    <thead>
      <tr>
        <th>Date</th>
        <th>Client</th>
        <th>Email</th>
        <th>Téléphone</th>
        <th>Balance</th>
        <th>Actif</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    @forelse($clients as $client)
      <tr>
        <td>{{ $client->created_at ? $client->created_at->format('d/m/Y') : 'N/A' }}</td>
        <td>{{ $client->client_name }}</td>
        <td>{{ $client->email }}</td>
        <td>{{ $client->phone_code }} {{ $client->telephone ?? 'N/A' }}</td>
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
              <button type="button" class="action-btn view-btn load-view" data-view="clients/voir_client" data-client-id="{{ $client->id }}" title="Voir">
                  <i class="fas fa-eye"></i>
              </button>
              <button type="button" class="action-btn edit-btn load-view" data-view="clients/edit_client" data-client-id="{{ $client->id }}" title="Éditer">
                  <i class="fas fa-edit"></i>
              </button>
              <button type="button" class="action-btn devis-btn load-view" data-view="devis/creer_devis" data-client-id="{{ $client->id }}" title="Créer un devis">
                  <i class="fas fa-file-alt"></i>
              </button>
              
              
              <button type="button" class="action-btn bon-btn load-view" data-view="bon_livraison/creer_bon_livraison" data-client-id="{{ $client->id }}" title="Créer un bon de livraison">
                  <i class="fas fa-receipt"></i>
              </button>
              <form method="POST" action="{{ route('clients.destroy', $client->id) }}" class="delete-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="action-btn delete-btn" title="Supprimer">
                      <i class="fas fa-trash-alt"></i>
                  </button>
              </form>
          </div>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="7" class="text-center">Aucun client trouvé</td>
      </tr>
    @endforelse
    </tbody>
  </table>
</div>

<style>
.search-section {
    margin: 1rem 0;
}

.search-input-group {
    display: flex;
    gap: 15px;
    align-items: center;
    margin-bottom: 1rem;
}

.search-box, .date-search-box {
    position: relative;
    display: flex;
    align-items: center;
}

.search-box input, .date-search-box input {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.search-box input:focus, .date-search-box input:focus {
    border-color: #40E0D0;
    outline: none;
    box-shadow: 0 0 0 2px rgba(64, 224, 208, 0.2);
}

.search-btn {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 5px;
    transition: color 0.3s ease;
}

.search-btn:hover {
    color: #40E0D0;
}

#clearSearch {
    padding: 8px 16px;
    background-color: #6c757d;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: background-color 0.3s ease;
}

#clearSearch:hover {
    background-color: #5a6268;
}

.highlight {
    background-color: rgba(64, 224, 208, 0.1);
    transition: background-color 0.3s ease;
}

.filter-link {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    color: #666;
    text-decoration: none;
    transition: all 0.3s ease;
}

.filter-link:hover {
    background: var(--filter-color);
    color: white;
}

.filter-link.active {
    background: var(--filter-color);
    color: white;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    background: none;
    border: none;
    padding: 6px;
    cursor: pointer;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.action-btn:hover {
    transform: translateY(-2px);
}

.action-btn.view-btn {
    color: #17a2b8;
}

.action-btn.view-btn:hover {
    color: #138496;
    background-color: rgba(23, 162, 184, 0.1);
}

.action-btn.edit-btn {
    color: #ffc107;
}

.action-btn.edit-btn:hover {
    color: #e0a800;
    background-color: rgba(255, 193, 7, 0.1);
}

.action-btn.devis-btn {
    color: #28a745;
}

.action-btn.devis-btn:hover {
    color: #218838;
    background-color: rgba(40, 167, 69, 0.1);
}

.action-btn.facture-btn {
    color: #6f42c1;
}

.action-btn.facture-btn:hover {
    color: #553c9a;
    background-color: rgba(111, 66, 193, 0.1);
}

.action-btn.bon-btn {
    color: #fd7e14;
}

.action-btn.bon-btn:hover {
    color: #d96b11;
    background-color: rgba(253, 126, 20, 0.1);
}

.action-btn.delete-btn {
    color: #dc3545;
}

.action-btn.delete-btn:hover {
    color: #c82333;
    background-color: rgba(220, 53, 69, 0.1);
}

.delete-form {
    display: inline;
    margin: 0;
    padding: 0;
}

.btn-export-custom {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: #4f46e5;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    font-weight: 500;
}

.btn-export-custom:hover {
    background-color: #4338ca;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    color: white;
    text-decoration: none;
}
</style>
}

.btn-export-custom:hover {
    background-color: #4338ca;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    color: white;
    text-decoration: none;
}
</style>
