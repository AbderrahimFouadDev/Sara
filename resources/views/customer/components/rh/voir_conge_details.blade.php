<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="main-container">
    <div class="header">
        <h2 class="title">
            <i class="fas fa-calendar-alt icon"></i>Détails du Congé
        </h2>
        <div class="header-buttons">
            <button class="btn btn-secondary" onclick="document.dispatchEvent(new CustomEvent('loadView', { detail: { view: 'rh/conges' } }))">
                <i class="fas fa-arrow-left"></i>Retour
            </button>
        </div>
    </div>

    <div class="detail-card">
        <div class="employee-section">
            <div class="employee-header">
                <div class="employee-info">
                    <img src="{{ $conge->salarie->photo ? asset('storage/' . $conge->salarie->photo) : 'https://via.placeholder.com/64' }}" alt="Photo employé" class="employee-photo">
                    <div class="employee-details">
                        <h3>{{ $conge->salarie->nom_complet }}</h3>
                        <p class="employee-id">ID: {{ $conge->salarie->matricule }}</p>
                        <p class="department">{{ $conge->salarie->departement }}</p>
                    </div>
                </div>
                <div class="status-section">
                    <span class="status-badge {{ $conge->status }}">
                        @switch($conge->status)
                            @case('approved')
                                Approuvé
                                @break
                            @case('rejected')
                                Refusé
                                @break
                            @default
                                En attente
                        @endswitch
                    </span>
                </div>
            </div>
        </div>

        <div class="leave-details">
            <div class="detail-row">
                <div class="detail-item">
                    <label>Type de Congé</label>
                    <span class="leave-type {{ $conge->type }}">
                        @switch($conge->type)
                            @case('paid')
                                Congé payé
                                @break
                            @case('sick')
                                Congé maladie
                                @break
                            @case('unpaid')
                                Congé sans solde
                                @break
                            @default
                                Autre
                        @endswitch
                    </span>
                </div>
                <div class="detail-item">
                    <label>Durée</label>
                    <span>{{ $conge->duree }} jours</span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-item">
                    <label>Date de Début</label>
                    <span>{{ \Carbon\Carbon::parse($conge->date_debut)->format('d/m/Y') }}</span>
                </div>
                <div class="detail-item">
                    <label>Date de Fin</label>
                    <span>{{ \Carbon\Carbon::parse($conge->date_fin)->format('d/m/Y') }}</span>
                </div>
            </div>

            @if($conge->motif)
            <div class="detail-row">
                <div class="detail-item full-width">
                    <label>Motif</label>
                    <p class="motif">{{ $conge->motif }}</p>
                </div>
            </div>
            @endif

            @if($conge->commentaire)
            <div class="detail-row">
                <div class="detail-item full-width">
                    <label>Commentaire</label>
                    <p class="commentaire">{{ $conge->commentaire }}</p>
                </div>
            </div>
            @endif

            @if($conge->document_justificatif)
            <div class="detail-row">
                <div class="detail-item full-width">
                    <label>Document Justificatif</label>
                    <a href="{{ asset('storage/' . $conge->document_justificatif) }}" target="_blank" class="document-link">
                        <i class="fas fa-file-pdf"></i>Voir le document
                    </a>
                </div>
            </div>
            @endif

            @if($conge->status === 'pending')
            <div class="action-buttons">
                <form action="{{ route('conges.update-status', $conge->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i>Approuver
                    </button>
                </form>

                <form action="{{ route('conges.update-status', $conge->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i>Refuser
                    </button>
                </form>
            </div>
            @endif

            <!-- Add CSRF Token -->
            <meta name="csrf-token" content="{{ csrf_token() }}">

            @if($conge->approved_by)
            <div class="approval-info">
                <label>Traité par:</label>
                <span>{{ $conge->approvedByUser->name }} le {{ \Carbon\Carbon::parse($conge->approved_at)->format('d/m/Y H:i') }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.main-container {
    padding: 24px;
    max-width: 1200px;
    margin: 0 auto;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.title {
    font-size: 24px;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 12px;
}

.icon {
    color: #3b82f6;
}

.detail-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 24px;
}

.employee-section {
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 24px;
    margin-bottom: 24px;
}

.employee-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.employee-info {
    display: flex;
    gap: 16px;
    align-items: center;
}

.employee-photo {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    object-fit: cover;
}

.employee-details h3 {
    font-size: 18px;
    color: #1f2937;
    margin: 0 0 4px 0;
}

.employee-id {
    color: #6b7280;
    font-size: 14px;
    margin: 0 0 4px 0;
}

.department {
    color: #3b82f6;
    font-size: 14px;
    margin: 0;
}

.leave-details {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.detail-row {
    display: flex;
    gap: 24px;
}

.detail-item {
    flex: 1;
}

.detail-item.full-width {
    flex: 0 0 100%;
}

.detail-item label {
    display: block;
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 8px;
}

.detail-item span {
    font-size: 16px;
    color: #1f2937;
}

.leave-type, .status-badge {
    display: inline-flex;
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 14px;
    font-weight: 500;
}

.leave-type.paid {
    background-color: #f0fdf4;
    color: #16a34a;
}

.leave-type.sick {
    background-color: #fff7ed;
    color: #ea580c;
}

.leave-type.unpaid {
    background-color: #f3f4f6;
    color: #6b7280;
}

.status-badge.approved {
    background-color: #f0fdf4;
    color: #16a34a;
}

.status-badge.pending {
    background-color: #fff7ed;
    color: #ea580c;
}

.status-badge.rejected {
    background-color: #fef2f2;
    color: #dc2626;
}

.motif, .commentaire {
    background-color: #f9fafb;
    padding: 12px;
    border-radius: 6px;
    margin: 0;
    color: #374151;
}

.document-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #3b82f6;
    text-decoration: none;
    padding: 8px 12px;
    background-color: #eff6ff;
    border-radius: 6px;
    transition: all 0.2s;
}

.document-link:hover {
    background-color: #dbeafe;
}

.action-buttons {
    display: flex;
    gap: 12px;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid #e5e7eb;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
}

.btn i {
    font-size: 16px;
}

.btn-success {
    background-color: #16a34a;
    color: white;
}

.btn-success:hover {
    background-color: #15803d;
}

.btn-danger {
    background-color: #dc2626;
    color: white;
}

.btn-danger:hover {
    background-color: #b91c1c;
}

.btn-secondary {
    background-color: #4b5563;
    color: white;
}

.btn-secondary:hover {
    background-color: #374151;
}

.approval-info {
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid #e5e7eb;
    font-size: 14px;
    color: #6b7280;
}

.approval-info label {
    font-weight: 500;
    margin-right: 8px;
}

@media (max-width: 768px) {
    .employee-header {
        flex-direction: column;
        gap: 16px;
    }

    .detail-row {
        flex-direction: column;
        gap: 16px;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation dialogs to the forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const status = this.querySelector('input[name="status"]').value;
            const message = status === 'approved' ? 
                'Êtes-vous sûr de vouloir approuver cette demande de congé ?' : 
                'Êtes-vous sûr de vouloir refuser cette demande de congé ?';
            
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush