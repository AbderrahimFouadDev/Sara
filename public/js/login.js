document.addEventListener('DOMContentLoaded', () => {
    // Fade-in the form container
    const container = document.querySelector('.login-container');
    if (container) {
      requestAnimationFrame(() => {
        container.classList.add('fade-in');
      });
    }
  
    // Disable button on submit to prevent double-click
    const form = document.querySelector('form');
    form.addEventListener('submit', () => {
      const btn = form.querySelector('button[type="submit"], .primary-button');
      if (btn) btn.disabled = true;
    });
  });
  