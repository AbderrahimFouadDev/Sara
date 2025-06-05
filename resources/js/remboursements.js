document.addEventListener('DOMContentLoaded', function() {
    // Filter handling
    const searchInput = document.getElementById('searchRemboursement');
    const statutFilter = document.getElementById('statutFilter');
    const dateFilter = document.getElementById('dateFilter');
    let currentFilters = {};
    let debounceTimer;

    // Debounced filter function
    const applyFilters = () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const filters = {
                search: searchInput.value,
                statut: statutFilter.value,
                date: dateFilter.value
            };

            fetch('/remboursements/filter?' + new URLSearchParams(filters), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('remboursementsBody').innerHTML = data.html;
                // Update URL with filters
                const url = new URL(window.location);
                Object.entries(filters).forEach(([key, value]) => {
                    if (value) {
                        url.searchParams.set(key, value);
                    } else {
                        url.searchParams.delete(key);
                    }
                });
                window.history.pushState({}, '', url);
            })
            .catch(error => console.error('Error:', error));
        }, 300);
    };

    // Event listeners
    [searchInput, statutFilter, dateFilter].forEach(element => {
        element.addEventListener('change', applyFilters);
    });
    searchInput.addEventListener('input', applyFilters);

    // Modal handling
    const modal = document.getElementById('detailsModal');
    const closeBtn = modal.querySelector('.close');

    window.showDetails = function(remboursementId) {
        fetch(`/remboursements/${remboursementId}`)
            .then(response => response.json())
            .then(data => {
                const remb = data.remboursement;
                const details = document.getElementById('remboursementDetails');
                details.innerHTML = `
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="label">Date:</span>
                            <span class="value">${new Date(remb.date_remboursement).toLocaleDateString()}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">N° Facture:</span>
                            <span class="value">${remb.facture.numero}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Montant:</span>
                            <span class="value">${new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'MAD' }).format(remb.montant)}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Motif:</span>
                            <span class="value">${remb.motif}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Statut:</span>
                            <span class="value status-badge ${remb.statut}">${remb.statut.charAt(0).toUpperCase() + remb.statut.slice(1)}</span>
                        </div>
                    </div>
                `;
                modal.style.display = 'block';
            });
    };

    closeBtn.onclick = function() {
        modal.style.display = 'none';
    };

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
}); 