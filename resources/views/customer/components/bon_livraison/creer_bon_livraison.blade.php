<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Créer un bon de livraison</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

  <div class="facture-creation-container">
    <div class="facture-header">
      <h2><i class="fas fa-truck"></i> Créer un bon de livraison</h2>
    </div>

    <form id="bonLivraisonForm"
          action="{{ route('bonlivraison.store') }}"
          method="POST"
          class="facture-form">
      @csrf

      <input type="hidden" name="client_id" id="clientId">

      <div class="form-group">
        <label for="searchClient">Client: <span class="required">*</span></label>
        <input
          type="text"
          id="searchClient"
          name="client_name"
          class="form-control"
          placeholder="Recherchez ou tapez un nouveau…"
          autocomplete="off"
          required>
        <div id="clientSearchResults" class="search-results"></div>
      </div>

      <div class="form-group">
        <label for="dateLivraison">Date de Livraison: <span class="required">*</span></label>
        <input type="date"
               id="dateLivraison"
               name="date_livraison"
               class="form-control"
               value="{{ date('Y-m-d') }}"
               required>
      </div>

      <div class="form-group">
        <label for="adresseLivraison">Adresse de Livraison:</label>
        <input type="text"
               id="adresseLivraison"
               name="adresse_livraison"
               class="form-control"
               >
      </div>

      <div class="form-group">
        <label for="modeTransport">Mode de Transport:</label>
        <input type="text"
               id="modeTransport"
               name="mode_transport"
               class="form-control"
               >
      </div>

      <div class="form-group">
        <label for="refCommande">Référence Commande:</label>
        <input type="text"
               id="refCommande"
               name="reference_commande"
               class="form-control"
              >
      </div>

      <div class="form-actions">
        <button type="button" class="btn btn-cancel" id="annulerLivraison">
          <i class="fas fa-times"></i> Annuler
        </button>
        <button type="submit" class="btn btn-submit" id="submitLivraison">
          <i class="fas fa-check"></i> Soumettre
        </button>
      </div>
    </form>
  </div>

  <style>
    {{--- identical style from your example with color adapted if you want ---}}
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
        color: #ff9933;
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
        background: #ff9933;
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
  document.addEventListener('DOMContentLoaded', function() {
      const form          = document.getElementById('bonLivraisonForm');
      const url           = form.getAttribute('action');
      const searchInput   = document.getElementById('searchClient');
      const searchResults = document.getElementById('clientSearchResults');
      const clientIdInput = document.getElementById('clientId');
      const annulerBtn    = document.getElementById('annulerLivraison');

      // Client search
      searchInput.addEventListener('input', async function() {
          if (this.value.length < 2) {
              searchResults.style.display = 'none';
              return;
          }
          try {
              const resp = await fetch(`/clients/search?q=${encodeURIComponent(this.value)}`, {
                  headers: {
                      'Accept': 'application/json',
                      'X-Requested-With': 'XMLHttpRequest'
                  }
              });
              const clients = await resp.json();
              searchResults.innerHTML = clients.map(c => `
                  <div class="search-result-item" data-client-id="${c.id}">
                    ${c.client_name}
                  </div>
              `).join('');
              searchResults.style.display = 'block';

              document.querySelectorAll('.search-result-item').forEach(item => {
                  item.addEventListener('click', function() {
                      searchInput.value   = this.textContent.trim();
                      clientIdInput.value = this.dataset.clientId;
                      searchResults.style.display = 'none';
                  });
              });
          } catch (err) {
              console.error('Error searching clients:', err);
          }
      });

      // Form submission
      form.addEventListener('submit', async function(e) {
          e.preventDefault();

          const formData = new FormData(form);
          const payload  = Object.fromEntries(formData.entries());

          try {
              const response = await fetch(url, {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                      'Accept': 'application/json'
                  },
                  body: JSON.stringify(payload)
              });

              if (!response.ok) {
                  const error = await response.json();
                  throw new Error(error.message || 'Erreur lors de la création du bon de livraison');
              }

              const result = await response.json();
              if (result.success) {
                  alert('Bon de livraison créé avec succès!');
                  document.dispatchEvent(new CustomEvent('loadView', { 
                      detail: { view: 'liste_bon_livraison' }
                  }));
              }
          } catch (error) {
              console.error('Error:', error);
              alert(error.message || 'Erreur lors de la création du bon de livraison');
          }
      });

      // Cancel button
      annulerBtn.addEventListener('click', function() {
          document.dispatchEvent(new CustomEvent('loadView', { 
              detail: { view: 'liste_bon_livraison' }
          }));
      });
  });
  </script>
</body>
</html>