<!-- Customer Profile View -->
@php
    $customer = App\Models\Customer::where('email', Auth::user()->email)->first();
@endphp

<!-- Add CSRF token meta tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="profile-container">
    <div class="profile-header">
        <h2>Mon Profil</h2>
    </div>

    <!-- Profile Display Section -->
    <div class="profile-content" id="profile-display">
        <div class="profile-section">
            <div class="profile-photo-section">
                <div class="profile-photo">
                    @if($customer->photo)
                        <img src="{{ asset('storage/' . $customer->photo) }}" alt="Photo de profil" id="profile-image">
                    @else
                        <img src="{{ asset('images/profile.jpg') }}" alt="Photo de profil par défaut" id="profile-image">
                    @endif
                </div>
            </div>

            <div class="profile-info">
                <div class="info-group">
                    <label>Nom complet</label>
                    <p>{{ $customer->nom }} {{ $customer->prenom }}</p>
                </div>
                <div class="info-group">
                    <label>Email</label>
                    <p>{{ $customer->email }}</p>
                </div>
                <div class="info-group">
                    <label>Téléphone</label>
                    <p>{{ $customer->telephone ?? 'Non renseigné' }}</p>
                </div>
                <div class="info-group">
                    <label>Entreprise</label>
                    <p>{{ $customer->entreprise ?? 'Non renseigné' }}</p>
                </div>
                <div class="info-group">
                    <label>Adresse de l'entreprise</label>
                    <p>{{ $customer->adresse_entreprise ?? 'Non renseigné' }}</p>
                </div>
                <div class="info-group">
                    <label>Secteur d'activité</label>
                    <p>{{ $customer->secteur ?? 'Non renseigné' }}</p>
                </div>
                <div class="info-group">
                    <label>Membre depuis</label>
                    <p>{{ $customer->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <div class="profile-actions">
            <button class="edit-profile-btn" onclick="loadComponent('edit_profil_customer')">
                <i class="fas fa-edit"></i> Modifier le profil
            </button>
            
        </div>
    </div>

    <!-- Edit Profile Form Section -->
    <div class="profile-content" id="profile-edit" style="display: none;">
        <form id="edit-profile-form" class="edit-profile-form">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="{{ $customer->nom }}" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="{{ $customer->prenom }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ $customer->email }}" required>
                </div>
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" value="{{ $customer->telephone }}">
                </div>
                <div class="form-group">
                    <label for="entreprise">Entreprise</label>
                    <input type="text" id="entreprise" name="entreprise" value="{{ $customer->entreprise }}">
                </div>
                <div class="form-group">
                    <label for="secteur">Secteur d'activité</label>
                    <input type="text" id="secteur" name="secteur" value="{{ $customer->secteur }}">
                </div>
                <div class="form-group full-width">
                    <label for="adresse_entreprise">Adresse de l'entreprise</label>
                    <textarea id="adresse_entreprise" name="adresse_entreprise" rows="3">{{ $customer->adresse_entreprise }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <button type="button" class="cancel-btn" onclick="hideEditForm()">
                    <i class="fas fa-times"></i> Annuler
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.profile-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    max-width: 1000px;
    margin: 0 auto;
}

.profile-header {
    margin-bottom: 2rem;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 1rem;
}

.profile-header h2 {
    font-size: 1.5rem;
    color: #1f2937;
    font-weight: 600;
}

.profile-section {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.profile-photo-section {
    text-align: center;
}

.profile-photo {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    overflow: hidden;
    margin: 0 auto 1rem;
    border: 4px solid #e5e7eb;
}

.profile-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-info {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.info-group {
    margin-bottom: 1rem;
}

.info-group label {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.info-group p {
    font-size: 1rem;
    color: #1f2937;
    font-weight: 500;
}

.profile-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-start;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
}

.edit-profile-btn,
.change-password-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 0.875rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
}

.edit-profile-btn {
    background: #3b82f6;
    color: white;
    border: none;
}

.edit-profile-btn:hover {
    background: #2563eb;
}

.change-password-btn {
    background: white;
    color: #4b5563;
    border: 1px solid #e5e7eb;
}

.change-password-btn:hover {
    background: #f3f4f6;
}

@media (max-width: 768px) {
    .profile-section {
        grid-template-columns: 1fr;
    }

    .profile-info {
        grid-template-columns: 1fr;
    }

    .profile-actions {
        flex-direction: column;
    }

    .edit-profile-btn,
    .change-password-btn {
        width: 100%;
        justify-content: center;
    }
}

/* Add these new styles for the edit form */
.edit-profile-form {
    background: #fff;
    padding: 2rem;
    border-radius: 12px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group.full-width {
    grid-column: span 2;
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: border-color 0.2s;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
}

.save-btn,
.cancel-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 0.875rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
}

.save-btn {
    background: #3b82f6;
    color: white;
    border: none;
}

.save-btn:hover {
    background: #2563eb;
}

.cancel-btn {
    background: white;
    color: #4b5563;
    border: 1px solid #e5e7eb;
}

.cancel-btn:hover {
    background: #f3f4f6;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full-width {
        grid-column: span 1;
    }

    .form-actions {
        flex-direction: column;
    }

    .save-btn,
    .cancel-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle change password button click
    const changePasswordBtn = document.querySelector('.change-password-btn');
    if (changePasswordBtn) {
        changePasswordBtn.addEventListener('click', function() {
            alert('Fonctionnalité à venir');
        });
    }
});

function showEditForm() {
    document.getElementById('profile-display').style.display = 'none';
    document.getElementById('profile-edit').style.display = 'block';
}

function hideEditForm() {
    document.getElementById('profile-edit').style.display = 'none';
    document.getElementById('profile-display').style.display = 'block';
}
</script> 