
document.addEventListener('DOMContentLoaded', () => {
  const nextBtns   = document.querySelectorAll('.next-btn');
  const finishBtn  = document.querySelector('.finish-btn');
  const modal      = document.getElementById('error-modal');
  const msgElem    = document.getElementById('error-message');
  const modalClose = document.getElementById('modal-close');
  const csrfToken  = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const form       = document.querySelector('form');

  function showError(text) {
    msgElem.textContent = text;
    modal.classList.remove('hidden');
  }
  modalClose.addEventListener('click', () => modal.classList.add('hidden'));

  nextBtns.forEach(btn => {
    btn.addEventListener('click', async () => {
      const next       = parseInt(btn.dataset.next, 10);
      const currentNum = next - 1;
      const current    = document.getElementById(`form-step-${currentNum}`);
      const inputs     = current.querySelectorAll('input');

      // Client-side required check
      let allValid = true;
      inputs.forEach(i => {
        i.classList.remove('input-error');
        if (!i.checkValidity()) {
          allValid = false;
          i.classList.add('input-error');
        }
      });
      if (!allValid) {
        return showError('Tu dois remplir tous les champs requis avant de continuer.');
      }

      // Prepare payload
      const url = `/register/step${currentNum}`;        // e.g. "/register/step1"
      const payload = new FormData();
      inputs.forEach(i => payload.append(i.name, i.value));

      try {
        const res = await fetch(url, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: payload
        });
        if (!res.ok) throw new Error('Statut ' + res.status);
        const json = await res.json();
        if (json.next !== next) throw new Error('Réponse inattendue');

        // Advance UI
        current.classList.add('hidden');
        document.getElementById(`form-step-${next}`).classList.remove('hidden');
        // Mark circles
        const prevStep = document.getElementById(`step${currentNum}`);
        prevStep.classList.add('checked');
        prevStep.querySelector('.circle').textContent = '✔';
        if (next === 4) {
          const s4 = document.getElementById('step4');
          s4.classList.add('checked');
          s4.querySelector('.circle').textContent = '✔';
        }
      } catch (err) {
        showError("Erreur serveur : impossible d'enregistrer cette étape.");
        console.error(err);
      }
    });
  });

  finishBtn.addEventListener('click', () => {
    // final submit sends *all* inputs of all steps in one go
    form.submit();
  });
});
