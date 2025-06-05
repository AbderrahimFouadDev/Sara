<!-- User Settings View -->
@php
    $customer = App\Models\Customer::where('email', Auth::user()->email)->first();
@endphp

<div class="settings-container">
    <div class="settings-header">
        <h2>Paramètres</h2>
        <p class="settings-description">Gérez vos préférences et paramètres de compte</p>
    </div>

    <div class="settings-grid">
        <!-- Security Section -->
        <div class="settings-section">
            <h3>Sécurité</h3>
            <div class="settings-card">
                <form id="password-form" class="settings-form">
                    @csrf
                    <div class="form-group">
                        <label for="current_password">Mot de passe actuel</label>
                        <input type="password" id="current_password" name="current_password" required>
                        <span class="error-message" id="current_password_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Nouveau mot de passe</label>
                        <input type="password" id="new_password" name="new_password" required>
                        <span class="error-message" id="new_password_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirmation">Confirmer le nouveau mot de passe</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
                        <span class="error-message" id="new_password_confirmation_error"></span>
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-key"></i> Changer le mot de passe
                    </button>
                </form>
            </div>
        </div>

        <!-- Notification Preferences -->
        <div class="settings-section">
            <h3>Notifications</h3>
            <div class="settings-card">
                <form id="notifications-form" class="settings-form">
                    @csrf
                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="email_notifications" checked>
                            Notifications par email
                        </label>
                        <span class="help-text">Recevoir des notifications par email pour les mises à jour importantes</span>
                    </div>
                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="invoice_notifications" checked>
                            Notifications de factures
                        </label>
                        <span class="help-text">Recevoir des notifications pour les nouvelles factures et rappels</span>
                    </div>
                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="marketing_notifications">
                            Communications marketing
                        </label>
                        <span class="help-text">Recevoir des offres spéciales et newsletters</span>
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-bell"></i> Enregistrer les préférences
                    </button>
                </form>
            </div>
        </div>

        <!-- Display Preferences -->
        <div class="settings-section">
            <h3>Affichage</h3>
            <div class="settings-card">
                <form id="display-form" class="settings-form">
                    @csrf
                    <div class="form-group">
                        <label for="language">Langue</label>
                        <select id="language" name="language" class="form-select">
                            <option value="fr" selected>Français</option>
                            <option value="en">English</option>
                            <option value="ar">العربية</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="theme">Thème</label>
                        <select id="theme" name="theme" class="form-select">
                            <option value="light" selected>Clair</option>
                            <option value="dark">Sombre</option>
                            <option value="system">Système</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paint-brush"></i> Appliquer les changements
                    </button>
                </form>
            </div>
        </div>

        <!-- Account Preferences -->
        <div class="settings-section">
            <h3>Compte</h3>
            <div class="settings-card">
                <div class="account-info">
                    <p><strong>Email:</strong> {{ $customer->email }}</p>
                    <p><strong>Membre depuis:</strong> {{ $customer->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="danger-zone">
                    <h4>Zone de danger</h4>
                    <button type="button" class="btn-danger" onclick="confirmAccountDeletion()">
                        <i class="fas fa-trash"></i> Supprimer mon compte
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.settings-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.settings-header {
    margin-bottom: 2rem;
    text-align: center;
}

.settings-header h2 {
    font-size: 1.875rem;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.settings-description {
    color: #6b7280;
    font-size: 1rem;
}

.settings-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

.settings-section {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
}

.settings-section h3 {
    color: #1f2937;
    font-size: 1.25rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e5e7eb;
}

.settings-card {
    background: #f9fafb;
    border-radius: 8px;
    padding: 1.5rem;
}

.settings-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    color: #4b5563;
    margin-bottom: 0.5rem;
}

.form-group input[type="password"],
.form-group input[type="text"],
.form-select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.875rem;
    transition: border-color 0.2s;
}

.form-group input:focus,
.form-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #4b5563;
    cursor: pointer;
}

.help-text {
    font-size: 0.75rem;
    color: #6b7280;
    margin-left: 1.75rem;
}

.btn-primary {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 0.75rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: background-color 0.2s;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-danger {
    background: #ef4444;
    color: white;
    border: none;
    padding: 0.75rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: background-color 0.2s;
}

.btn-danger:hover {
    background: #dc2626;
}

.error-message {
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.account-info {
    margin-bottom: 1.5rem;
}

.account-info p {
    margin-bottom: 0.5rem;
    color: #4b5563;
}

.danger-zone {
    border-top: 1px solid #e5e7eb;
    padding-top: 1.5rem;
    margin-top: 1.5rem;
}

.danger-zone h4 {
    color: #ef4444;
    font-size: 1rem;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }

    .settings-container {
        padding: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password Change Form
    const passwordForm = document.getElementById('password-form');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('/profile/change-password', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Mot de passe modifié avec succès');
                    this.reset();
                } else {
                    alert(data.message || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue lors du changement de mot de passe');
            });
        });
    }

    // Notifications Form
    const notificationsForm = document.getElementById('notifications-form');
    if (notificationsForm) {
        notificationsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('/profile/update-notifications', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Préférences de notification mises à jour');
                } else {
                    alert(data.message || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue lors de la mise à jour des préférences');
            });
        });
    }

    // Display Preferences Form
    const displayForm = document.getElementById('display-form');
    if (displayForm) {
        displayForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('/profile/update-preferences', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Préférences d\'affichage mises à jour');
                    // Reload page to apply new settings
                    window.location.reload();
                } else {
                    alert(data.message || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue lors de la mise à jour des préférences');
            });
        });
    }
});

function confirmAccountDeletion() {
    if (confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')) {
        fetch('/profile/delete', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Compte supprimé avec succès');
                window.location.href = '/';
            } else {
                alert(data.message || 'Une erreur est survenue');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue lors de la suppression du compte');
        });
    }
}
</script> 