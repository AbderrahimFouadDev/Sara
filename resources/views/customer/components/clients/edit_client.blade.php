{{-- resources/views/customer/components/clients/edit_client.blade.php --}}
<form id="edit-client-form" method="POST" action="{{ route('clients.update', $client->id) }}">
  @csrf
  @method('PUT')

  <div class="form-header">
    <h2><i class="fas fa-user-edit"></i> Modifier client</h2>
    <div class="form-actions">
      <button type="submit" class="btn btn-save">Enregistrer</button>
      <button type="button" class="btn btn-cancel load-view" data-view="clients/liste_clients">Annuler</button>
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
          <input type="hidden" name="client_actif" value="0">
          <input type="checkbox" id="client-actif" name="client_actif" value="1" {{ $client->client_actif ? 'checked' : '' }}>
          <label for="client-actif">Client actif</label>
        </div>

        <div class="form-group">
          <label for="client-name">Client <span class="text-danger">*</span></label>
          <input type="text" id="client-name" name="client_name" value="{{ old('client_name', $client->client_name) }}" class="form-input @error('client_name') is-invalid @enderror">
          @error('client_name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group checkbox-group">
          <input type="hidden" name="has_tin" value="0">
          <input type="checkbox" id="has-tin" name="has_tin" value="1" {{ $client->has_tin ? 'checked' : '' }}>
          <label for="has-tin">Est soumis au TIN/TVA ?</label>
        </div>

        <div class="form-group tin-section" id="tin-section">
          <label for="tin">TIN / ICE</label>
          <input type="text" id="tin" name="tin" value="{{ old('tin', $client->tin) }}" class="form-input @error('tin') is-invalid @enderror">
          @error('tin')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="autre-id">Autre ID d'acheteur</label>
          <input type="text" id="autre-id" name="autre_id" value="{{ old('autre_id', $client->autre_id) }}" class="form-input @error('autre_id') is-invalid @enderror">
          @error('autre_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="points">Points de fidélité</label>
          <input type="number" id="points" name="points" value="{{ old('points', $client->points) }}" class="form-input @error('points') is-invalid @enderror">
          @error('points')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="solde">Solde</label>
          <input type="number" step="0.01" id="solde" name="solde" value="{{ old('solde', $client->solde) }}" class="form-input @error('solde') is-invalid @enderror">
          @error('solde')
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
          <input type="text" id="contact-personne" name="contact_personne" value="{{ old('contact_personne', $client->contact_personne) }}" class="form-input @error('contact_personne') is-invalid @enderror">
          @error('contact_personne')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group phone-input">
          <label for="telephone">Téléphone</label>
          <div class="phone-wrapper">
            <select name="phone_code" class="country-code">
              <option value="+33" {{ old('phone_code', $client->phone_code)=='+33'?'selected':'' }}>+33</option>
              <option value="+212" {{ old('phone_code', $client->phone_code)=='+212'?'selected':'' }}>+212</option>
            </select>
            <input type="tel" id="telephone" name="telephone" value="{{ old('telephone', $client->telephone) }}" class="form-input @error('telephone') is-invalid @enderror">
          </div>
          @error('telephone')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group phone-input">
          <label for="fax">Numéro de fax</label>
          <div class="phone-wrapper">
            <select name="fax_code" class="country-code">
              <option value="+33" {{ old('fax_code', $client->fax_code)=='+33'?'selected':'' }}>+33</option>
              <option value="+212" {{ old('fax_code', $client->fax_code)=='+212'?'selected':'' }}>+212</option>
            </select>
            <input type="tel" id="fax" name="fax" value="{{ old('fax', $client->fax) }}" class="form-input @error('fax') is-invalid @enderror">
          </div>
          @error('fax')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="{{ old('email', $client->email) }}" class="form-input @error('email') is-invalid @enderror">
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="website">Adresse Web</label>
          <input type="url" id="website" name="website" value="{{ old('website', $client->website) }}" class="form-input @error('website') is-invalid @enderror">
          @error('website')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

    </div> {{-- end left --}}

    {{-- Right column --}}
    <div class="column">

      {{-- Réseau social --}}
      <div class="subsection">
        <h3 class="subsection-header"><i class="fas fa-share-alt"></i> Réseau social</h3>

        @foreach (['linkedin','facebook','twitter','google'] as $social)
          <div class="form-group">
            <label for="{{ $social }}">{{ ucfirst($social) }}</label>
            <input type="url"
                   id="{{ $social }}"
                   name="{{ $social }}"
                   value="{{ old($social, $client->$social) }}"
                   class="form-input @error($social) is-invalid @enderror">
            @error($social)
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        @endforeach
      </div>

      <hr class="divider-sm">

      {{-- Adresse de facturation --}}
      <div class="subsection">
        <h3 class="subsection-header"><i class="fas fa-credit-card"></i> Adresse de facturation</h3>

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
            <input type="text"
                   id="{{ $field }}"
                   name="{{ $field }}"
                   value="{{ old($field, $client->$field) }}"
                   class="form-input @error($field) is-invalid @enderror">
            @error($field)
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        @endforeach

        {{-- Pays --}}
        <div class="form-group">
          <label for="pays">Pays</label>
          <input list="country-list" id="pays" name="pays" value="{{ old('pays', $client->pays) }}" class="form-input @error('pays') is-invalid @enderror" placeholder="Choisissez…">
          <datalist id="country-list">
            <option value="France">
            <option value="Maroc">
            <option value="Belgique">
            <option value="Canada">
            <option value="Tunisie">
          </datalist>
          @error('pays')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

    </div> {{-- end right --}}
  </div>
</form>

@push('styles')
<style>
.form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.form-actions {
  display: flex;
  gap: 1rem;
}

.btn {
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
  font-weight: 500;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-save {
  background-color: #4f46e5;
  color: white;
  border: none;
}

.btn-save:hover {
  background-color: #4338ca;
}

.btn-cancel {
  background-color: #f3f4f6;
  color: #374151;
  border: 1px solid #d1d5db;
}

.btn-cancel:hover {
  background-color: #e5e7eb;
}

.divider {
  border: none;
  border-top: 1px solid #e5e7eb;
  margin: 1.5rem 0;
}

.divider-sm {
  border: none;
  border-top: 1px solid #e5e7eb;
  margin: 1rem 0;
}

.two-column-sections {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 2rem;
}

.subsection {
  margin-bottom: 2rem;
}

.subsection-header {
  font-size: 1.1rem;
  color: #374151;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: #374151;
  font-weight: 500;
}

.form-input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  background-color: white;
}

.form-input:focus {
  outline: none;
  border-color: #4f46e5;
  box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-input.is-invalid {
  border-color: #dc2626;
}

.invalid-feedback {
  color: #dc2626;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.checkbox-group {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.checkbox-group label {
  margin-bottom: 0;
}

.phone-input .phone-wrapper {
  display: flex;
  gap: 0.5rem;
}

.phone-input .country-code {
  width: auto;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  background-color: white;
}

.phone-input .form-input {
  flex: 1;
}

.text-danger {
  color: #dc2626;
}
</style>
@endpush 