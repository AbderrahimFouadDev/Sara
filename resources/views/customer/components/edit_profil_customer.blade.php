<!-- Edit Customer Profile View -->
@php
    $customer = App\Models\Customer::where('email', Auth::user()->email)->first();
@endphp

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="profile-container">
    <div class="profile-header">
        <h2>Modifier Mon Profil</h2>
    </div>

    <div class="profile-content">
        <form id="edit-profile-form" class="edit-profile-form" method="POST" action="{{ route('profile.update.ajax') }}" enctype="multipart/form-data">
            @csrf

            <div class="profile-section">
                <div class="profile-photo-section">
                    <div class="profile-photo">
                        @if($customer->photo)
                            <img src="{{ asset('storage/' . $customer->photo) }}" alt="Photo de profil" id="preview-photo">
                        @else
                            <img src="{{ asset('images/profile.jpg') }}" alt="Photo de profil par défaut" id="preview-photo">
                        @endif
                    </div>
                    <div class="photo-upload-section">
                        <label for="photo" class="change-photo-btn">
                            <i class="fas fa-camera"></i> Changer la photo
                        </label>
                        <input type="file" id="photo" name="photo" accept="image/*" style="display: none;">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" value="{{ $customer->nom }}" required>
                        @error('nom')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" value="{{ $customer->prenom }}" required>
                        @error('prenom')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ $customer->email }}" required>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" value="{{ $customer->telephone }}">
                        @error('telephone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="entreprise">Entreprise</label>
                        <input type="text" id="entreprise" name="entreprise" value="{{ $customer->entreprise }}">
                        @error('entreprise')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="secteur">Secteur d'activité</label>
                        <input type="text" id="secteur" name="secteur" value="{{ $customer->secteur }}">
                        @error('secteur')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label for="adresse_entreprise">Adresse de l'entreprise</label>
                        <textarea id="adresse_entreprise" name="adresse_entreprise" rows="3">{{ $customer->adresse_entreprise }}</textarea>
                        @error('adresse_entreprise')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <button type="button" class="cancel-btn" onclick="loadComponent('profil_customer')">
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

.photo-upload-section {
    margin-top: 1rem;
}

.change-photo-btn {
    background: #f3f4f6;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    color: #4b5563;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.change-photo-btn:hover {
    background: #e5e7eb;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.form-group {
    margin-bottom: 1rem;
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

.error-message {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
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
    .profile-section {
        grid-template-columns: 1fr;
    }

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
    console.log('Initializing edit profile form...');

    const photoInput = document.getElementById('photo');
    const previewPhoto = document.getElementById('preview-photo');
    const editForm = document.getElementById('edit-profile-form');

    // Handle photo preview
    if (photoInput && previewPhoto) {
        photoInput.addEventListener('change', function(e) {
            console.log('Photo input changed');
            const file = this.files[0];
            if (file) {
                console.log('File selected:', file.name);
                if (!file.type.startsWith('image/')) {
                    console.error('Invalid file type');
                    alert('Veuillez sélectionner une image valide (JPG, PNG, etc.)');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    console.log('Preview loaded');
                    previewPhoto.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Handle form submission
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submission started');

            const formData = new FormData(this);

            fetch('/profile/update', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Server response:', data);
                if (data.success) {
                    alert('Profil mis à jour avec succès');
                    loadComponent('profil_customer');
                } else {
                    throw new Error(data.message || 'Erreur lors de la mise à jour');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la mise à jour: ' + error.message);
            });
        });
    }
});
</script> 