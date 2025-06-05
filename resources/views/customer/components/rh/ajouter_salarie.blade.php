{{-- resources/views/customer/components/rh/ajouter_salarie.blade.php --}}
<div class="employee-form-container">
    <div class="form-header">
        <div class="header-icon">
            <i class="fas fa-user-plus"></i>
        </div>
        <h2>Ajouter un Salarié</h2>
        <p class="header-subtitle">Enregistrer un nouveau membre de l'équipe</p>
    </div>

    <form id="employeeForm" method="POST" action="{{ route('rh.salaries.store') }}" class="employee-form" enctype="multipart/form-data">
        @csrf
        
        <!-- Informations personnelles -->
        <div class="form-section">
            <h4><i class="fas fa-id-card"></i> Informations Personnelles</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label for="photo">Photo</label>
                    <div class="photo-upload-section">
                        <div class="photo-preview-container">
                            <img id="photoPreview" src="{{ asset('images/default-avatar.png') }}" alt="Photo preview">
                        </div>
                        <div class="upload-button">
                            <input type="file" id="photo" name="photo" accept="image/*" onchange="previewImage(this)">
                            <label for="photo">
                                <i class="fas fa-camera"></i>
                                Choisir une photo
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nom_complet">Nom Complet *</label>
                    <input type="text" id="nom_complet" name="nom_complet" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="cin">CIN *</label>
                    <input type="text" id="cin" name="cin" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="cnss">Numéro CNSS</label>
                    <input type="text" id="cnss" name="cnss" class="form-control">
                </div>
            </div>
        </div>

        <!-- Informations professionnelles -->
        <div class="form-section">
            <h3>Informations Professionnelles</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="poste">Poste *</label>
                    <input type="text" id="poste" name="poste" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="departement">Département *</label>
                    <input type="text" id="departement" name="departement" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="statut">Statut *</label>
                    <select id="statut" name="statut" required class="form-control">
                        <option value="actif">Actif</option>
                        <option value="congé">En Congé</option>
                        <option value="quitté">Quitté</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Contrat et Rémunération -->
        <div class="form-section">
            <h3>Contrat et Rémunération</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="type_contrat">Type de Contrat *</label>
                    <select id="type_contrat" name="type_contrat" required class="form-control">
                        <option value="CDI">CDI</option>
                        <option value="CDD">CDD</option>
                        <option value="Stage">Stage</option>
                        <option value="Interim">Intérim</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="date_embauche">Date d'Embauche *</label>
                    <input type="date" id="date_embauche" name="date_embauche" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="date_debut_contrat">Date Début Contrat *</label>
                    <input type="date" id="date_debut_contrat" name="date_debut_contrat" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="date_fin_contrat">Date Fin Contrat</label>
                    <input type="date" id="date_fin_contrat" name="date_fin_contrat" class="form-control">
                </div>

                <div class="form-group">
                    <label for="salaire_base">Salaire de Base *</label>
                    <input type="number" id="salaire_base" name="salaire_base" required class="form-control" step="0.01">
                </div>
            </div>
        </div>

        <!-- Documents -->
        <div class="form-section">
            <h3>Documents</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="document_cin">Copie CIN</label>
                    <input type="file" id="document_cin" name="document_cin" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="form-group">
                    <label for="document_cnss">Document CNSS</label>
                    <input type="file" id="document_cnss" name="document_cnss" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="form-group">
                    <label for="document_contrat">Contrat Signé</label>
                    <input type="file" id="document_contrat" name="document_contrat" class="form-control" accept=".pdf">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Enregistrer
            </button>
            <button type="button" class="btn btn-secondary" onclick="history.back()">
                <i class="fas fa-times"></i>
                Annuler
            </button>
        </div>
    </form>
</div>

<style>
.employee-form-container {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.05);
    margin: 2rem;
}

.form-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #f8f9fa;
}

.header-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #40E0D0, #3498db);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.header-icon i {
    font-size: 2.5rem;
    color: white;
}

.form-header h2 {
    color: #2c3e50;
    font-size: 2rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.header-subtitle {
    color: #6c757d;
    font-size: 1rem;
}

.form-section {
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 15px;
    margin-bottom: 2rem;
}

.form-section h3 {
    color: #2c3e50;
    font-size: 1.2rem;
    margin-bottom: 1.5rem;
    font-weight: 600;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #2c3e50;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.form-control:focus {
    border-color: #40E0D0;
    box-shadow: 0 0 0 3px rgba(64, 224, 208, 0.2);
    outline: none;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid #f8f9fa;
}

.btn {
    padding: 0.8rem 1.5rem;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #40E0D0, #3498db);
    color: white;
}

.btn-secondary {
    background: #64748b;
    color: white;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .employee-form-container {
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

.photo-upload-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    margin-top: 10px;
}

.photo-preview-container {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #e2e8f0;
    background: #f8fafc;
}

.photo-preview-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.upload-button {
    position: relative;
    width: 100%;
    max-width: 200px;
}

.upload-button input[type="file"] {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
}

.upload-button label {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 20px;
    background: #f3f4f6;
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    color: #4b5563;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.upload-button:hover label {
    background: #e5e7eb;
    border-color: #9ca3af;
}

.upload-button i {
    font-size: 1.2em;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('employeeForm');
    const submitBtn = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';

        const formData = new FormData(this);
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                alert(data.message || 'Salarié ajouté avec succès!');
                document.dispatchEvent(new CustomEvent('loadView', {
                    detail: { view: 'rh/salaries' }
                }));
            } else {
                throw new Error(data.message || 'Une erreur est survenue');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Erreur lors de l\'ajout du salarié: ' + error.message);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Enregistrer';
        }
    });

    // Add date validation for contract dates
    const dateDebutContrat = document.getElementById('date_debut_contrat');
    const dateFinContrat = document.getElementById('date_fin_contrat');
    const typeContrat = document.getElementById('type_contrat');

    // Update date_fin_contrat validation based on type_contrat
    typeContrat.addEventListener('change', function() {
        if (this.value === 'CDI') {
            dateFinContrat.value = '';
            dateFinContrat.disabled = true;
        } else {
            dateFinContrat.disabled = false;
        }
    });

    // Ensure date_fin_contrat is after date_debut_contrat
    dateFinContrat.addEventListener('change', function() {
        if (this.value && dateDebutContrat.value) {
            if (new Date(this.value) <= new Date(dateDebutContrat.value)) {
                alert('La date de fin de contrat doit être postérieure à la date de début');
                this.value = '';
            }
        }
    });

    // Initialize contract type state
    if (typeContrat.value === 'CDI') {
        dateFinContrat.value = '';
        dateFinContrat.disabled = true;
    }
});

function previewImage(input) {
    const preview = document.getElementById('photoPreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script> 