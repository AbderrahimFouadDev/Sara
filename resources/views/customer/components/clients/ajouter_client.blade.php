{{-- resources/views/customer/components/ajouter_client.blade.php --}}
<form method="POST" action="{{ route('clients.store') }}">
  @csrf

  <div class="form-header">
    <h2><i class="fas fa-user-plus"></i> Formulaire client</h2>
    <div class="form-actions">
      <button type="submit" class="btn btn-save">Enregistrer</button>
      <a href="{{ url()->previous() }}" class="btn btn-cancel">Annuler</a>
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
          {{-- Hidden so we always get a 0 or 1 --}}
          <input type="hidden" name="client_actif" value="0">
          <input type="checkbox" id="client-actif" name="client_actif" value="1" {{ old('client_actif') ? 'checked' : '' }}>
          <label for="client-actif">Client actif</label>
        </div>

        <div class="form-group">
          <label for="client-name">Client <span class="text-danger">*</span></label>
          <input type="text" id="client-name" name="client_name" value="{{ old('client_name') }}" class="form-input @error('client_name') is-invalid @enderror">
          @error('client_name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group checkbox-group">
          <input type="hidden" name="has_tin" value="0">
          <input type="checkbox" id="has-tin" name="has_tin" value="1" {{ old('has_tin') ? 'checked' : '' }}>
          <label for="has-tin">Est soumis au TIN/TVA ?</label>
        </div>

        <div class="form-group tin-section" id="tin-section">
          <label for="tin">TIN / ICE</label>
          <input type="text" id="tin" name="tin" value="{{ old('tin') }}" class="form-input @error('tin') is-invalid @enderror">
          @error('tin')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="autre-id">Autre ID d'acheteur</label>
          <input type="text" id="autre-id" name="autre_id" value="{{ old('autre_id') }}" class="form-input @error('autre_id') is-invalid @enderror">
          @error('autre_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="points">Points de fidélité</label>
          <input type="number" id="points" name="points" value="{{ old('points') }}" placeholder="Optionnel" class="form-input @error('points') is-invalid @enderror">
          @error('points')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="solde">Solde d'ouverture</label>
          <input type="number" step="0.01" id="solde" name="solde" value="{{ old('solde') }}" placeholder="Optionnel" class="form-input @error('solde') is-invalid @enderror">
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
          <input type="text" id="contact-personne" name="contact_personne" value="{{ old('contact_personne') }}" class="form-input @error('contact_personne') is-invalid @enderror">
          @error('contact_personne')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group phone-input">
          <label for="telephone">Téléphone</label>
          <div class="phone-wrapper">
            <select name="phone_code" class="country-code">
              <option value="+33" {{ old('phone_code')=='+33'?'selected':'' }}>+33</option>
              <option value="+212" {{ old('phone_code')=='+212'?'selected':'' }}>+212</option>
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
              <option value="+33" {{ old('fax_code')=='+33'?'selected':'' }}>+33</option>
              <option value="+212" {{ old('fax_code')=='+212'?'selected':'' }}>+212</option>
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
                   value="{{ old($social) }}"
                   placeholder="Optionnel"
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
                   value="{{ old($field) }}"
                   class="form-input @error($field) is-invalid @enderror">
            @error($field)
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        @endforeach

        {{-- Pays --}}
        <div class="form-group">
          <label for="pays">Pays</label>
          <input list="country-list" id="pays" name="pays" value="{{ old('pays') }}" class="form-input @error('pays') is-invalid @enderror" placeholder="Choisissez…">
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

{{-- Add a little JS to toggle the TIN field --}}
@push('scripts')
<script>
  document.getElementById('has-tin').addEventListener('change', function() {
    document.getElementById('tin-section').style.display = this.checked ? '' : 'none';
  });
</script>
@endpush
