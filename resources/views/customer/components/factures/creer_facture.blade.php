<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Créer une facture</title>

  {{-- 1) CSRF token for JS --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- your CSS here --}}
</head>
<body>

  <div class="facture-creation-container">
    <div class="facture-header">
      <h2><i class="fas fa-file-invoice-dollar"></i> Créer une nouvelle facture</h2>
    </div>

    {{-- 2) form with action + method --}}
    <form id="factureForm"
          class="facture-form"
          action="{{ route('factures.store') }}"
          method="POST">
      @csrf

      {{-- HIDDEN for the selected fournisseur_id --}}
      <input type="hidden" name="id_fournisseur" id="fournisseurId">

      <div class="form-group">
        <label for="searchFournisseur">Fournisseur: <span class="required">*</span></label>
        <input
          type="text"
          id="searchFournisseur"
          name="fournisseur_name"
          class="form-control"
          placeholder="Recherchez ou tapez un nouveau..."
          autocomplete="off"
          required>
        <div id="fournisseurSearchResults" class="search-results"></div>
      </div>

      <div class="form-group">
        <label for="dateFacture">Date Facture: <span class="required">*</span></label>
        <input type="date"
               id="dateFacture"
               name="date_facture"
               class="form-control"
               value="{{ date('Y-m-d') }}"
               required>
      </div>

      <div class="form-group">
        <label for="groupeFacture">Type de Facture:</label>
        <select id="groupeFacture"
                name="groupe_facture"
                class="form-control">
          <option value="standard">Facture Standard</option>
          <option value="proforma">Facture Proforma</option>
          <option value="acompte">Facture d'Acompte</option>
        </select>
      </div>

      <div class="form-actions">
        <button type="button" class="btn btn-cancel" id="annulerFacture">
          <i class="fas fa-times"></i> Annuler
        </button>
        <button type="submit" class="btn btn-submit">
          <i class="fas fa-check"></i> Soumettre
        </button>
      </div>
    </form>
  </div>

<style>
.facture-creation-container {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    max-width: 800px;
    margin: 2rem auto;
}

.facture-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e0e0e0;
}

.facture-header h2 {
    color: #40E0D0;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.facture-form {
    display: flex;
    flex-direction: column;
    gap: 2rem;
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

.search-result-item {
    padding: 0.5rem 1rem;
    cursor: pointer;
}

.search-result-item:hover {
    background: #f8f9fa;
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
    .facture-creation-container {
        margin: 1rem;
        padding: 1rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('factureForm');
      const searchInput = document.getElementById('searchFournisseur');
      const searchResults = document.getElementById('fournisseurSearchResults');
      const fournisseurIdInput = document.getElementById('fournisseurId');
      const annulerBtn = document.getElementById('annulerFacture');

      // —————————————
      // 1) Search for existing fournisseurs
      // —————————————
      searchInput.addEventListener('input', async function() {
          const query = this.value.trim();
          if (query.length < 2) {
              searchResults.style.display = 'none';
              return;
          }

          try {
              const res = await fetch(`/fournisseurs/search?q=${encodeURIComponent(query)}`, {
                  headers: { 'Accept': 'application/json' }
              });
              const fournisseurs = await res.json();

              searchResults.innerHTML = fournisseurs.map(f => `
                  <div class="search-result-item" data-fournisseur-id="${f.id}">
                      ${f.fournisseur_name}
                  </div>
              `).join('');
              searchResults.style.display = 'block';

              document.querySelectorAll('.search-result-item').forEach(item => {
                  item.onclick = () => {
                      const id = item.dataset.fournisseurId;
                      const name = item.textContent.trim();
                      searchInput.value = name;
                      fournisseurIdInput.value = id;
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
              return alert('Veuillez entrer ou sélectionner un nom de fournisseur');
          }

          const payload = {
              id_fournisseur: fournisseurIdInput.value || null,
              fournisseur_name: nameVal,
              date_facture: document.getElementById('dateFacture').value,
              groupe_facture: document.getElementById('groupeFacture').value
          };

          console.log('Submitting payload:', payload);

          try {
              const res = await fetch('/factures', {
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
                  alert(json.message || 'Facture créée avec succès !');
                  return document.dispatchEvent(new CustomEvent('loadView', {
                      detail: { view: 'liste_factures' }
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
              detail: { view: 'liste_factures' }
          }));
      });
  });
  </script>
</body>
</html>