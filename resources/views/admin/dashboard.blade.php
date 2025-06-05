<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin - FacturePro</title>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Pass activities data to JavaScript -->
    <script>
        window.recentActivities = @json($recentActivities);

        document.addEventListener('DOMContentLoaded', function() {
            // Theme toggle functionality
            const themeToggle = document.getElementById('themeToggle');
            const html = document.documentElement;
            const themeIcon = themeToggle.querySelector('i');

            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            html.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);

            themeToggle.addEventListener('click', function() {
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                html.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
            });

            function updateThemeIcon(theme) {
                themeIcon.className = theme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
            }

            // Get all nav links
            const navLinks = document.querySelectorAll('.sidebar-nav a');
            
            // Function to show a section
            function showSection(sectionId) {
                // Hide all sections
                document.querySelectorAll('.section').forEach(section => {
                    section.style.display = 'none';
                });
                
                // Show the target section
                const targetSection = document.getElementById(sectionId);
                if (targetSection) {
                    targetSection.style.display = 'block';
                }
                
                // Update active state in navigation
                navLinks.forEach(link => {
                    const linkTarget = link.getAttribute('href').substring(1);
                    if (linkTarget === sectionId) {
                        link.parentElement.classList.add('active');
                    } else {
                        link.parentElement.classList.remove('active');
                    }
                });
            }
            
            // Handle navigation clicks
            navLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const targetId = link.getAttribute('href').substring(1);
                    showSection(targetId);
                });
            });

            // Handle "Voir tout" link
            document.querySelector('.view-all').addEventListener('click', (e) => {
                e.preventDefault();
                showSection('activities');
            });

            const statusFilter = document.getElementById('statusFilter');
            const searchInput = document.getElementById('userSearchInput');
            let typingTimer;

            // Function to filter users
            function filterUsers() {
                const status = statusFilter.value;
                const searchTerm = searchInput.value;
                
                // Only include status in query if it's not empty
                const queryParams = new URLSearchParams();
                if (status) {
                    queryParams.append('status', status);
                }
                if (searchTerm) {
                    queryParams.append('search', searchTerm);
                }

                fetch(`/admin/users/filter?${queryParams.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.querySelector('.users-table-container').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }

            // Event listener for status filter
            statusFilter.addEventListener('change', filterUsers);

            // Event listener for search with debounce
            searchInput.addEventListener('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(filterUsers, 500); // Wait 500ms after user stops typing
            });

            // Bulk actions functionality
            const selectAllCheckbox = document.getElementById('selectAllUsers');
            const bulkActionSelect = document.getElementById('bulkAction');
            const applyBulkActionBtn = document.querySelector('.apply-btn');
            
            // Toggle all checkboxes
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.user-select');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActionButton();
            });

            // Update apply button state when individual checkboxes change
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('user-select')) {
                    updateBulkActionButton();
                }
            });

            // Update bulk action button state
            function updateBulkActionButton() {
                const checkedBoxes = document.querySelectorAll('.user-select:checked');
                applyBulkActionBtn.disabled = checkedBoxes.length === 0 || !bulkActionSelect.value;
            }

            // Enable/disable apply button when bulk action changes
            bulkActionSelect.addEventListener('change', updateBulkActionButton);

            // Handle bulk action
            applyBulkActionBtn.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.user-select:checked');
                const selectedUserIds = Array.from(checkedBoxes).map(cb => cb.value);
                const action = bulkActionSelect.value;

                if (!selectedUserIds.length || !action) {
                    return;
                }

                const confirmMessage = {
                    'activate': 'Êtes-vous sûr de vouloir activer les utilisateurs sélectionnés ?',
                    'deactivate': 'Êtes-vous sûr de vouloir désactiver les utilisateurs sélectionnés ?',
                    'delete': 'Êtes-vous sûr de vouloir supprimer les utilisateurs sélectionnés ? Cette action est irréversible.'
                }[action];

                if (confirm(confirmMessage)) {
                    fetch(`/admin/users/bulk-action`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            action: action,
                            userIds: selectedUserIds
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Refresh the users table
                            filterUsers();
                            
                            // Reset bulk action controls
                            bulkActionSelect.value = '';
                            selectAllCheckbox.checked = false;
                            updateBulkActionButton();
                            
                            // Show success message
                            alert(data.message);
                        } else {
                            alert(data.message || 'Une erreur est survenue');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Une erreur est survenue lors de l\'exécution de l\'action groupée');
                    });
                }
            });
        });
    </script>
    
    <!-- Styles -->
    @vite([
        'resources/css/admin_dashboard.css',
        'resources/js/admin_dashboard.js'
    ])
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            /* Light theme */
            --primary-color: #1a237e;
            --primary-light: #534bae;
            --primary-dark: #000051;
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-tertiary: #edf2f7;
            --text-primary: #2d3748;
            --text-secondary: #4a5568;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.05);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.05);
            --card-bg: #ffffff;
            --hover-bg: #f7fafc;
            --success-color: #48bb78;
            --danger-color: #f56565;
            --warning-color: #ed8936;
            --info-color: #4299e1;
        }

        [data-theme="dark"] {
            --primary-color: #534bae;
            --primary-light: #1a237e;
            --primary-dark: #000051;
            --bg-primary: #111827;
            --bg-secondary: #1f2937;
            --bg-tertiary: #374151;
            --text-primary: #f3f4f6;
            --text-secondary: #d1d5db;
            --border-color: #374151;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.3);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.3);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.3);
            --card-bg: #1f2937;
            --hover-bg: #2d3748;
            --success-color: #31c48d;
            --danger-color: #f98080;
            --warning-color: #f6ad55;
            --info-color: #63b3ed;
        }

        /* Global Styles */
        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            width: 280px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            color: white;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-lg);
            z-index: 1000;
        }

        .sidebar-header {
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            margin: 0.25rem 0;
        }

        .sidebar-nav a:hover,
        .sidebar-nav li.active a {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: white;
            transform: translateX(5px);
        }

        .sidebar-nav i {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
            background: var(--bg-primary);
            transition: all 0.3s ease;
        }

        /* Cards */
        .card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Activity Items */
        .activity-item {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-md);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            background: var(--bg-tertiary);
            transition: all 0.3s ease;
        }

        /* Form Controls */
        input, select, textarea {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: 100%;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px var(--primary-color);
            outline: none;
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
        }

        /* Theme Toggle */
        .theme-toggle {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 50px;
            height: 50px;
            border-radius: 25px;
            background: var(--primary-color);
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .theme-toggle:hover {
            transform: scale(1.1) rotate(8deg);
            background: var(--primary-light);
        }

        /* Profile Section Styles */
        .profile-section {
            padding: 2rem;
        }

        .profile-card {
            background: var(--bg-secondary);
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            padding: 2.5rem;
            margin-top: 1.5rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-2px);
        }

        .info-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .info-header h3 {
            margin: 0;
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 600;
        }

        .btn-edit {
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-edit:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-1px);
        }

        .info-group {
            margin-bottom: 2rem;
            background: var(--bg-primary);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .info-group:hover {
            background: var(--bg-tertiary);
        }

        .info-group label {
            display: block;
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .info-group p {
            color: var(--text-primary);
            font-size: 1.1rem;
            margin: 0;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-group label {
            display: block;
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(var(--primary-color-rgb), 0.1);
            outline: none;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
        }

        .btn {
            padding: 0.875rem 1.75rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            font-size: 1rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-secondary {
            background: var(--bg-tertiary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Messages Section Styles */
        .messages-section {
            padding: 2rem;
        }

        .messages-container {
            display: grid;
            gap: 1.5rem;
            padding: 1rem;
        }

        .message-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .message-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .message-card.unread {
            border-left: 4px solid var(--primary-color);
            background: linear-gradient(to right, rgba(var(--primary-color-rgb), 0.05), var(--bg-secondary));
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .message-info h3 {
            margin: 0 0 0.5rem 0;
            color: var(--text-primary);
            font-size: 1.2rem;
            font-weight: 600;
        }

        .message-email {
            color: var(--text-secondary);
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .message-email i {
            font-size: 0.9rem;
            color: var(--primary-color);
        }

        .message-meta {
            text-align: right;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-end;
        }

        .message-date {
            color: var(--text-secondary);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .message-date i {
            font-size: 0.9rem;
        }

        .unread-badge {
            background: var(--primary-color);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .unread-badge i {
            font-size: 0.8rem;
        }

        .message-content {
            color: var(--text-primary);
            line-height: 1.6;
            margin: 1.5rem 0;
            font-size: 1rem;
            white-space: pre-wrap;
            background: var(--bg-primary);
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .message-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .btn-reply, .btn-mark-read {
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .btn-reply {
            background: var(--primary-color);
            color: white;
        }

        .btn-mark-read {
            background: var(--success-color);
            color: white;
        }

        .btn-reply:hover, .btn-mark-read:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .no-messages {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--bg-secondary);
            border-radius: 16px;
            border: 2px dashed var(--border-color);
            color: var(--text-secondary);
        }

        .no-messages i {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: var(--text-secondary);
            opacity: 0.5;
        }

        .no-messages p {
            font-size: 1.1rem;
            margin: 0;
        }

        /* Tables */
        .users-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .users-table th {
            background: var(--bg-tertiary);
            color: var(--text-primary);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        .users-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
        }

        .users-table tr:hover td {
            background: var(--hover-bg);
        }

        /* Status Badges */
        .status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status.active {
            background: var(--success-color);
            color: white;
        }

        .status.inactive {
            background: var(--danger-color);
            color: white;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-primary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-light);
        }

        .messages-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding: 1rem;
        }

        .message-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .message-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .message-card.unread {
            border-left: 4px solid var(--primary-color);
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .message-info h3 {
            margin: 0;
            color: var(--text-primary);
        }

        .message-email {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .message-meta {
            text-align: right;
        }

        .message-date {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .unread-badge {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8rem;
            margin-left: 0.5rem;
        }

        .message-content {
            color: var(--text-primary);
            line-height: 1.5;
            margin: 1rem 0;
            white-space: pre-wrap;
        }

        .message-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .btn-reply, .btn-mark-read {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-reply {
            background: var(--primary-color);
            color: white;
        }

        .btn-mark-read {
            background: var(--success-color);
            color: white;
        }

        .btn-reply:hover, .btn-mark-read:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .no-messages {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }

        .no-messages i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .editor-container {
            display: flex;
            gap: 2rem;
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        .editor-sidebar {
            width: 250px;
            background: var(--bg-tertiary);
            border-radius: 8px;
            padding: 1rem;
        }

        .editable-sections {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .editable-sections li {
            padding: 0.75rem 1rem;
            margin: 0.5rem 0;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-secondary);
            transition: all 0.3s ease;
        }

        .editable-sections li:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .editable-sections li.active {
            background: var(--primary-color);
            color: white;
        }

        .editor-content {
            flex: 1;
            background: var(--bg-primary);
            border-radius: 8px;
            padding: 1.5rem;
        }

        .edit-field {
            margin-bottom: 1.5rem;
        }

        .edit-field label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 500;
        }

        .content-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background: var(--bg-secondary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .content-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(var(--primary-color-rgb), 0.1);
            outline: none;
        }

        .features-list {
            margin-bottom: 1rem;
        }

        .feature-item {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .feature-item .feature-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .btn-delete-feature {
            color: var(--danger-color);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .btn-delete-feature:hover {
            background: rgba(var(--danger-color-rgb), 0.1);
        }

        /* Enhanced Users Section Styles */
        .users-section {
            background: var(--bg-secondary);
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            margin: 1.5rem;
            padding: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid var(--border-color);
        }

        .header-left h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .header-left p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .header-actions {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .filter-wrapper {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .custom-select {
            min-width: 180px;
            padding: 0.75rem 2.5rem 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }

        .custom-select:hover, .custom-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(var(--primary-color-rgb), 0.1);
        }

        .search-bar {
            position: relative;
            min-width: 300px;
        }

        .search-bar input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .search-bar input:hover {
            border-color: var(--primary-color);
        }

        .search-bar input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(var(--primary-color-rgb), 0.1);
            outline: none;
        }

        .search-bar i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .users-table-container {
            background: var(--bg-primary);
            border-radius: 12px;
            overflow: hidden;
            margin: 1rem 0;
            box-shadow: var(--shadow-md);
        }

        .users-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .users-table th {
            background: var(--bg-tertiary);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .users-table th:first-child {
            padding-left: 1.5rem;
            border-top-left-radius: 12px;
        }

        .users-table th:last-child {
            padding-right: 1.5rem;
            border-top-right-radius: 12px;
        }

        .users-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .users-table tr:last-child td {
            border-bottom: none;
        }

        .users-table tr:hover td {
            background: var(--hover-bg);
        }

        .status {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            line-height: 1;
        }

        .status::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }

        .status.active {
            background: rgba(72, 187, 120, 0.1);
            color: #48bb78;
        }

        .status.active::before {
            background: #48bb78;
        }

        .status.inactive {
            background: rgba(245, 101, 101, 0.1);
            color: #f56565;
        }

        .status.inactive::before {
            background: #f56565;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 8px;
            background: var(--bg-tertiary);
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .action-btn.view:hover {
            background: var(--primary-color);
            color: white;
        }

        .action-btn.toggle-status:hover {
            background: var(--warning-color);
            color: white;
        }

        .action-btn.delete:hover {
            background: var(--danger-color);
            color: white;
        }

        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            background: var(--bg-secondary);
            border-radius: 0 0 12px 12px;
            border-top: 1px solid var(--border-color);
        }

        .bulk-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .apply-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            background: var(--primary-color);
            color: white;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .apply-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .apply-btn:not(:disabled):hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.2);
        }

        /* Checkbox styling */
        .users-table input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .users-table input[type="checkbox"]:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Pagination styling */
        .pagination {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .pagination .page-link {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: var(--bg-tertiary);
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        @media (max-width: 1024px) {
            .header-actions {
                flex-direction: column;
                gap: 1rem;
            }

            .search-bar {
                min-width: 100%;
            }

            .filter-wrapper {
                width: 100%;
            }

            .custom-select {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .users-section {
                margin: 1rem;
                padding: 1rem;
            }

            .section-header {
                flex-direction: column;
                gap: 1rem;
            }

            .table-footer {
                flex-direction: column;
                gap: 1rem;
            }

            .bulk-actions {
                width: 100%;
                flex-direction: column;
            }

            .pagination {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="admin-dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo_all_in_one.png') }}" alt="Logo" class="logo">
                <h2>All In One</h2>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="active">
                        <a href="#dashboard">
                            <i class="fas fa-home"></i> 
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li>
                        <a href="#users">
                            <i class="fas fa-users"></i> 
                            <span>Utilisateurs</span>
                        </a>
                    </li>
                    <li>
                        <a href="#activities">
                            <i class="fas fa-history"></i> 
                            <span>Activités</span>
                        </a>
                    </li>
                    <li>
                        <a href="#messages">
                            <i class="fas fa-envelope"></i> 
                            <span>Messages</span>
                            <span class="badge-count">{{ $unreadMessages ?? 0 }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="#profile">
                            <i class="fas fa-user-circle"></i>
                            <span>Mon Compte</span>
                        </a>
                    </li>
                    
                </ul>
            </nav>

            <div class="sidebar-footer">
                <div class="admin-info">
                    <div class="admin-details">
                        <span class="admin-name">{{ Auth::user()->name }}</span>
                        <span class="admin-email">{{ Auth::user()->email }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> 
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>

            <style>
                .sidebar-footer {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    width: 280px;
                    background: linear-gradient(to bottom, transparent, var(--primary-dark) 20%);
                    padding: 1.5rem;
                    border-top: 1px solid rgba(255, 255, 255, 0.1);
                }

                .admin-info {
                    margin-bottom: 1rem;
                }

                .admin-details {
                    display: flex;
                    flex-direction: column;
                    gap: 0.25rem;
                }

                .admin-name {
                    color: white;
                    font-weight: 500;
                    font-size: 1rem;
                }

                .admin-email {
                    color: rgba(255, 255, 255, 0.7);
                    font-size: 0.875rem;
                }

                .logout-form {
                    margin-top: 1rem;
                }

                .logout-btn {
                    width: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.5rem;
                    padding: 0.75rem;
                    background: rgba(255, 255, 255, 0.1);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    border-radius: 8px;
                    color: white;
                    font-size: 0.9rem;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }

                .logout-btn:hover {
                    background: rgba(255, 255, 255, 0.2);
                    transform: translateY(-1px);
                }

                .logout-btn i {
                    font-size: 1rem;
                }
            </style>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Dashboard Content -->
            <div class="dashboard-content section">
                <!-- Welcome Banner -->
                <div class="welcome-banner">
                    <div class="welcome-text">
                        <h1>Bonjour, {{ Auth::user()->name }}</h1>
                        <p>Voici un aperçu de l'activité des utilisateurs</p>
                    </div>
                    <div class="date-time">
                        <span class="current-date">{{ now()->format('d M Y') }}</span>
                        <span class="current-time" id="current-time"></span>
                    </div>
                </div>

                <!-- User Stats -->
                <div class="stats-grid">
                    <div class="stat-card total-users">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Utilisateurs</h3>
                            <p class="number">{{ $totalUsers ?? 0 }}</p>
                            <span class="trend up">
                                <i class="fas fa-arrow-up"></i> +5.2%
                            </span>
                        </div>
                    </div>
                    <div class="stat-card active-users">
                        <div class="stat-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Utilisateurs Actifs</h3>
                            <p class="number">{{ $activeUsers ?? 0 }}</p>
                            <span class="trend up">
                                <i class="fas fa-arrow-up"></i> +3.1%
                            </span>
                        </div>
                    </div>
                    <div class="stat-card inactive-users">
                        <div class="stat-icon">
                            <i class="fas fa-user-slash"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Utilisateurs Inactifs</h3>
                            <p class="number">{{ $inactiveUsers ?? 0 }}</p>
                            <span class="trend down">
                                <i class="fas fa-arrow-down"></i> -2.4%
                            </span>
                        </div>
                    </div>
                    <div class="stat-card new-users">
                        <div class="stat-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Nouveaux (30j)</h3>
                            <p class="number">{{ $newUsers ?? 0 }}</p>
                            <span class="trend up">
                                <i class="fas fa-arrow-up"></i> +8.3%
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Main Charts Section -->
                <div class="charts-section">
                    <div class="chart-container user-growth">
                        <div class="chart-header">
                            <h3>Croissance des Utilisateurs</h3>
                            <div class="chart-actions">
                                <select class="chart-period">
                                    <option value="week">Cette semaine</option>
                                    <option value="month" selected>Ce mois</option>
                                    <option value="year">Cette année</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart-body">
                            <canvas id="userGrowthChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-container user-status">
                        <div class="chart-header">
                            <h3>Statut des Utilisateurs</h3>
                            <div class="chart-actions">
                                <button class="chart-refresh">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="chart-body">
                            <canvas id="userStatusChart"></canvas>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // User Status Chart
                                const userStatusCtx = document.getElementById('userStatusChart').getContext('2d');
                                new Chart(userStatusCtx, {
                                    type: 'doughnut',
                                    data: {
                                        labels: ['Actifs', 'Inactifs'],
                                        datasets: [{
                                            data: [{{ $activeUsers }}, {{ $inactiveUsers }}],
                                            backgroundColor: [
                                                '#48bb78', // green for active
                                                '#f56565'  // red for inactive
                                            ]
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        legend: {
                                            position: 'bottom'
                                        },
                                        title: {
                                            display: true,
                                            text: 'Répartition des Utilisateurs par Statut'
                                        }
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="recent-activity">
                    <div class="section-header">
                        <h3>Activité Récente</h3>
                        <a href="#activities" class="view-all">
                            <i class="fas fa-external-link-alt"></i> Voir tout
                        </a>
                    </div>
                    <div class="activity-list">
                        @foreach($recentActivities as $activity)
                        <div class="activity-item {{ $activity->type }}">
                            <div class="activity-icon">
                                <i class="fas {{ $activity->icon_class }}"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-header">
                                    <span class="activity-user">{{ $activity->user ? $activity->user->name : 'Système' }}</span>
                                    <span class="activity-action">{{ $activity->description }}</span>
                                </div>
                                <div class="activity-time">{{ $activity->time_ago }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Activities Section -->
            <div id="activities" class="activities-section section" style="display: none;">
                <div class="section-header">
                    <h2>Historique des Activités</h2>
                    <p>Consultez toutes les activités des utilisateurs</p>
                </div>

                <div class="activities-filters">
                    <div class="filter-group">
                        <label>Type</label>
                        <select class="activity-type-filter">
                            <option value="">Tous les types</option>
                            <option value="login">Connexion</option>
                            <option value="logout">Déconnexion</option>
                            <option value="create">Création</option>
                            <option value="update">Modification</option>
                            <option value="delete">Suppression</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Date</label>
                        <input type="date" class="activity-date-filter">
                    </div>
                </div>

                <div class="activities-list">
                    @foreach($recentActivities as $activity)
                    <div class="activity-item {{ $activity->type }}">
                        <div class="activity-icon">
                            @if($activity->type === 'login')
                                <i class="fas fa-sign-in-alt text-success"></i>
                            @elseif($activity->type === 'logout')
                                <i class="fas fa-sign-out-alt text-danger"></i>
                            @elseif($activity->type === 'create')
                                <i class="fas fa-plus-circle text-primary"></i>
                            @elseif($activity->type === 'update')
                                <i class="fas fa-edit text-warning"></i>
                            @elseif($activity->type === 'delete')
                                <i class="fas fa-trash text-danger"></i>
                            @endif
                        </div>
                        <div class="activity-content">
                            <div class="activity-user">{{ $activity->user->name }}</div>
                            <div class="activity-description">{{ $activity->description }}</div>
                            <div class="activity-timestamp">{{ $activity->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Profile Section -->
            <div id="profile" class="profile-section section" style="display: none;">
                <div class="section-header">
                <h2>Mon Compte</h2>
                    </div>

                <div class="profile-card">
                    <div class="profile-info">
                        <div class="info-header">
                            <h3>Informations Personnelles</h3>
                            <button type="button" class="btn-edit" onclick="toggleEditMode()">
                                <i class="fas fa-edit"></i> Modifier
                            </button>
                        </div>

                        <!-- View Mode -->
                        <div id="viewMode" class="info-content">
                            <div class="info-group">
                                <label>Nom complet</label>
                                <p>{{ Auth::user()->name }}</p>
                            </div>
                            <div class="info-group">
                                <label>Email</label>
                        <p>{{ Auth::user()->email }}</p>
                    </div>
                </div>

                        <!-- Edit Mode -->
                        <div id="editMode" class="info-content" style="display: none;">
                            <form id="profileForm" onsubmit="updateProfile(event)">
                        <div class="form-group">
                            <label for="name">Nom complet</label>
                                    <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                                    <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" class="form-control" required>
                        </div>
                                <div class="form-group">
                                    <label for="current_password">Mot de passe actuel</label>
                                    <input type="password" id="current_password" name="current_password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="new_password">Nouveau mot de passe (optionnel)</label>
                                    <input type="password" id="new_password" name="new_password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="new_password_confirmation">Confirmer le nouveau mot de passe</label>
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control">
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    <button type="button" class="btn btn-secondary" onclick="toggleEditMode()">Annuler</button>
                                </div>
                    </form>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .profile-section {
                    padding: 2rem;
                }

                .profile-card {
                    background: var(--bg-secondary);
                    border-radius: 16px;
                    box-shadow: var(--shadow-lg);
                    padding: 2.5rem;
                    margin-top: 1.5rem;
                    border: 1px solid var(--border-color);
                    transition: all 0.3s ease;
                }

                .profile-card:hover {
                    transform: translateY(-2px);
                }

                .info-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 2.5rem;
                    padding-bottom: 1.5rem;
                    border-bottom: 1px solid var(--border-color);
                }

                .info-header h3 {
                    margin: 0;
                    color: var(--text-primary);
                    font-size: 1.5rem;
                    font-weight: 600;
                }

                .btn-edit {
                    background: var(--bg-tertiary);
                    border: 1px solid var(--border-color);
                    color: var(--text-primary);
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    font-size: 0.95rem;
                    padding: 0.75rem 1.25rem;
                    border-radius: 8px;
                    transition: all 0.2s;
                }

                .btn-edit:hover {
                    background: var(--primary-color);
                    color: white;
                    border-color: var(--primary-color);
                    transform: translateY(-1px);
                }

                .info-group {
                    margin-bottom: 2rem;
                    background: var(--bg-primary);
                    padding: 1.5rem;
                    border-radius: 12px;
                    border: 1px solid var(--border-color);
                    transition: all 0.2s ease;
                }

                .info-group:hover {
                    background: var(--bg-tertiary);
                }

                .info-group label {
                    display: block;
                    color: var(--text-secondary);
                    font-size: 0.9rem;
                    margin-bottom: 0.75rem;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    font-weight: 600;
                }

                .info-group p {
                    color: var(--text-primary);
                    font-size: 1.1rem;
                    margin: 0;
                    font-weight: 500;
                }

                .form-group {
                    margin-bottom: 2rem;
                }

                .form-group label {
                    display: block;
                    color: var(--text-secondary);
                    font-size: 0.9rem;
                    margin-bottom: 0.75rem;
                    font-weight: 600;
                }

                .form-control {
                    width: 100%;
                    padding: 1rem;
                    border: 2px solid var(--border-color);
                    border-radius: 8px;
                    background: var(--bg-primary);
                    color: var(--text-primary);
                    font-size: 1rem;
                    transition: all 0.2s;
                }

                .form-control:focus {
                    border-color: var(--primary-color);
                    box-shadow: 0 0 0 3px rgba(var(--primary-color-rgb), 0.1);
                    outline: none;
                }

                .form-actions {
                    display: flex;
                    gap: 1rem;
                    margin-top: 2.5rem;
                }

                .btn {
                    padding: 0.875rem 1.75rem;
                    border-radius: 8px;
                    font-weight: 500;
                    cursor: pointer;
                    border: none;
                    transition: all 0.2s;
                    font-size: 1rem;
                }

                .btn-primary {
                    background: var(--primary-color);
                    color: white;
                }

                .btn-secondary {
                    background: var(--bg-tertiary);
                    color: var(--text-primary);
                    border: 1px solid var(--border-color);
                }

                .btn:hover {
                    transform: translateY(-2px);
                    box-shadow: var(--shadow-md);
                }
            </style>

            <script>
                function toggleEditMode() {
                    const viewMode = document.getElementById('viewMode');
                    const editMode = document.getElementById('editMode');
                    
                    if (viewMode.style.display !== 'none') {
                        viewMode.style.display = 'none';
                        editMode.style.display = 'block';
                    } else {
                        viewMode.style.display = 'block';
                        editMode.style.display = 'none';
                        // Reset form
                        document.getElementById('profileForm').reset();
                    }
                }

                function updateProfile(event) {
                    event.preventDefault();
                    
                    const formData = new FormData(event.target);
                    const data = Object.fromEntries(formData.entries());
                    
                    fetch('/admin/profile/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Profil mis à jour avec succès');
                            // Update displayed information
                            document.querySelector('#viewMode .info-group:nth-child(1) p').textContent = formData.get('name');
                            document.querySelector('#viewMode .info-group:nth-child(2) p').textContent = formData.get('email');
                            toggleEditMode();
                        } else {
                            alert(data.message || 'Une erreur est survenue');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Une erreur est survenue lors de la mise à jour du profil');
                    });
                }
            </script>

            <!-- User Details Section -->
            <div class="user-details-section section" style="display: none;">
                <!-- Content will be loaded here -->
            </div>

            <!-- Theme Toggle Button -->
            <button class="theme-toggle" id="themeToggle">
                <i class="fas fa-moon"></i>
            </button>

            <!-- User Management Section -->
            <div class="users-section section" style="display: none;">
                <div class="section-header">
                    <div class="header-left">
                        <h2>Gestion des Utilisateurs</h2>
                        <p>{{ $totalUsers ?? 0 }} utilisateurs au total</p>
                    </div>
                    <div class="header-actions">
                        <div class="filter-wrapper">
                            <div class="status-filter">
                                <select id="statusFilter" class="custom-select">
                                    <option value="">Statut: Tous</option>
                                    <option value="active">Statut: Actif</option>
                                    <option value="inactive">Statut: Inactif</option>
                                </select>
                            </div>
                    </div>
                    <div class="search-bar">
                        <input type="text" id="userSearchInput" placeholder="Rechercher un utilisateur...">
                            <i class="fas fa-search"></i>
                    </div>
                    </div>
                </div>

                <div class="users-table-container">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAllUsers">
                                </th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Entreprise</th>
                                <th>Adresse</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr data-user-id="{{ $customer->id }}">
                                <td>
                                    <input type="checkbox" class="user-select" value="{{ $customer->id }}">
                                </td>
                                <td>{{ $customer->nom }}</td>
                                <td>{{ $customer->prenom }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->telephone }}</td>
                                <td>{{ $customer->entreprise ?? '-' }}</td>
                                <td>{{ $customer->adresse_entreprise ?? '-' }}</td>
                                <td>
                                    <span class="status {{ $customer->status ?? 'active' }}">
                                        {{ ucfirst($customer->status ?? 'actif') }}
                                    </span>
                                </td>
                                <td class="actions">
                                    <button onclick="showUserDetails({{ $customer->id }})" class="action-btn view" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn toggle-status" 
                                            onclick="toggleUserStatus(this, {{ $customer->id }})"
                                            data-status="{{ $customer->status ?? 'active' }}"
                                            title="{{ ($customer->status ?? 'active') == 'active' ? 'Désactiver' : 'Activer' }}">
                                        <i class="fas {{ ($customer->status ?? 'active') == 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                    <button class="action-btn delete" 
                                            onclick="deleteUser(this, {{ $customer->id }})"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <div class="bulk-actions">
                        <select id="bulkAction">
                            <option value="">Actions groupées</option>
                            <option value="activate">Activer</option>
                            <option value="deactivate">Désactiver</option>
                            <option value="delete">Supprimer</option>
                        </select>
                        <button class="apply-btn" disabled>Appliquer</button>
                    </div>
                    <div class="pagination">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>

            <!-- Messages Section -->
            <div id="messages" class="messages-section section" style="display: none;">
                <div class="section-header">
                    <h2>Messages des Utilisateurs</h2>
                    <p>{{ $unreadMessages }} message(s) non lu(s)</p>
                </div>

                <div class="messages-container">
                    @if(isset($messages) && $messages->count() > 0)
                        @foreach($messages as $message)
                            <div class="message-card {{ $message->read ? 'read' : 'unread' }}" data-id="{{ $message->id }}">
                                <div class="message-header">
                                    <div class="message-info">
                                        <h3>{{ $message->name }}</h3>
                                        <span class="message-email">{{ $message->email }}</span>
                                    </div>
                                    <div class="message-meta">
                                        <span class="message-date">{{ $message->created_at->format('d/m/Y H:i') }}</span>
                                        @if(!$message->read)
                                            <span class="unread-badge">Non lu</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="message-content">
                                    {{ $message->message }}
                                </div>
                                <div class="message-actions">
                                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $message->email }}" target="_blank" class="btn-reply">
                                        <i class="fas fa-reply"></i> Répondre via Gmail
                                    </a>
                                    @if(!$message->read)
                                        <button class="btn-mark-read" onclick="markMessageAsRead({{ $message->id }})">
                                            <i class="fas fa-check"></i> Marquer comme lu
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="no-messages">
                            <i class="fas fa-inbox"></i>
                            <p>Aucun message pour le moment</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Home Editor Section -->
            <div class="home-editor-section section" style="display: none;">
                <div class="section-header">
                    <h2>Éditeur de Page d'Accueil</h2>
                    <div class="editor-actions">
                        <button class="btn btn-primary save-all-changes">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>

                <div class="editor-container">
                    <div class="editor-sidebar">
                        <h3>Sections</h3>
                        <ul class="editable-sections">
                            <li data-section="hero" class="active">
                                <i class="fas fa-home"></i> En-tête
                            </li>
                            <li data-section="features">
                                <i class="fas fa-star"></i> Fonctionnalités
                            </li>
                            <li data-section="modules">
                                <i class="fas fa-cube"></i> Modules
                            </li>
                            <li data-section="pricing">
                                <i class="fas fa-tags"></i> Tarifs
                            </li>
                            <li data-section="contact">
                                <i class="fas fa-envelope"></i> Contact
                            </li>
                        </ul>
                    </div>
                    <div class="editor-content">
                        <!-- Hero Section Editor -->
                        <div class="section-editor hero-editor" data-section="hero">
                            <h3>Section En-tête</h3>
                            <div class="edit-field">
                                <label>Titre principal</label>
                                <input type="text" class="content-input" data-key="title" value="">
                            </div>
                            <div class="edit-field">
                                <label>Description</label>
                                <textarea class="content-input" data-key="description" rows="3"></textarea>
                            </div>
                            <div class="edit-field">
                                <label>Texte du bouton principal</label>
                                <input type="text" class="content-input" data-key="cta_text" value="">
                            </div>
                        </div>

                        <!-- Features Section Editor -->
                        <div class="section-editor features-editor" data-section="features" style="display: none;">
                            <h3>Section Fonctionnalités</h3>
                            <div class="features-list">
                                <!-- Features will be loaded dynamically -->
                            </div>
                            <button class="btn btn-secondary add-feature">
                                <i class="fas fa-plus"></i> Ajouter une fonctionnalité
                            </button>
                        </div>

                        <!-- Other section editors will be similar -->
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modals -->
    <!-- User Action Modal -->
    <div id="userActionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmation</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir <span class="action-text"></span> cet utilisateur ?</p>
            </div>
            <div class="modal-actions">
                <button class="cancel-btn">Annuler</button>
                <button class="confirm-btn">Confirmer</button>
            </div>
        </div>
    </div>

    <script>
        function markMessageAsRead(messageId) {
            fetch(`/admin/messages/${messageId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const messageCard = document.querySelector(`.message-card[data-id="${messageId}"]`);
                    messageCard.classList.remove('unread');
                    messageCard.classList.add('read');
                    
                    // Remove unread badge and mark as read button
                    const unreadBadge = messageCard.querySelector('.unread-badge');
                    const markReadBtn = messageCard.querySelector('.btn-mark-read');
                    if (unreadBadge) unreadBadge.remove();
                    if (markReadBtn) markReadBtn.remove();
                    
                    // Update unread count in header
                    const unreadCount = document.querySelector('.section-header p');
                    const currentCount = parseInt(unreadCount.textContent);
                    if (currentCount > 0) {
                        unreadCount.textContent = `${currentCount - 1} message(s) non lu(s)`;
                    }
                    
                    // Update sidebar badge count
                    const sidebarBadge = document.querySelector('.sidebar-nav .badge-count');
                    if (sidebarBadge) {
                        const currentBadgeCount = parseInt(sidebarBadge.textContent);
                        if (currentBadgeCount > 0) {
                            sidebarBadge.textContent = currentBadgeCount - 1;
                        }
                    }
                }
            });
        }

        function showUserDetails(userId) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.style.display = 'none';
            });
            
            // Show user details section
            const userDetailsSection = document.querySelector('.user-details-section');
            userDetailsSection.style.display = 'block';
            
            // Fetch and display user details
            fetch(`/admin/voir_users/${userId}`)
                .then(response => response.text())
                .then(html => {
                    userDetailsSection.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    userDetailsSection.innerHTML = '<div class="error">Error loading user details</div>';
                });
        }

        function toggleUserStatus(button, userId) {
            const currentStatus = button.getAttribute('data-status');
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            
            fetch(`/admin/customers/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button
                    const icon = button.querySelector('i');
                    button.setAttribute('data-status', newStatus);
                    
                    if (newStatus === 'active') {
                        icon.className = 'fas fa-ban';
                        button.title = 'Désactiver';
                    } else {
                        icon.className = 'fas fa-check';
                        button.title = 'Activer';
                    }
                    
                    // Update status cell
                    const row = button.closest('tr');
                    const statusCell = row.querySelector('.status');
                    statusCell.className = `status ${newStatus}`;
                    statusCell.textContent = newStatus === 'active' ? 'Actif' : 'Inactif';
                    
                    // Show success message
                    alert('Le statut a été mis à jour avec succès');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue lors de la mise à jour du statut');
            });
        }

        function deleteUser(button, userId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) {
                fetch(`/admin/customers/${userId}/delete`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the row from the table
                        const row = button.closest('tr');
                        row.remove();
                        
                        // Show success message
                        alert('L\'utilisateur a été supprimé avec succès');
                        
                        // Update the total users count if it exists
                        const totalUsersElement = document.querySelector('.total-users .number');
                        if (totalUsersElement) {
                            const currentTotal = parseInt(totalUsersElement.textContent);
                            totalUsersElement.textContent = currentTotal - 1;
                        }
                    } else {
                        alert(data.message || 'Une erreur est survenue lors de la suppression');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue lors de la suppression');
                });
            }
        }
    </script>
</body>
</html>
