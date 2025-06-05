<div class="form-container">
    <div class="form-header">
        <h2><i class="fas fa-plus-circle"></i> Nouvelle Demande de Congé</h2>
    </div>

    <form id="newCongeForm" class="conge-form" action="/conges" method="POST">
        @csrf
        <!-- Employee Selection -->
        <div class="form-section">
            <div class="form-group">
                <label for="salarie_id">Employé*</label>
                <select name="salarie_id" class="form-control" required>
                    <option value="">Sélectionner un employé</option>
                    @foreach($salaries as $salarie)
                        <option value="{{ $salarie->id }}">{{ $salarie->nom_complet }} - {{ $salarie->matricule }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Leave Details -->
        <div class="form-section">
            <div class="form-group">
                <label for="type">Type de Congé*</label>
                <select id="type" name="type" class="form-control" required>
                    <option value="paid">Congé payé</option>
                    <option value="sick">Congé maladie</option>
                    <option value="unpaid">Congé sans solde</option>
                    <option value="other">Autre</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="date_debut">Date de début*</label>
                    <input type="date" id="date_debut" name="date_debut" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="date_fin">Date de fin*</label>
                    <input type="date" id="date_fin" name="date_fin" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label for="motif">Motif*</label>
                <textarea id="motif" name="motif" class="form-control" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label for="document_justificatif">Document Justificatif</label>
                <input type="file" id="document_justificatif" name="document_justificatif" 
                       class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                <small class="form-text text-muted">
                    Formats acceptés: PDF, JPG, PNG. Taille max: 2MB
                </small>
            </div>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="document.dispatchEvent(new CustomEvent('loadView', { detail: { view: 'rh/conges' } }))">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </form>
</div>

<style>
.form-container {
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

.form-group {
    margin-bottom: 1rem;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    color: #374151;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    font-size: 1rem;
}

.form-control:focus {
    border-color: #3b82f6;
    outline: none;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary {
    background-color: #3b82f6;
    color: white;
    border: none;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-secondary {
    background-color: #6b7280;
    color: white;
    border: none;
}

.btn-secondary:hover {
    background-color: #4b5563;
}

.form-text {
    font-size: 0.875rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }

    .form-container {
        margin: 1rem;
        padding: 1rem;
    }
}

.autocomplete-wrapper {
    position: relative;
}

.autocomplete-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    max-height: 200px;
    overflow-y: auto;
    background: white;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    display: none;
}

.autocomplete-item {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #e5e7eb;
}

.autocomplete-item:hover {
    background-color: #f3f4f6;
}

.autocomplete-item .employee-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.autocomplete-item .employee-details {
    display: flex;
    flex-direction: column;
}

.autocomplete-item .employee-name {
    font-weight: 500;
    color: #1f2937;
}

.autocomplete-item .employee-id {
    font-size: 0.875rem;
    color: #6b7280;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newCongeForm');
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');
    const searchInput = document.getElementById('salarie_search');
    const salarieIdInput = document.getElementById('salarie_id');
    const searchResults = document.getElementById('search-results');
    let debounceTimer;

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    dateDebut.min = today;
    dateFin.min = today;

    // Update date_fin min when date_debut changes
    dateDebut.addEventListener('change', function() {
        dateFin.min = this.value;
        if (dateFin.value && dateFin.value < this.value) {
            dateFin.value = this.value;
        }
    });

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value;
        
        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(async () => {
            try {
                const response = await fetch(`/rh/salaries/search?q=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                
                if (data.employees && data.employees.length > 0) {
                    searchResults.innerHTML = data.employees.map(employee => `
                        <div class="autocomplete-item" data-id="${employee.id}" data-name="${employee.nom_complet}">
                            <div class="employee-info">
                                <div class="employee-details">
                                    <span class="employee-name">${employee.nom_complet}</span>
                                    <span class="employee-id">ID: ${employee.matricule}</span>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    searchResults.style.display = 'block';
                } else {
                    searchResults.innerHTML = '<div class="autocomplete-item">Aucun employé trouvé</div>';
                    searchResults.style.display = 'block';
                }
            } catch (error) {
                console.error('Error searching employees:', error);
            }
        }, 300);
    });

    // Handle click on search results
    searchResults.addEventListener('click', function(e) {
        const item = e.target.closest('.autocomplete-item');
        if (item) {
            const id = item.dataset.id;
            const name = item.dataset.name;
            
            searchInput.value = name;
            salarieIdInput.value = id;
            searchResults.style.display = 'none';
        }
    });

    // Hide results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

    // Show results when focusing on input if it has a value
    searchInput.addEventListener('focus', function() {
        if (this.value.length >= 2) {
            searchResults.style.display = 'block';
        }
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        try {
            const response = await fetch('/conges', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (response.ok) {
                alert('Demande de congé créée avec succès');
                // Redirect to conges list
                document.dispatchEvent(new CustomEvent('loadView', {
                    detail: { view: 'rh/conges' }
                }));
            } else {
                throw new Error(result.message || 'Erreur lors de la création de la demande');
            }
        } catch (error) {
            alert(error.message);
        }
    });
});

function retourListe() {
    document.dispatchEvent(new CustomEvent('loadView', {
        detail: { view: 'rh/conges' }
    }));
}
</script> 