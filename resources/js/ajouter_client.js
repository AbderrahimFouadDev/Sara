document.addEventListener('DOMContentLoaded', function() {
    // Get the checkbox and TIN section elements
    const hasTinCheckbox = document.getElementById('has-tin');
    const tinSection = document.getElementById('tin-section');

    if (hasTinCheckbox && tinSection) {
        // Function to toggle TIN section visibility
        const toggleTinSection = () => {
            if (hasTinCheckbox.checked) {
                tinSection.style.display = 'block';
                tinSection.style.opacity = '1';
                tinSection.style.height = 'auto';
            } else {
                tinSection.style.display = 'none';
                tinSection.style.opacity = '0';
                tinSection.style.height = '0';
            }
        };

        // Set initial state
        toggleTinSection();

        // Add change event listener
        hasTinCheckbox.addEventListener('change', toggleTinSection);
    }

    // Form validation feedback
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('.form-input');

    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() !== '') {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            }
        });
    });

    // Phone number formatting
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,2})(\d{0,2})(\d{0,2})(\d{0,2})/);
            e.target.value = !x[2] ? x[1] : `${x[1]} ${x[2]}` + (!x[3] ? '' : ` ${x[3]}`) + (!x[4] ? '' : ` ${x[4]}`) + (!x[5] ? '' : ` ${x[5]}`);
        });
    });

    // Form submission animation
    form.addEventListener('submit', function(e) {
        const submitButton = this.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
            submitButton.disabled = true;
        }
    });

    // Add animation class to form on load
    if (form) {
        form.classList.add('form-fade-in');
    }
});
