document.addEventListener('DOMContentLoaded', function() {
    // Initialize Navigation first (before charts)
    initializeNavigation();

    // Initialize Charts after navigation
    initializeCharts();

    // Initialize Event Listeners
    initializeEventListeners();

    // Load Initial Data
    loadDashboardData();

    // Initialize Modal Handlers
    initializeModals();

    // Update time
    updateCurrentTime();
    setInterval(updateCurrentTime, 60000); // Update every minute

    // Initial updates
    updateDashboardStats();
    updateTime();
    
    // Set up periodic updates
    setInterval(updateDashboardStats, 60000); // Update stats every minute
    setInterval(updateTime, 1000); // Update time every second
});

function initializeNavigation() {
    const navLinks = document.querySelectorAll('.sidebar-nav a');
    const sections = {
        'dashboard': document.querySelector('.dashboard-content'),
        'users': document.querySelector('.users-section'),
        'messages': document.querySelector('.messages-section'),
        'home-editor': document.querySelector('.home-editor-section')
    };

    // Initially hide all sections except dashboard
    Object.values(sections).forEach(section => {
        if (section) {
            section.style.display = 'none';
        }
    });
    if (sections['dashboard']) {
        sections['dashboard'].style.display = 'block';
    }

    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const section = e.currentTarget.getAttribute('href').substring(1);
            console.log('Clicked section:', section); // Debug log

            // Update active state
            navLinks.forEach(l => l.parentElement.classList.remove('active'));
            e.currentTarget.parentElement.classList.add('active');

            // Show/hide sections
            Object.keys(sections).forEach(key => {
                if (sections[key]) {
                    console.log('Toggling section:', key); // Debug log
                    sections[key].style.display = key === section ? 'block' : 'none';
                }
            });

            // Reinitialize charts if dashboard is shown
            if (section === 'dashboard') {
                initializeCharts();
            }
        });
    });
}

