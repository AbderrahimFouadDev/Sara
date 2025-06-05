// Search functionality
function initializeSearch() {
    const searchInput = document.getElementById('nav-search');
    const searchResults = document.getElementById('search-results');
    let currentFocus = -1;

    // Build navigation items map for search
    const navItems = [];
    document.querySelectorAll('.nav-link[data-view]').forEach(link => {
        navItems.push({
            title: link.querySelector('span').textContent.trim(),
            icon: link.querySelector('i').className,
            view: link.getAttribute('data-view'),
            element: link
        });
    });

    searchInput.addEventListener('input', function(e) {
        const searchText = this.value.toLowerCase();
        searchResults.innerHTML = '';
        
        if (searchText.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        const matches = navItems.filter(item => 
            item.title.toLowerCase().includes(searchText)
        );

        if (matches.length > 0) {
            matches.forEach(item => {
                const div = document.createElement('div');
                div.className = 'search-result-item';
                div.innerHTML = `
                    <i class="${item.icon}"></i>
                    <span>${item.title}</span>
                `;
                div.addEventListener('click', () => {
                    // Navigate to the view
                    if (item.view === 'dashboard') {
                        showDefaultDashboard();
                    } else {
                        loadView(item.view);
                    }
                    
                    // Update active state
                    document.querySelectorAll('.nav-item').forEach(navItem => {
                        navItem.classList.remove('active');
                    });
                    item.element.closest('.nav-item').classList.add('active');
                    
                    // Clear search
                    searchInput.value = '';
                    searchResults.style.display = 'none';
                });
                searchResults.appendChild(div);
            });
            searchResults.style.display = 'block';
        } else {
            searchResults.innerHTML = '<div class="search-no-results">Aucun résultat trouvé</div>';
            searchResults.style.display = 'block';
        }
    });

    // Handle keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        const items = searchResults.getElementsByClassName('search-result-item');
        
        if (items.length === 0) return;

        if (e.key === 'ArrowDown') {
            currentFocus++;
            addActive(items);
        } else if (e.key === 'ArrowUp') {
            currentFocus--;
            addActive(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentFocus > -1) {
                if (items[currentFocus]) {
                    items[currentFocus].click();
                }
            }
        }
    });

    // Close search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
}

function addActive(items) {
    if (!items) return false;
    removeActive(items);
    if (currentFocus >= items.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (items.length - 1);
    items[currentFocus].classList.add('active');
}

function removeActive(items) {
    for (let i = 0; i < items.length; i++) {
        items[i].classList.remove('active');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    initializeSearch();
    // Handle menu item clicks and active states
    const menuItems = document.querySelectorAll('.nav-link');
    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Remove active class from all menu items and their parents
            document.querySelectorAll('.nav-item').forEach(navItem => {
                navItem.classList.remove('active');
            });
            
            // Add active class to clicked item's parent
            this.closest('.nav-item').classList.add('active');
            
            // If this is the dashboard link, show default dashboard
            if(this.getAttribute('data-view') === 'dashboard') {
                const defaultDashboard = document.getElementById('default-dashboard');
                const dynamicContent = document.getElementById('dynamic-content');
                if(defaultDashboard && dynamicContent) {
                    defaultDashboard.style.display = 'block';
                    dynamicContent.style.display = 'none';
                }
            }
    });
});

