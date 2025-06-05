document.addEventListener('DOMContentLoaded', () => {

    // Highlight active filter link
    document.querySelectorAll('.filter-link').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        document.querySelectorAll('.filter-link').forEach(l => l.classList.remove('active'));
        link.classList.add('active');
        // TODO: add filtering logic here based on link.dataset.filter
      });
    });
  
  });
  