function initializeCharts() {
    console.log('Initializing charts...'); // Debug log

    // Fetch stats data for charts
    fetch('/admin/stats')
        .then(response => response.json())
        .then(data => {
            // User Growth Chart
            const growthCtx = document.getElementById('userGrowthChart');
            if (growthCtx) {
                try {
                    new Chart(growthCtx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: data.monthlyGrowth.map(item => item.month),
                            datasets: [{
                                label: 'Nouveaux Utilisateurs',
                                data: data.monthlyGrowth.map(item => item.count),
                                borderColor: '#4a90e2',
                                tension: 0.4,
                                fill: false
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error creating growth chart:', error);
                }
            }

            // User Status Chart
            const statusCtx = document.getElementById('userStatusChart');
            if (statusCtx) {
                try {
                    new Chart(statusCtx.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Actifs', 'Inactifs', 'En attente'],
                            datasets: [{
                                data: [
                                    data.userStatus.active,
                                    data.userStatus.inactive,
                                    data.userStatus.pending
                                ],
                                backgroundColor: [
                                    '#27ae60',
                                    '#e74c3c',
                                    '#f1c40f'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error creating status chart:', error);
                }
            }
        })
        .catch(error => {
            console.error('Error fetching stats data:', error);
        });
}

function initializeEventListeners() {
    // Search functionality
    const searchInput = document.querySelector('.search-bar input');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            filterUsers(searchTerm);
        });
    }

    // Message list click handlers
    const messageList = document.querySelector('.message-list');
    if (messageList) {
        messageList.addEventListener('click', (e) => {
            const messageItem = e.target.closest('.message-item');
            if (messageItem) {
                loadMessageContent(messageItem.dataset.id);
            }
        });
    }

    // Reply button handler
    const replyBtn = document.querySelector('.reply-btn');
    if (replyBtn) {
        replyBtn.addEventListener('click', () => {
            const replyText = document.querySelector('.message-reply textarea').value;
            if (replyText.trim()) {
                sendReply(replyText);
            }
        });
    }

    // Home editor save handler
    const saveChangesBtn = document.querySelector('.save-changes-btn');
    if (saveChangesBtn) {
        saveChangesBtn.addEventListener('click', saveHomeChanges);
    }
}

function loadDashboardData() {
    // Load user statistics
    loadUserStats();
    
    // Load recent activity
    loadRecentActivity();
}

function loadUserStats() {
    // This would typically be an API call
    // For now using dummy data
    updateStatistics({
        totalUsers: 150,
        activeUsers: 120,
        inactiveUsers: 30,
        newUsers: 25
    });
}

function updateStatistics(data) {
    // Update statistics cards
    Object.keys(data).forEach(key => {
        const element = document.querySelector(`.${key.replace(/([A-Z])/g, '-$1').toLowerCase()} .number`);
        if (element) {
            element.textContent = data[key];
        }
    });
}

function loadRecentActivity() {
    const activityList = document.querySelector('.activity-list');
    if (!activityList) return;

    // Get activities from the page data
    const activities = window.recentActivities || [];
    
    activityList.innerHTML = activities.map(activity => `
        <div class="activity-item ${activity.type}">
            <div class="activity-icon">
                <i class="fas ${activity.icon_class}"></i>
            </div>
            <div class="activity-details">
                <p><strong>${activity.user ? activity.user.name : 'Système'}</strong> - ${activity.description}</p>
                <small>${activity.time_ago}</small>
            </div>
        </div>
    `).join('');
}

function getActivityIcon(type) {
    const icons = {
        'new-user': 'fa-user-plus',
        'status-change': 'fa-user-slash',
        'login': 'fa-sign-in-alt'
    };
    return icons[type] || 'fa-info-circle';
}

function updateCurrentTime() {
    const timeElement = document.getElementById('current-time');
    if (timeElement) {
        const now = new Date();
        timeElement.textContent = now.toLocaleTimeString('fr-FR', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
    }
}

function loadUsers() {
    const userTable = document.querySelector('.users-table tbody');
    if (!userTable) return;

    // Implement API call to fetch users
    // For now, using dummy data
    const users = [
        { 
            name: 'John Doe', 
            email: 'john@example.com', 
            status: 'active',
            registrationDate: '2024-01-15' 
        },
        { 
            name: 'Jane Smith', 
            email: 'jane@example.com', 
            status: 'inactive',
            registrationDate: '2024-01-16' 
        }
    ];

    userTable.innerHTML = users.map(user => `
        <tr>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td><span class="status ${user.status}">${user.status}</span></td>
            <td>${user.registrationDate}</td>
            <td class="actions">
                ${user.status === 'active' ? 
                    `<button class="action-btn deactivate" data-id="${user.email}" title="Désactiver">
                        <i class="fas fa-ban"></i>
                    </button>` :
                    `<button class="action-btn activate" data-id="${user.email}" title="Activer">
                        <i class="fas fa-check"></i>
                    </button>`
                }
                <button class="action-btn delete" data-id="${user.email}" title="Supprimer">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');

    // Add event listeners to action buttons
    userTable.querySelectorAll('.action-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const action = e.currentTarget.classList.contains('deactivate') ? 'désactiver' :
                          e.currentTarget.classList.contains('activate') ? 'activer' : 'supprimer';
            const userId = e.currentTarget.dataset.id;
            showConfirmationModal(action, userId);
        });
    });
}

function filterUsers(searchTerm) {
    const rows = document.querySelectorAll('.users-table tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
}

function showConfirmationModal(action, userId) {
    const modal = document.getElementById('userActionModal');
    if (!modal) return;

    modal.style.display = 'flex';
    modal.querySelector('.action-text').textContent = action;
    
    const confirmBtn = modal.querySelector('.confirm-btn');
    confirmBtn.onclick = () => {
        executeUserAction(action, userId);
        modal.style.display = 'none';
    };
}

function executeUserAction(action, userId) {
    // Implement API call to execute the action
    console.log(`Executing ${action} for user ${userId}`);
    // Reload users after action
    loadUsers();
}

function initializeModals() {
    const modals = document.querySelectorAll('.modal');
    
    modals.forEach(modal => {
        const closeButtons = modal.querySelectorAll('.close-modal, .cancel-btn');
        
        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                modal.style.display = 'none';
            });
        });

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
}

// Message handling functions
function loadMessageContent(messageId) {
    // Implement API call to load message content
    console.log(`Loading message ${messageId}`);
}

function sendReply(replyText) {
    // Implement API call to send reply
    console.log(`Sending reply: ${replyText}`);
}

// Home editor functions
function saveHomeChanges() {
    // Implement API call to save home page changes
    console.log('Saving home page changes');
}

// Update dashboard stats
function updateDashboardStats() {
    fetch('/admin/stats')
        .then(response => response.json())
        .then(data => {
            // Update total users
            document.querySelector('.total-users .number').textContent = data.totalUsers;
            document.querySelector('.total-users .trend').innerHTML = `
                <i class="fas fa-arrow-${data.totalGrowth >= 0 ? 'up' : 'down'}"></i>
                ${Math.abs(data.totalGrowth).toFixed(1)}%
            `;
            
            // Update active users
            document.querySelector('.active-users .number').textContent = data.activeUsers;
            document.querySelector('.active-users .trend').innerHTML = `
                <i class="fas fa-arrow-${data.activeGrowth >= 0 ? 'up' : 'down'}"></i>
                ${Math.abs(data.activeGrowth).toFixed(1)}%
            `;
            
            // Update inactive users
            document.querySelector('.inactive-users .number').textContent = data.inactiveUsers;
            
            // Update new users
            document.querySelector('.new-users .number').textContent = data.newUsers;
        })
        .catch(error => console.error('Error fetching stats:', error));
}

// Update time
function updateTime() {
    const now = new Date();
    const timeElement = document.getElementById('current-time');
    if (timeElement) {
        timeElement.textContent = now.toLocaleTimeString();
    }
} 