// Handle submenu toggles
    const submenuToggles = document.querySelectorAll('.has-submenu > .nav-link');
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            const parent = toggle.parentElement;
            
            // Close other submenus
            document.querySelectorAll('.has-submenu.open').forEach(openMenu => {
                if (openMenu !== parent) {
                    openMenu.classList.remove('open');
                }
            });
            
            // Toggle current submenu
            parent.classList.toggle('open');
        });
    });

    // Handle user profile dropdown
    const userProfileToggle = document.querySelector('.user-profile i.fa-chevron-down');
    const userDropdown = document.querySelector('.user-dropdown');
    let isDropdownOpen = false;

    if (userProfileToggle && userDropdown) {
        userProfileToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            isDropdownOpen = !isDropdownOpen;
            userDropdown.style.display = isDropdownOpen ? 'block' : 'none';
            userProfileToggle.style.transform = isDropdownOpen ? 'rotate(180deg)' : 'rotate(0)';
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!userDropdown.contains(e.target) && !userProfileToggle.contains(e.target)) {
                isDropdownOpen = false;
                userDropdown.style.display = 'none';
                userProfileToggle.style.transform = 'rotate(0)';
            }
        });
    }

    // Handle view loading
    const viewLinks = document.querySelectorAll('[data-view]');
    viewLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const view = this.getAttribute('data-view');
            
            // Update active states
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            this.closest('.nav-item').classList.add('active');
            
            if (view === 'dashboard') {
                showDefaultDashboard();
            } else {
                loadView(view);
            }
        });
    });

    // Search functionality
    const searchInput = document.getElementById('nav-search');
    const searchResults = document.getElementById('search-results');
    let currentFocus = -1;

    // Build navigation items map for search
    const navItems = [];
    document.querySelectorAll('.nav-link[data-view]').forEach(link => {
        navItems.push({
            title: link.querySelector('span').textContent.trim(),
            icon: link.querySelector('i').className,
            view: link.getAttribute('data-view'),
            element: link
        });
    });

    searchInput.addEventListener('input', function(e) {
        const searchText = this.value.toLowerCase();
        searchResults.innerHTML = '';
        
        if (searchText.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        const matches = navItems.filter(item => 
            item.title.toLowerCase().includes(searchText)
        );

        if (matches.length > 0) {
            matches.forEach(item => {
                const div = document.createElement('div');
                div.className = 'search-result-item';
                div.innerHTML = `
                    <i class="${item.icon}"></i>
                    <span>${item.title}</span>
                `;
                div.addEventListener('click', () => {
                    // Navigate to the view
                    if (item.view === 'dashboard') {
                        showDefaultDashboard();
                    } else {
                        loadView(item.view);
                    }
            
            // Update active state
                    document.querySelectorAll('.nav-item').forEach(navItem => {
                        navItem.classList.remove('active');
                    });
                    item.element.closest('.nav-item').classList.add('active');
                    
                    // Clear search
                    searchInput.value = '';
                    searchResults.style.display = 'none';
                });
                searchResults.appendChild(div);
            });
            searchResults.style.display = 'block';
        } else {
            searchResults.innerHTML = '<div class="search-no-results">Aucun résultat trouvé</div>';
            searchResults.style.display = 'block';
        }
    });

    // Handle keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        const items = searchResults.getElementsByClassName('search-result-item');
        
        if (items.length === 0) return;

        if (e.key === 'ArrowDown') {
            currentFocus++;
            addActive(items);
        } else if (e.key === 'ArrowUp') {
            currentFocus--;
            addActive(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentFocus > -1) {
                if (items[currentFocus]) {
                    items[currentFocus].click();
                }
            }
        }
    });

    function addActive(items) {
        if (!items) return false;
        removeActive(items);
        if (currentFocus >= items.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (items.length - 1);
        items[currentFocus].classList.add('active');
    }

    function removeActive(items) {
        for (let i = 0; i < items.length; i++) {
            items[i].classList.remove('active');
        }
    }

    // Close search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
});

function showDefaultDashboard() {
    const defaultDashboard = document.getElementById('default-dashboard');
    const dynamicContent = document.getElementById('dynamic-content');
    
    if (defaultDashboard && dynamicContent) {
        defaultDashboard.style.display = 'block';
        dynamicContent.style.display = 'none';
    }
}

// Load view content
function loadView(view, params = {}) {
    const defaultContent = document.getElementById('default-dashboard');
    const dynamicContent = document.getElementById('dynamic-content');
    
    // Show loading state in dynamic content
    dynamicContent.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Chargement...</div>';
    dynamicContent.style.display = 'block';
    
    // Hide default dashboard content
    if (defaultContent) {
        defaultContent.style.display = 'none';
    }

    // Build the URL with query parameters
    const queryParams = new URLSearchParams(params).toString();
    const url = `/dashboard/component/${view}${queryParams ? `?${queryParams}` : ''}`;

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        dynamicContent.innerHTML = html;
        dynamicContent.style.display = 'block';
        
        // Initialize specific views
        if (view === 'bon_livraison/edit_bon_livraison') {
            console.log('Bon de livraison edit view detected, initializing...');
            initializeBonLivraisonEdit();
        } else if (view === 'factures/edit_facture') {
            console.log('Facture edit view detected, initializing...');
            initializeFactureEdit();
        }

        // Re-initialize event listeners for newly loaded content
        document.querySelectorAll('.load-view').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const view = this.dataset.view;
                const params = {};
                
                // Get all data attributes except data-view
                Object.keys(this.dataset).forEach(key => {
                    if (key !== 'view') {
                        params[key] = this.dataset[key];
                    }
                });
                
                loadView(view, params);
            });
        });
    })
    .catch(error => {
        console.error('Error loading view:', error);
        dynamicContent.innerHTML = '<div class="error">Une erreur est survenue lors du chargement.</div>';
    });
}

// Handle module cards
function initializeModuleCards() {
    const moduleCards = document.querySelectorAll('.module-card');
    
    moduleCards.forEach(card => {
        card.addEventListener('click', () => {
            const link = card.querySelector('.module-link');
            if (link) {
                window.location.href = link.href;
            }
        });
    });
}

// Handle stat cards
function initializeStatCards() {
    const statCards = document.querySelectorAll('.stat-card');
    
    statCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
            card.style.boxShadow = 'var(--shadow)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = 'none';
        });
    });
}

// Handle quick actions
function initializeQuickActions() {
    const actionButtons = document.querySelectorAll('.action-btn');
    
    actionButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const action = button.dataset.action;
            if (action) {
                handleQuickAction(action);
            }
        });
    });
}

// Handle quick action clicks
function handleQuickAction(action) {
    switch(action) {
        case 'new-invoice':
            loadView('factures/nouvelle');
            break;
        case 'new-client':
            loadView('clients/nouveau');
            break;
        case 'new-product':
            loadView('produits/nouveau');
            break;
        // Add more cases for other quick actions
    }
}

