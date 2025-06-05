<div class="devis-creation-container">
    <div class="devis-header">
        <h2><i class="fas fa-file-invoice"></i> Créer un nouveau devis</h2>
    </div>

  <form id="devisForm" class="devis-form" action="{{ route('devis.store') }}"
          method="POST">
    @csrf

    {{-- HIDDEN for the selected client_id --}}
    <input type="hidden" name="client_id" id="clientId">

    <div class="form-group">
        <label for="searchClient">Client: <span class="required">*</span></label>
        <input type="text"
       id="searchClient"
       name="client_name"
       class="form-control"
       placeholder="Recherchez ou tapez un nouveau..."
       autocomplete="off"
       required>

        <div id="clientSearchResults" class="search-results"></div>
    </div>

    <div class="form-group">
        <label for="dateDevis">Date Devis: <span class="required">*</span></label>
        <input type="date"
               id="dateDevis"
               name="date_devis"
               class="form-control"
               value="{{ date('Y-m-d') }}"
               required>
    </div>

    <div class="form-group">
        <label for="groupeDevis">Groupe Devis:</label>
        <select id="groupeDevis"
                name="groupe_devis"
                class="form-control">
            <option value="estimate">Estimate Default</option>
            <option value="group1">Groupe 1</option>
            <option value="group2">Groupe 2</option>
        </select>
    </div>

    <div class="form-actions">
        <button type="button" class="btn btn-cancel" id="annulerDevis">
            <i class="fas fa-times"></i> Annuler
        </button>
        <button type="submit" class="btn btn-submit">
            <i class="fas fa-check"></i> Soumettre
        </button>
    </div>
</form>
<meta name="csrf-token" content="{{ csrf_token() }}">

</div>

<style>
.devis-creation-container {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    max-width: 800px;
    margin: 2rem auto;
}

.devis-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e0e0e0;
}

.devis-header h2 {
    color: #40E0D0;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.devis-form {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.form-section {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-group label {
    font-weight: 500;
    color: #333;
}

.required {
    color: #dc3545;
}

.form-control {
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    width: 100%;
}

.search-client-wrapper,
.date-input-wrapper {
    position: relative;
}

.search-icon,
.calendar-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    pointer-events: none;
}

.search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 0 0 4px 4px;
    max-height: 200px;
    overflow-y: auto;
    display: none;
    z-index: 1;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 1rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-cancel {
    background: #dc3545;
    color: white;
}

.btn-submit {
    background: #40E0D0;
    color: white;
}

.btn:hover {
    opacity: 0.9;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
.selected-client {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #f0f0f0;
    padding: 0.5rem;
    border-radius: 4px;
}
.selected-client button {
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
    color: #dc3545;
}

</style>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const form            = document.getElementById('devisForm');
  const searchInput     = document.getElementById('searchClient');
  const searchResults   = document.getElementById('clientSearchResults');
  const clientIdInput   = document.getElementById('clientId');
  const annulerBtn      = document.getElementById('annulerDevis');

  // —————————————
  // 1) Search for existing clients
  // —————————————
  searchInput.addEventListener('input', async function() {
    const query = this.value.trim();
    if (query.length < 2) {
      searchResults.style.display = 'none';
      return;
    }

    try {
      const res = await fetch(`/clients/search?q=${encodeURIComponent(query)}`, {
        headers: { 'Accept': 'application/json' }
      });
      const clients = await res.json();

      searchResults.innerHTML = clients.map(c => `
        <div class="search-result-item" data-client-id="${c.id}">
          ${c.client_name}
        </div>
      `).join('');
      searchResults.style.display = 'block';

      document.querySelectorAll('.search-result-item').forEach(item => {
        item.onclick = () => {
          const id   = item.dataset.clientId;
          const name = item.textContent.trim();
          searchInput.value   = name;
          clientIdInput.value = id;
          searchResults.style.display = 'none';
        };
      });
    } catch (err) {
      console.error('Search error:', err);
    }
  });

  // —————————————
  // 2) Submit the form via AJAX
  // —————————————
  form.addEventListener('submit', async e => {
    e.preventDefault();

    const nameVal = searchInput.value.trim();
    if (!nameVal) {
      return alert('Veuillez entrer ou sélectionner un nom de client');
    }

    const payload = {
      client_id:   clientIdInput.value || null,
      client_name: nameVal,
      date_devis:  document.getElementById('dateDevis').value,
      groupe_devis: document.getElementById('groupeDevis').value
    };

    console.log('Submitting payload:', payload);

    try {
      const res = await fetch('/devis', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      });

      if (res.ok) {
        const json = await res.json();
        console.log('Server response:', json);
        alert(json.message || 'Devis créé avec succès !');
        return document.dispatchEvent(new CustomEvent('loadView', {
          detail: { view: 'liste_devis' }
        }));
      }

      if (res.status === 422) {
        const err = await res.json();
        console.warn('Validation errors:', err.errors);
        return alert(Object.values(err.errors).flat().join('\n'));
      }

      const text = await res.text();
      console.error('Unexpected error:', text);
      alert("Une erreur s'est produite. Consultez la console.");
    } catch (err) {
      console.error('Network error:', err);
      alert("Erreur réseau. Consultez la console.");
    }
  });

  // —————————————
  // 3) Cancel button
  // —————————————
  annulerBtn.addEventListener('click', () => {
    document.dispatchEvent(new CustomEvent('loadView', {
      detail: { view: 'liste_devis' }
    }));
  });
});
</script>
@endpush
