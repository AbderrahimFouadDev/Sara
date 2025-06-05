<form method="POST" action="{{ route('fournisseurs.store') }}" id="fournisseur-form">
    @csrf

    <!-- Add notification div at the top of the form -->
    <div id="notification" class="notification-message" style="display: none;"></div>

    <div class="form-header">
        <h2><i class="fas fa-truck"></i> Formulaire fournisseur</h2>
        <div class="form-actions">
            <button type="submit" class="btn btn-save">Enregistrer</button>
            <a href="#" class="btn btn-cancel">Annuler</a>
        </div>
    </div>

    <hr class="divider">

    <div class="two-column-sections">
        {{-- Left column --}}
        <div class="column">
            {{-- Informations basiques --}}
            <div class="subsection">
                <h3 class="subsection-header"><i class="fas fa-info-circle"></i> Informations basiques</h3>

                <div class="form-group checkbox-group">
                    <input type="hidden" name="fournisseur_actif" value="0">
                    <input type="checkbox" id="fournisseur-actif" name="fournisseur_actif" value="1" {{ old('fournisseur_actif') ? 'checked' : '' }}>
                    <label for="fournisseur-actif">Fournisseur actif</label>
                </div>

                <div class="form-group">
                    <label for="fournisseur-name">Fournisseur <span class="text-danger">*</span></label>
                    <input type="text" id="fournisseur-name" name="fournisseur_name" value="{{ old('fournisseur_name') }}" class="form-input @error('fournisseur_name') is-invalid @enderror">
                    @error('fournisseur_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group checkbox-group">
                    <input type="hidden" name="has_tin" value="0">
                    <input type="checkbox" id="has-tin" name="has_tin" value="1" {{ old('has_tin') ? 'checked' : '' }}>
                    <label for="has-tin">Est soumis au TIN/TVA ?</label>
                </div>

                <div class="form-group" id="tin-section" style="{{ old('has_tin') ? '' : 'display: none;' }}">
                    <label for="tin">TIN / ICE</label>
                    <input type="text" id="tin" name="tin" value="{{ old('tin') }}" class="form-input @error('tin') is-invalid @enderror">
                    @error('tin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="autre-id">Autre ID de vendeur</label>
                    <input type="text" id="autre-id" name="autre_id_vendeur" value="{{ old('autre_id_vendeur') }}" class="form-input @error('autre_id_vendeur') is-invalid @enderror">
                    @error('autre_id_vendeur')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="debut-balance">Solde initial</label>
                    <input type="number" step="0.01" id="debut-balance" name="debut_balance_fournisseur" value="{{ old('debut_balance_fournisseur') }}" class="form-input @error('debut_balance_fournisseur') is-invalid @enderror">
                    @error('debut_balance_fournisseur')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="divider-sm">

            {{-- Coordonnées contact --}}
            <div class="subsection">
                <h3 class="subsection-header"><i class="fas fa-address-book"></i> Coordonnées contact</h3>

                <div class="form-group">
                    <label for="contact-personne">Personne de contact</label>
                    <input type="text" id="contact-personne" name="contact_personne" value="{{ old('contact_personne') }}" class="form-input @error('contact_personne') is-invalid @enderror">
                    @error('contact_personne')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group phone-input">
                    <label for="telephone">Téléphone</label>
                    <div class="phone-wrapper">
                        <select name="phone_code" class="country-code">
                            <option value="+212" {{ old('phone_code')=='+212'?'selected':'' }}>+212</option>
                            <option value="+33" {{ old('phone_code')=='+33'?'selected':'' }}>+33</option>
                        </select>
                        <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" class="form-input @error('telephone') is-invalid @enderror">
                    </div>
                    @error('telephone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group phone-input">
                    <label for="fax">Numéro de fax</label>
                    <div class="phone-wrapper">
                        <select name="fax_code" class="country-code">
                            <option value="+212" {{ old('fax_code')=='+212'?'selected':'' }}>+212</option>
                            <option value="+33" {{ old('fax_code')=='+33'?'selected':'' }}>+33</option>
                        </select>
                        <input type="tel" id="fax" name="fax" value="{{ old('fax') }}" class="form-input @error('fax') is-invalid @enderror">
                    </div>
                    @error('fax')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="website">Adresse Web</label>
                    <input type="url" id="website" name="website" value="{{ old('website') }}" class="form-input @error('website') is-invalid @enderror">
                    @error('website')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Right column --}}
        <div class="column">
            {{-- Réseau social --}}
            <div class="subsection">
                <h3 class="subsection-header"><i class="fas fa-share-alt"></i> Réseau social</h3>

                @foreach (['linkedin','facebook','twitter','google'] as $social)
                    <div class="form-group">
                        <label for="{{ $social }}">{{ ucfirst($social) }}</label>
                        <input type="url" id="{{ $social }}" name="{{ $social }}" value="{{ old($social) }}" class="form-input @error($social) is-invalid @enderror">
                        @error($social)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </div>

            <hr class="divider-sm">

            {{-- Adresse --}}
            <div class="subsection">
                <h3 class="subsection-header"><i class="fas fa-map-marker-alt"></i> Adresse</h3>

                @foreach ([
                    'adresse'=>'Adresse',
                    'complement'=>'Complément',
                    'adresse_sup'=>'N° d\'adresse supplémentaire',
                    'immeuble'=>'Immeuble N°',
                    'region'=>'Région',
                    'district'=>'Gouvernement / District',
                    'ville'=>'Ville',
                    'code_postal'=>'Code postal',
                ] as $field => $label)
                    <div class="form-group">
                        <label for="{{ $field }}">{{ $label }}</label>
                        <input type="text" id="{{ $field }}" name="{{ $field }}" value="{{ old($field) }}" class="form-input @error($field) is-invalid @enderror">
                        @error($field)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach

                <div class="form-group">
                    <label for="pays">Pays</label>
                    <input list="country-list" id="pays" name="pays" value="{{ old('pays') }}" class="form-input @error('pays') is-invalid @enderror" placeholder="Choisissez…">
                    <datalist id="country-list">
                        <option value="Maroc">
                        <option value="France">
                        <option value="Belgique">
                        <option value="Canada">
                        <option value="Tunisie">
                    </datalist>
                    @error('pays')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hasTinCheckbox = document.getElementById('has-tin');
    const tinSection = document.getElementById('tin-section');
    const form = document.getElementById('fournisseur-form');
    const notification = document.getElementById('notification');

    if (hasTinCheckbox && tinSection) {
        hasTinCheckbox.addEventListener('change', function() {
            tinSection.style.display = this.checked ? 'block' : 'none';
        });
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        try {
            const formData = new FormData(this);
            
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (response.ok) {
                // Show success message
                notification.textContent = data.message;
                notification.className = 'notification-message success';
                notification.style.display = 'block';
                
                // Reset form
                form.reset();
                
                // Reset TIN section visibility
                if (tinSection) {
                    tinSection.style.display = 'none';
                }
            } else {
                // Handle validation errors
                if (data.errors) {
                    let errorMessage = 'Erreurs de validation:';
                    Object.values(data.errors).forEach(error => {
                        errorMessage += '\n- ' + error[0];
                    });
                    notification.textContent = errorMessage;
                } else {
                    notification.textContent = data.message || 'Une erreur est survenue';
                }
                notification.className = 'notification-message error';
                notification.style.display = 'block';
            }
        } catch (error) {
            console.error('Error:', error);
            notification.textContent = 'Une erreur est survenue lors de l\'enregistrement';
            notification.className = 'notification-message error';
            notification.style.display = 'block';
        }

        // Hide notification after 5 seconds
        setTimeout(() => {
            notification.style.display = 'none';
        }, 5000);
    });
});
</script>

<style>
.notification-message {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
    font-weight: 500;
    animation: slideDown 0.3s ease-out;
}

.notification-message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.notification-message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    white-space: pre-line;
}

@keyframes slideDown {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>
@endpush 