// Initialize client list functionality
function initializeClientList() {
    const searchInput = document.querySelector('#client-search');
    const clientTable = document.querySelector('#client-table');
    
    if (searchInput && clientTable) {
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const rows = clientTable.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
}

// Initialize invoice list functionality
function initializeInvoiceList() {
    const statusFilters = document.querySelectorAll('.status-filter');
    const invoiceTable = document.querySelector('#invoice-table');
    
    if (statusFilters && invoiceTable) {
        statusFilters.forEach(filter => {
            filter.addEventListener('click', (e) => {
                e.preventDefault();
                const status = e.target.dataset.status;
                
                // Update active filter
                statusFilters.forEach(f => f.classList.remove('active'));
                e.target.classList.add('active');
                
                // Filter rows
                const rows = invoiceTable.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    if (status === 'all' || row.dataset.status === status) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    }
}

// Initialize product list functionality
function initializeProductList() {
    const categoryFilters = document.querySelectorAll('.category-filter');
    const productGrid = document.querySelector('#product-grid');
    
    if (categoryFilters && productGrid) {
        categoryFilters.forEach(filter => {
            filter.addEventListener('click', (e) => {
                e.preventDefault();
                const category = e.target.dataset.category;
                
                // Update active filter
                categoryFilters.forEach(f => f.classList.remove('active'));
                e.target.classList.add('active');
                
                // Filter products
                const products = productGrid.querySelectorAll('.product-card');
                products.forEach(product => {
                    if (category === 'all' || product.dataset.category === category) {
                        product.style.display = '';
                    } else {
                        product.style.display = 'none';
                    }
                });
            });
        });
    }
}

// Handle notifications
function initializeNotifications() {
    const notificationBell = document.querySelector('.notification-bell');
    const notificationDropdown = document.querySelector('.notification-dropdown');
    
    if (notificationBell && notificationDropdown) {
        notificationBell.addEventListener('click', (e) => {
            e.stopPropagation();
            notificationDropdown.style.display = notificationDropdown.style.display === 'block' ? 'none' : 'block';
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
            notificationDropdown.style.display = 'none';
        });
    }
}

// Handle theme switching
function initializeThemeSwitch() {
    const themeSwitch = document.querySelector('.theme-switch');
    
    if (themeSwitch) {
        themeSwitch.addEventListener('click', () => {
            document.body.classList.toggle('dark-theme');
            // Save preference to localStorage
            localStorage.setItem('theme', document.body.classList.contains('dark-theme') ? 'dark' : 'light');
        });
        
        // Load saved theme preference
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-theme');
        }
    }
}

// Handle responsive sidebar
function initializeResponsiveSidebar() {
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const dashboardSidebar = document.querySelector('.dashboard-sidebar');
    
    if (sidebarToggle && dashboardSidebar) {
        sidebarToggle.addEventListener('click', () => {
            dashboardSidebar.classList.toggle('collapsed');
        });
        
        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 1024) {
                dashboardSidebar.classList.add('collapsed');
            }
        });
    }
}

// Initialize all components when the page loads
window.addEventListener('load', () => {
    initializeNotifications();
    initializeThemeSwitch();
    initializeResponsiveSidebar();
});

// Listen for custom loadView events
document.addEventListener('loadView', function(e) {
    if (e.detail && e.detail.view) {
        loadView(e.detail.view, e.detail.params || {});
    }
});

// Initialize scripts for remboursements view
function initializeRemboursementsScripts() {
    const searchInput = document.getElementById('searchRemboursement');
    const statutFilter = document.getElementById('statutFilter');
    const dateFilter = document.getElementById('dateFilter');
    
    if (searchInput && statutFilter && dateFilter) {
        searchInput.addEventListener('input', filterRemboursements);
        statutFilter.addEventListener('change', filterRemboursements);
        dateFilter.addEventListener('change', filterRemboursements);
    }

    // Initialize modal functionality
    const modal = document.getElementById('detailsModal');
    const closeBtn = document.querySelector('.close');
    
    if (modal && closeBtn) {
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }
}

// Filter remboursements function
function filterRemboursements() {
    const search = document.getElementById('searchRemboursement').value.toLowerCase();
    const statut = document.getElementById('statutFilter').value;
    const date = document.getElementById('dateFilter').value;

    fetch('/remboursements/filter', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ search, statut, date })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('remboursementsBody').innerHTML = data.html;
    })
    .catch(error => console.error('Error:', error));
}

// Show remboursement details
function showDetails(id) {
    fetch(`/remboursements/${id}`)
        .then(response => response.json())
        .then(data => {
            const modal = document.getElementById('detailsModal');
            const detailsContainer = document.getElementById('remboursementDetails');
            const remboursement = data.remboursement;

            detailsContainer.innerHTML = `
                <div class="details-content">
                    <p><strong>ID Remboursement:</strong> ${remboursement.id}</p>
                    <p><strong>Date:</strong> ${remboursement.date_remboursement}</p>
                    <p><strong>Montant:</strong> ${remboursement.montant} MAD</p>
                    <p><strong>Motif:</strong> ${remboursement.motif}</p>
                    <p><strong>Statut:</strong> ${remboursement.statut}</p>
                    <p><strong>Informations supplémentaires:</strong></p>
                    <ul>
                        <li>Facture associée: ${remboursement.facture.numero}</li>
                        <li>Date de la demande: ${remboursement.created_at}</li>
                        <li>Dernière mise à jour: ${remboursement.updated_at}</li>
                    </ul>
                </div>
            `;

            modal.style.display = "block";
        })
        .catch(error => console.error('Error:', error));
}

