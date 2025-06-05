@extends('layouts.admin')

@section('content')
<div class="activities-page">
    <div class="page-header">
        <div class="header-content">
            <h2>Historique des Activités</h2>
            <p class="text-muted">Consultez toutes les activités des utilisateurs</p>
        </div>
        <div class="filters">
            <div class="filter-group">
                <label for="typeFilter">Type</label>
                <select id="typeFilter" class="form-select">
                    <option value="">Tous les types</option>
                    <option value="login">Connexions</option>
                    <option value="logout">Déconnexions</option>
                    <option value="new_user">Nouveaux utilisateurs</option>
                    <option value="status_change">Changements de statut</option>
                    <option value="profile_update">Mises à jour de profil</option>
                    <option value="user_deleted">Suppressions</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="dateFilter">Date</label>
                <input type="date" id="dateFilter" class="form-control">
            </div>
        </div>
    </div>

    <div class="activities-container">
        <div class="activities-list">
            @foreach($activities as $activity)
            <div class="activity-item {{ $activity->type }}" data-type="{{ $activity->type }}" data-date="{{ $activity->created_at->format('Y-m-d') }}">
                <div class="activity-icon">
                    <i class="fas {{ $activity->icon_class }}"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-header">
                        <span class="activity-user">
                            @if($activity->user)
                                {{ $activity->user->name }}
                            @else
                                Système
                            @endif
                        </span>
                        <span class="activity-time" title="{{ $activity->created_at->format('d/m/Y H:i:s') }}">
                            {{ $activity->time_ago }}
                        </span>
                    </div>
                    <p class="activity-description">{{ $activity->description }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pagination-container">
            {{ $activities->links() }}
        </div>
    </div>
</div>

<style>
.activities-page {
    padding: 30px;
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 30px;
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.header-content {
    margin-bottom: 20px;
}

.header-content h2 {
    margin: 0 0 8px 0;
    color: #2d3748;
    font-size: 1.5rem;
}

.text-muted {
    color: #718096;
    margin: 0;
}

.filters {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.filter-group label {
    display: block;
    margin-bottom: 8px;
    color: #4a5568;
    font-weight: 500;
}

.form-select, .form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    background-color: white;
    color: #2d3748;
    transition: all 0.2s;
}

.form-select:focus, .form-control:focus {
    outline: none;
    border-color: #4299e1;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
}

.activities-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    overflow: hidden;
}

.activities-list {
    padding: 20px;
}

.activity-item {
    display: flex;
    gap: 16px;
    padding: 16px;
    border-radius: 8px;
    transition: all 0.2s;
    margin-bottom: 12px;
}

.activity-item:last-child {
    margin-bottom: 0;
}

.activity-item:hover {
    background-color: #f7fafc;
}

.activity-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}

.activity-user {
    font-weight: 600;
    color: #2d3748;
}

.activity-time {
    font-size: 0.875rem;
    color: #718096;
}

.activity-description {
    margin: 0;
    color: #4a5568;
    line-height: 1.5;
}

/* Activity type colors */
.activity-item.login .activity-icon {
    background: #e3f2fd;
    color: #1976d2;
}

.activity-item.logout .activity-icon {
    background: #fce4ec;
    color: #c2185b;
}

.activity-item.new_user .activity-icon {
    background: #e8f5e9;
    color: #388e3c;
}

.activity-item.status_change .activity-icon {
    background: #fff3e0;
    color: #f57c00;
}

.activity-item.profile_update .activity-icon {
    background: #f3e5f5;
    color: #7b1fa2;
}

.activity-item.user_deleted .activity-icon {
    background: #ffebee;
    color: #d32f2f;
}

.pagination-container {
    padding: 20px;
    border-top: 1px solid #e2e8f0;
    background: #f8fafc;
}

/* Pagination styling */
.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.pagination .page-item {
    display: inline-block;
}

.pagination .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 12px;
    border-radius: 6px;
    background: white;
    border: 1px solid #e2e8f0;
    color: #4a5568;
    text-decoration: none;
    transition: all 0.2s;
}

.pagination .page-item.active .page-link {
    background: #4299e1;
    color: white;
    border-color: #4299e1;
}

.pagination .page-link:hover {
    background: #edf2f7;
    border-color: #cbd5e0;
}

.pagination .page-item.active .page-link:hover {
    background: #2b6cb0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeFilter = document.getElementById('typeFilter');
    const dateFilter = document.getElementById('dateFilter');
    const activities = document.querySelectorAll('.activity-item');

    function filterActivities() {
        const selectedType = typeFilter.value;
        const selectedDate = dateFilter.value;

        activities.forEach(activity => {
            const typeMatch = !selectedType || activity.dataset.type === selectedType;
            const dateMatch = !selectedDate || activity.dataset.date === selectedDate;
            
            activity.style.display = typeMatch && dateMatch ? 'flex' : 'none';
        });
    }

    typeFilter.addEventListener('change', filterActivities);
    dateFilter.addEventListener('change', filterActivities);

    // Add animation when filtering
    const originalDisplay = {};
    activities.forEach(activity => {
        originalDisplay[activity.dataset.type] = 'flex';
    });

    function animateFiltering() {
        activities.forEach(activity => {
            if (activity.style.display === 'none') {
                activity.style.opacity = '0';
                activity.style.transform = 'translateY(-10px)';
            } else {
                activity.style.opacity = '1';
                activity.style.transform = 'translateY(0)';
            }
        });
    }

    // Add transition styles
    activities.forEach(activity => {
        activity.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    });

    typeFilter.addEventListener('change', animateFiltering);
    dateFilter.addEventListener('change', animateFiltering);
});
</script>
@endsection 