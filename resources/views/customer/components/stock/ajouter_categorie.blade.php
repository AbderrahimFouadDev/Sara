{{-- resources/views/customer/components/stock/ajouter_categorie.blade.php --}}
<div class="category-form-container">
    <div class="form-header">
        <div class="header-icon">
            <i class="fas fa-folder-plus"></i>
        </div>
        <h2>Ajouter une Catégorie</h2>
        <p class="header-subtitle">Créez une nouvelle catégorie pour organiser vos articles et services</p>
    </div>

    <form id="categoryForm" method="POST" action="{{ route('categories.store') }}" class="category-form">
        @csrf
        @method('POST')

        <div class="form-group">
            <label for="nom">
                <i class="fas fa-tag input-icon"></i>
                Nom de la catégorie *
            </label>
            <input type="text" id="nom" name="nom" required class="form-control" placeholder="Entrez le nom de la catégorie">
        </div>

        <div class="form-group">
            <label for="description">
                <i class="fas fa-align-left input-icon"></i>
                Description
            </label>
            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Décrivez la catégorie..."></textarea>
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
.category-form-container {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.05);
    max-width: 800px;
    margin: 2rem auto;
}

.form-header {
    text-align: center;
    margin-bottom: 3rem;
    padding-bottom: 2rem;
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

.category-form {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.form-group {
    margin-bottom: 0;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    color: #2c3e50;
    font-weight: 500;
    font-size: 0.95rem;
}

.input-icon {
    color: #40E0D0;
    width: 20px;
}

.form-control {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8fafc;
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
    .category-form-container {
        margin: 1rem;
        padding: 1.5rem;
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
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('categoryForm');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const url = form.getAttribute('action');
            const method = form.getAttribute('method');
            const nom = document.getElementById('nom').value;
            const description = document.getElementById('description').value;

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ nom, description })
                });

                const data = await response.json();

                if (response.ok) {
                    alert('Catégorie ajoutée avec succès !');
                    form.reset();
                } else {
                    alert('Erreur: ' + (data.message || 'Vérifiez les champs et réessayez.'));
                    console.error(data);
                }
            } catch (error) {
                console.error('Erreur lors de la requête :', error);
                alert('Une erreur est survenue. Veuillez réessayer plus tard.');
            }
        });
    });
</script>