// Initialize client search functionality
function initializeClientSearch() {
    console.log('Initializing client search...');
    
    const searchInput = document.getElementById('searchInput');
    const dateSearch = document.getElementById('dateSearch');
    const searchBtn = document.getElementById('searchBtn');
    const dateSearchBtn = document.getElementById('dateSearchBtn');
    const clearSearch = document.getElementById('clearSearch');
    const tableRows = document.querySelectorAll('.client-table tbody tr');

    if (!searchInput || !dateSearch || !searchBtn || !dateSearchBtn || !clearSearch) {
        console.error('Search elements not found:', {
            searchInput: !!searchInput,
            dateSearch: !!dateSearch,
            searchBtn: !!searchBtn,
            dateSearchBtn: !!dateSearchBtn,
            clearSearch: !!clearSearch
        });
        return;
    }

    console.log('All search elements found, setting up event listeners...');

    function clearHighlights() {
        tableRows.forEach(row => {
            row.classList.remove('highlight');
            row.style.display = '';
        });
    }

    function performSearch() {
        console.log('Performing search...');
        const searchText = searchInput.value.toLowerCase();
        if (!searchText) return;

        clearHighlights();
        let found = false;

        tableRows.forEach(row => {
            const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

            if (name.includes(searchText) || email.includes(searchText)) {
                row.style.display = '';
                row.classList.add('highlight');
                found = true;
            } else {
                row.style.display = 'none';
            }
        });

        if (!found) {
            alert('Aucun client trouvé avec ces critères.');
        }
    }

    function performDateSearch() {
        console.log('Performing date search...');
        const searchDate = dateSearch.value;
        if (!searchDate) return;

        clearHighlights();
        let found = false;

        tableRows.forEach(row => {
            const date = row.querySelector('td:nth-child(1)').textContent;
            if (date.includes(searchDate)) {
                row.style.display = '';
                row.classList.add('highlight');
                found = true;
            } else {
                row.style.display = 'none';
            }
        });

        if (!found) {
            alert('Aucun client trouvé à cette date.');
        }
    }

    // Search button click
    searchBtn.addEventListener('click', () => {
        console.log('Search button clicked');
        performSearch();
    });

    // Date search button click
    dateSearchBtn.addEventListener('click', () => {
        console.log('Date search button clicked');
        performDateSearch();
    });

    // Enter key in search input
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    // Enter key in date input
    dateSearch.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performDateSearch();
        }
    });

    // Clear search
    clearSearch.addEventListener('click', () => {
        console.log('Clear search clicked');
        searchInput.value = '';
        dateSearch.value = '';
        clearHighlights();
    });

    console.log('Client search initialization complete');
}

