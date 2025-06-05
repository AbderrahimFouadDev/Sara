<table class="users-table">
    <thead>
        <tr>
            <th>
                <input type="checkbox" id="selectAllUsers">
            </th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Entreprise</th>
            <th>Adresse</th>
            <th>Secteur</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customers as $customer)
        <tr data-user-id="{{ $customer->id }}">
            <td>
                <input type="checkbox" class="user-select" value="{{ $customer->id }}">
            </td>
            <td>{{ $customer->nom }}</td>
            <td>{{ $customer->prenom }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->telephone }}</td>
            <td>{{ $customer->entreprise ?? '-' }}</td>
            <td>{{ $customer->adresse_entreprise ?? '-' }}</td>
            <td>{{ $customer->secteur ?? '-' }}</td>
            <td>
                <span class="status {{ $customer->status ?? 'active' }}">
                    {{ ucfirst($customer->status ?? 'actif') }}
                </span>
            </td>
            <td class="actions">
                <button onclick="showUserDetails({{ $customer->id }})" class="action-btn view" title="Voir">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="action-btn toggle-status" 
                        onclick="toggleUserStatus(this, {{ $customer->id }})"
                        data-status="{{ $customer->status ?? 'active' }}"
                        title="{{ ($customer->status ?? 'active') == 'active' ? 'Désactiver' : 'Activer' }}">
                    <i class="fas {{ ($customer->status ?? 'active') == 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                </button>
                <button class="action-btn delete" 
                        onclick="deleteUser(this, {{ $customer->id }})"
                        title="Supprimer">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table> 