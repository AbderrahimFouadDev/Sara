<div class="edit-category-container">
    <div class="form-header">
        <h2>Modifier la catégorie</h2>
        <p class="subtitle">Modifier les informations de la catégorie</p>
    </div>

    <form action="{{ route('categories.update', $category->id) }}" method="POST" class="category-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nom">Nom de la catégorie</label>
            <input type="text" id="nom" name="nom" class="form-control" value="{{ $category->nom }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3">{{ $category->description }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Enregistrer
            </button>
            <button type="button" class="btn btn-secondary load-view" data-view="stock/liste_categories">
                <i class="fas fa-times"></i>
                Annuler
            </button>
        </div>
    </form>
</div>

<style>
.edit-category-container {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    max-width: 600px;
    margin: 0 auto;
}

.form-header {
    margin-bottom: 2rem;
    text-align: center;
}

.form-header h2 {
    color: #2c3e50;
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
}

.subtitle {
    color: #666;
    font-size: 0.9rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #2c3e50;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 4px;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    border: none;
}

.btn-primary {
    background-color: #40E0D0;
    color: white;
}

.btn-primary:hover {
    background-color: #3BC9BA;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
}
</style>