// Initialize delete buttons functionality
function initializeDeleteButtons() {
    console.log('Initializing delete buttons...');
    
    const deleteButtons = document.querySelectorAll('.delete-btn');
    
    if (deleteButtons.length > 0) {
        console.log('Found', deleteButtons.length, 'delete buttons');
        
        deleteButtons.forEach(button => {
            console.log('Setting up delete button event listener for:', button);
            
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                console.log('Delete button clicked');
                
                const clientId = this.getAttribute('data-client-id');
                const row = this.closest('tr');
                const clientName = row.querySelector('td:nth-child(2)').textContent.trim();
                
                console.log('Delete request details:', {
                    clientId,
                    clientName
                });

                if (!confirm(`Voulez-vous vraiment supprimer le client "${clientName}" ?`)) {
                    console.log('Delete cancelled by user');
                    return;
                }

                try {
                    console.log('Sending delete request to /clients/' + clientId);
                    const response = await fetch(`/clients/${clientId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    console.log('Server response received');
                    const data = await response.json();
                    console.log('Response data:', data);

                    if (data.success) {
                        console.log('Delete successful - removing row');
                        row.remove();
                        alert('Client supprimé avec succès!');
                    } else {
                        console.error('Server returned error:', data);
                        // Check if it's a foreign key constraint error
                        if (data.error_type === 'foreign_key_constraint') {
                            alert('Impossible de supprimer ce client car il est lié à des documents existants (devis, factures, bons de livraison, etc.). Veuillez d\'abord supprimer ou modifier ces documents.');
                        } else {
                            alert('Erreur: ' + (data.message || 'Impossible de supprimer le client'));
                        }
                    }
                } catch (error) {
                    console.error('Delete request failed:', error);
                    alert('Une erreur est survenue lors de la suppression du client. Veuillez réessayer plus tard.');
                }
            });
        });
    } else {
        console.log('No delete buttons found');
    }
}

// Initialize supplier search functionality
function initializeSupplierSearch() {
    console.log('Initializing supplier search...');
    
    const searchInput = document.getElementById('searchInput');
    const dateSearch = document.getElementById('dateSearch');
    const searchBtn = document.getElementById('searchBtn');
    const dateSearchBtn = document.getElementById('dateSearchBtn');
    const clearSearch = document.getElementById('clearSearch');
    const tableRows = document.querySelectorAll('.fournisseur-table tbody tr');

    if (!searchInput || !dateSearch || !searchBtn || !dateSearchBtn || !clearSearch) {
        console.error('Search elements not found:', {
            searchInput: !!searchInput,
            dateSearch: !!dateSearch,
            searchBtn: !!searchBtn,
            dateSearchBtn: !!dateSearchBtn,
            clearSearch: !!clearSearch
        });
        return;
    }

    console.log('All search elements found, setting up event listeners...');

    function clearHighlights() {
        tableRows.forEach(row => {
            row.classList.remove('highlight');
            row.style.display = '';
        });
    }

    function performSearch() {
        console.log('Performing search...');
        const searchText = searchInput.value.toLowerCase();
        if (!searchText) return;

        clearHighlights();
        let found = false;

        tableRows.forEach(row => {
            const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const contact = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

            if (name.includes(searchText) || contact.includes(searchText)) {
                row.style.display = '';
                row.classList.add('highlight');
                found = true;
            } else {
                row.style.display = 'none';
            }
        });

        if (!found) {
            alert('Aucun fournisseur trouvé avec ces critères.');
        }
    }

    function performDateSearch() {
        console.log('Performing date search...');
        const searchDate = dateSearch.value;
        if (!searchDate) return;

        clearHighlights();
        let found = false;

        tableRows.forEach(row => {
            const date = row.querySelector('td:nth-child(1)').textContent;
            if (date.includes(searchDate)) {
                row.style.display = '';
                row.classList.add('highlight');
                found = true;
            } else {
                row.style.display = 'none';
            }
        });

        if (!found) {
            alert('Aucun fournisseur trouvé à cette date.');
        }
    }

    // Search button click
    searchBtn.addEventListener('click', () => {
        console.log('Search button clicked');
        performSearch();
    });

    // Date search button click
    dateSearchBtn.addEventListener('click', () => {
        console.log('Date search button clicked');
        performDateSearch();
    });

    // Enter key in search input
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    // Enter key in date input
    dateSearch.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performDateSearch();
        }
    });

    // Clear search
    clearSearch.addEventListener('click', () => {
        console.log('Clear search clicked');
        searchInput.value = '';
        dateSearch.value = '';
        clearHighlights();
    });

    console.log('Supplier search initialization complete');
}

// Initialize Bon de Livraison Edit Functionality
function initializeBonLivraisonEdit() {
    console.log('Starting bon de livraison edit initialization...');
    
    // Get elements
    const form = document.getElementById('editBonLivraisonForm');
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const addProductBtn = document.getElementById('addProductBtn');
    const selectedProductsTable = document.getElementById('selectedProducts');

    if (!form || !productSelect || !quantityInput || !addProductBtn || !selectedProductsTable) {
        console.error('Required elements not found');
        return;
    }

    const selectedProducts = new Map();

    // Initialize existing products if any
    console.log('Checking for existing products in form data...');
    
    try {
        // Get products data from form data attribute
        const productsData = form.dataset.products;
        console.log('Raw products data:', productsData);

        if (productsData) {
            const products = JSON.parse(productsData);
            console.log('Parsed products:', products);

            if (Array.isArray(products) && products.length > 0) {
                console.log('Found existing products:', products);

                // Clear the table first
                selectedProductsTable.innerHTML = '';
                
                // Add each product to the table
                products.forEach(product => {
                    console.log('Processing product:', product);
                    if (product && product.id) {
                        addProductToTable({
                            id: product.id,
                            name: product.name,
                            description: product.description || '',
                            price_ht: parseFloat(product.price_ht) || 0,
                            quantity: parseInt(product.quantity) || 1,
                            remise_percent: parseFloat(product.remise_percent) || 0,
                            remise_amount: parseFloat(product.remise_amount) || 0,
                            total_ht: parseFloat(product.total_ht) || 0,
                            tva_amount: parseFloat(product.tva_amount) || 0,
                            total_ttc: parseFloat(product.total_ttc) || 0
                        });
                    }
                });
            } else {
                console.log('No products found in data');
                selectedProductsTable.innerHTML = `
                    <tr class="no-products">
                        <td colspan="10" class="text-center">Aucun article sélectionné</td>
                    </tr>
                `;
            }
        } else {
            console.error('No products data found in form');
            selectedProductsTable.innerHTML = `
                <tr class="no-products">
                    <td colspan="10" class="text-center">Aucun article sélectionné</td>
                </tr>
            `;
        }
    } catch (error) {
        console.error('Error processing products data:', error);
        selectedProductsTable.innerHTML = `
            <tr class="no-products">
                <td colspan="10" class="text-center">Erreur lors du chargement des articles</td>
            </tr>
        `;
    }

    // Add product button click handler
    addProductBtn.addEventListener('click', function() {
        const selectedOption = productSelect.selectedOptions[0];
        
        if (!selectedOption || !selectedOption.value) {
            alert('Veuillez sélectionner un article');
            return;
        }

        const productId = parseInt(selectedOption.value);
        if (selectedProducts.has(productId)) {
            alert('Cet article est déjà dans la liste');
            return;
        }

        const quantity = parseInt(quantityInput.value) || 1;
        const price_ht = parseFloat(selectedOption.dataset.price);
        
        const product = {
            id: productId,
            name: selectedOption.dataset.name || selectedOption.text.split(' - ')[0],
            description: selectedOption.dataset.description || '',
            price_ht: price_ht,
            quantity: quantity,
            remise_percent: 0,
            remise_amount: 0,
            total_ht: price_ht * quantity,
            tva_amount: price_ht * quantity * 0.20,
            total_ttc: price_ht * quantity * 1.20
        };

        addProductToTable(product);

        // Reset inputs
        productSelect.value = '';
        quantityInput.value = '1';
    });

    function addProductToTable(product) {
        console.log('Adding product to table:', product);

        // Remove no products message if exists
        const noProducts = selectedProductsTable.querySelector('.no-products');
        if (noProducts) {
            noProducts.remove();
        }

        // Ensure numeric values
        const price_ht = parseFloat(product.price_ht) || 0;
        const quantity = parseInt(product.quantity) || 1;
        const remise_percent = parseFloat(product.remise_percent) || 0;
        const remise_amount = parseFloat(product.remise_amount) || 0;
        const total_ht = parseFloat(product.total_ht) || (price_ht * quantity);
        const tva_amount = parseFloat(product.tva_amount) || (total_ht * 0.20);
        const total_ttc = parseFloat(product.total_ttc) || (total_ht + tva_amount);

        console.log('Processed values:', {
            price_ht,
            quantity,
            remise_percent,
            remise_amount,
            total_ht,
            tva_amount,
            total_ttc
        });

        const row = document.createElement('tr');
        row.dataset.productId = product.id;
        row.innerHTML = `
            <td>${product.name}</td>
            <td>${product.description || ''}</td>
            <td class="price-ht">${price_ht.toFixed(2)} MAD</td>
            <td>
                <input type="number" class="form-control quantity-input" 
                       value="${quantity}" min="1" style="width: 80px">
            </td>
            <td>
                <input type="number" class="form-control remise-input" 
                       value="${remise_percent}" min="0" max="100" step="0.01" style="width: 80px">
            </td>
            <td class="remise-amount">${remise_amount.toFixed(2)} MAD</td>
            <td class="total-ht-line">${total_ht.toFixed(2)} MAD</td>
            <td class="tva-amount">${tva_amount.toFixed(2)} MAD</td>
            <td class="total-ttc-line">${total_ttc.toFixed(2)} MAD</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm delete-product">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        // Add event listeners
        const qtyInput = row.querySelector('.quantity-input');
        const remiseInput = row.querySelector('.remise-input');
        const deleteBtn = row.querySelector('.delete-product');

        if (qtyInput) {
            qtyInput.addEventListener('change', function() {
                updateProductCalculations(product.id);
            });
        }

        if (remiseInput) {
            remiseInput.addEventListener('change', function() {
                updateProductCalculations(product.id);
            });
        }

        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
                    selectedProducts.delete(product.id);
                    row.remove();
                    if (selectedProducts.size === 0) {
                        selectedProductsTable.innerHTML = `
                            <tr class="no-products">
                                <td colspan="10" class="text-center">Aucun article sélectionné</td>
                            </tr>
                        `;
                    }
                    updateTotals();
                }
            });
        }

        selectedProductsTable.appendChild(row);
        selectedProducts.set(product.id, {
            ...product,
            price_ht,
            quantity,
            remise_percent,
            remise_amount,
            total_ht,
            tva_amount,
            total_ttc
        });
        updateTotals();
    }

    function updateProductCalculations(productId) {
        const row = selectedProductsTable.querySelector(`tr[data-product-id="${productId}"]`);
        if (!row) return;

        const product = selectedProducts.get(productId);
        if (!product) return;

        const quantity = parseInt(row.querySelector('.quantity-input').value) || 1;
        const remisePercent = parseFloat(row.querySelector('.remise-input').value) || 0;

        product.quantity = quantity;
        product.remise_percent = remisePercent;
        product.total_ht_before_remise = product.price_ht * quantity;
        product.remise_amount = (product.total_ht_before_remise * remisePercent) / 100;
        product.total_ht = product.total_ht_before_remise - product.remise_amount;
        product.tva_amount = product.total_ht * 0.20;
        product.total_ttc = product.total_ht + product.tva_amount;

        // Update row display
        row.querySelector('.remise-amount').textContent = `${product.remise_amount.toFixed(2)} MAD`;
        row.querySelector('.total-ht-line').textContent = `${product.total_ht.toFixed(2)} MAD`;
        row.querySelector('.tva-amount').textContent = `${product.tva_amount.toFixed(2)} MAD`;
        row.querySelector('.total-ttc-line').textContent = `${product.total_ttc.toFixed(2)} MAD`;

        updateTotals();
    }

    function updateTotals() {
        let totalHT = 0, totalTVA = 0, totalTTC = 0;

        selectedProducts.forEach(product => {
            totalHT += product.total_ht;
            totalTVA += product.tva_amount;
            totalTTC += product.total_ttc;
        });

        const totalsFooter = selectedProductsTable.closest('table').querySelector('tfoot');
        if (totalsFooter) {
            totalsFooter.querySelector('.total-ht').textContent = `${totalHT.toFixed(2)} MAD`;
            totalsFooter.querySelector('.total-tva').textContent = `${totalTVA.toFixed(2)} MAD`;
            totalsFooter.querySelector('.total-ttc').textContent = `${totalTTC.toFixed(2)} MAD`;
        }
    }

    // Form submission handler
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Form submission started');

        if (selectedProducts.size === 0) {
            alert('Veuillez ajouter au moins un produit');
            return;
        }

        const bonLivraisonId = form.dataset.bonLivraisonId;
        console.log('Bon de livraison ID:', bonLivraisonId);

        const formData = {
            date_livraison: document.getElementById('date_livraison').value,
            etat: document.getElementById('etat').value,
            notes: document.getElementById('notes').value,
            products: Array.from(selectedProducts.values()).map(product => ({
                id: product.id,
                quantity: parseInt(product.quantity),
                price_ht: parseFloat(product.price_ht),
                remise_percent: parseFloat(product.remise_percent),
                remise_amount: parseFloat(product.remise_amount),
                total_ht: parseFloat(product.total_ht),
                tva_amount: parseFloat(product.tva_amount),
                total_ttc: parseFloat(product.total_ttc)
            }))
        };

        console.log('Submitting form data:', formData);

        try {
            const response = await fetch(`/bonlivraison/${bonLivraisonId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();
            console.log('Server response:', result);

            if (response.ok) {
                alert('Bon de livraison mis à jour avec succès');
                // Redirect to the bon de livraison view page
                window.location.href = `/bonlivraison/${bonLivraisonId}`;
            } else {
                throw new Error(result.message || 'Erreur lors de la mise à jour du bon de livraison');
            }
        } catch (error) {
            console.error('Error submitting form:', error);
            alert(error.message || 'Une erreur est survenue lors de la mise à jour');
            }
        });
    }

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, checking for bon de livraison edit form');
    const editForm = document.getElementById('editBonLivraisonForm');
    if (editForm) {
        console.log('Found edit form, initializing...');
        initializeBonLivraisonEdit();
    } else {
        console.log('Edit form not found');
    }
});

function initializeFactureEdit() {
    console.log('Starting facture edit initialization...');
    
    const form = document.getElementById('editFactureForm');
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const addProductBtn = document.getElementById('addProductBtn');
    const selectedProductsTable = document.getElementById('selectedProducts');
    const selectedProducts = new Map();

    if (!form || !productSelect || !quantityInput || !addProductBtn || !selectedProductsTable) {
        console.error('Required elements not found for facture edit');
        return;
    }

    console.log('Found all required elements');

    // Initialize existing products if any
    console.log('Checking for existing products...');
    const existingRows = selectedProductsTable.querySelectorAll('tr[data-product-id]');
    if (existingRows.length > 0) {
        console.log('Found existing rows:', existingRows.length);
        existingRows.forEach(row => {
            const productId = parseInt(row.dataset.productId);
            const qtyInput = row.querySelector('.quantity-input');
            const quantity = parseInt(qtyInput.value) || 1;
            const priceHt = parseFloat(row.querySelector('td:nth-child(3)').textContent);
            const tvaRate = 20;

            const product = {
                id: productId,
                name: row.querySelector('td:nth-child(1)').textContent,
                description: row.querySelector('td:nth-child(2)').textContent,
                price_ht: priceHt,
                quantity: quantity,
                tva_rate: tvaRate,
                total_ht: priceHt * quantity,
                tva_amount: (priceHt * quantity * tvaRate) / 100,
                total_ttc: priceHt * quantity * (1 + tvaRate/100)
            };

            selectedProducts.set(productId, product);
            setupRowEventListeners(row, product);
        });
    }

    // Add product button click handler
    addProductBtn.addEventListener('click', function() {
        console.log('Add product button clicked');
        const selectedOption = productSelect.selectedOptions[0];
        
        if (!selectedOption || !selectedOption.value) {
            alert('Veuillez sélectionner un article');
            return;
        }

        const productId = parseInt(selectedOption.value);
        if (selectedProducts.has(productId)) {
            alert('Cet article est déjà dans la liste');
            return;
        }

        const quantity = parseInt(quantityInput.value) || 1;
        const price_ht = parseFloat(selectedOption.dataset.price);
        const tva_rate = 20; // TVA rate is 20%
        
        const product = {
            id: productId,
            name: selectedOption.text.split(' - ')[0],
            description: selectedOption.dataset.description || '',
            price_ht: price_ht,
            quantity: quantity,
            tva_rate: tva_rate,
            total_ht: price_ht * quantity,
            tva_amount: (price_ht * quantity * tva_rate) / 100,
            total_ttc: price_ht * quantity * (1 + tva_rate/100)
        };

        console.log('Adding new product:', product);

        // Create and add the new row
        const row = createProductRow(product);
        
        // Only remove the "no products" message if it exists
        const noProducts = selectedProductsTable.querySelector('.no-products');
        if (noProducts) {
            noProducts.remove();
        }

        selectedProducts.set(product.id, product);
        selectedProductsTable.appendChild(row);
        setupRowEventListeners(row, product);
        updateFactureTotals();

        // Reset inputs
        productSelect.value = '';
        quantityInput.value = '1';
    });

    function createProductRow(product) {
        const row = document.createElement('tr');
        row.dataset.productId = product.id;
        row.innerHTML = `
            <td>${product.name}</td>
            <td>${product.description || ''}</td>
            <td>${parseFloat(product.price_ht).toFixed(2)} MAD</td>
            <td>
                <input type="number" class="form-control quantity-input" 
                       value="${product.quantity}" min="1" style="width: 80px">
            </td>
            <td class="total-ht-line">${parseFloat(product.total_ht).toFixed(2)} MAD</td>
            <td class="tva-amount">${parseFloat(product.tva_amount).toFixed(2)} MAD</td>
            <td class="total-ttc-line">${parseFloat(product.total_ttc).toFixed(2)} MAD</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm delete-product">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        return row;
    }

    function setupRowEventListeners(row, product) {
        const qtyInput = row.querySelector('.quantity-input');
        const deleteBtn = row.querySelector('.delete-product');

        if (qtyInput) {
            qtyInput.addEventListener('change', function() {
                const quantity = parseInt(this.value) || 1;
                const prod = selectedProducts.get(product.id);
                if (prod) {
                    prod.quantity = quantity;
                    prod.total_ht = prod.price_ht * quantity;
                    prod.tva_amount = (prod.price_ht * quantity * prod.tva_rate) / 100;
                    prod.total_ttc = prod.total_ht + prod.tva_amount;

                    row.querySelector('.total-ht-line').textContent = `${prod.total_ht.toFixed(2)} MAD`;
                    row.querySelector('.tva-amount').textContent = `${prod.tva_amount.toFixed(2)} MAD`;
                    row.querySelector('.total-ttc-line').textContent = `${prod.total_ttc.toFixed(2)} MAD`;
                    updateFactureTotals();
                }
            });
        }

        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
                    selectedProducts.delete(product.id);
                    row.remove();
                    if (selectedProducts.size === 0) {
                        selectedProductsTable.innerHTML = `
                            <tr class="no-products">
                                <td colspan="8" class="text-center">Aucun article sélectionné</td>
                            </tr>
                        `;
                    }
                    updateFactureTotals();
                }
            });
        }
    }

    function updateFactureTotals() {
        let totalHT = 0, totalTVA = 0, totalTTC = 0;

        selectedProducts.forEach(product => {
            totalHT += product.total_ht;
            totalTVA += product.tva_amount;
            totalTTC += product.total_ttc;
        });

        const totalsFooter = selectedProductsTable.closest('table').querySelector('tfoot');
        if (totalsFooter) {
            totalsFooter.querySelector('.total-ht').textContent = `${totalHT.toFixed(2)} MAD`;
            totalsFooter.querySelector('.total-tva').textContent = `${totalTVA.toFixed(2)} MAD`;
            totalsFooter.querySelector('.total-ttc').textContent = `${totalTTC.toFixed(2)} MAD`;
        }
    }

    // Form submission handler
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Facture form submission started');

        if (selectedProducts.size === 0) {
            alert('Veuillez ajouter au moins un produit');
            return;
        }

        const factureId = form.dataset.factureId;
        const formData = {
            date_facture: document.getElementById('date_facture').value,
            groupe_facture: document.getElementById('groupe_facture').value,
            statut: document.getElementById('statut').value,
            notes: document.getElementById('notes').value,
            products: Array.from(selectedProducts.values()).map(product => ({
                id: product.id,
                quantity: parseInt(product.quantity),
                price_ht: parseFloat(product.price_ht),
                tva_rate: parseFloat(product.tva_rate),
                total_ht: parseFloat(product.total_ht),
                tva_amount: parseFloat(product.tva_amount),
                total_ttc: parseFloat(product.total_ttc)
            }))
        };

        console.log('Submitting form data:', formData);

        try {
            const response = await fetch(`/factures/${factureId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();
            console.log('Server response:', result);

            if (response.ok) {
                alert('Facture mise à jour avec succès');
                window.location.href = `/factures/${factureId}`;
            } else {
                throw new Error(result.message || 'Erreur lors de la mise à jour de la facture');
            }
        } catch (error) {
            console.error('Error submitting facture form:', error);
            alert('Erreur lors de la mise à jour: ' + (error.message || 'Une erreur est survenue'));
        }
    });
}

// Handle profile photo upload
function initializeProfilePhotoUpload() {
    const photoInput = document.getElementById('photo-input');
    const profileImage = document.getElementById('profile-image');
    
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (profileImage) {
                        profileImage.src = e.target.result;
                    }
                };
                reader.readAsDataURL(e.target.files[0]);

                // Create FormData
                const formData = new FormData();
                formData.append('photo', e.target.files[0]);
                
                // Get CSRF token
                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                if (!token) {
                    console.error('CSRF token not found');
                    return;
                }

                // Send the form data
                fetch('/profile/update', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Photo uploaded successfully');
                        // Don't reload immediately to let user see the preview
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        console.error('Upload failed:', data.message);
                        alert(data.message || 'Une erreur est survenue lors du téléchargement');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue lors de la mise à jour de la photo');
                });
            }
        });
    }
}

// Initialize all profile related functionality
function initializeProfile() {
    initializeProfilePhotoUpload();
    
    // Handle change password button click
    const changePasswordBtn = document.querySelector('.change-password-btn');
    if (changePasswordBtn) {
        changePasswordBtn.addEventListener('click', function() {
            alert('Fonctionnalité à venir');
        });
    }
}

// Call initialization when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeProfile();
});



