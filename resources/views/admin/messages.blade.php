@extends('admin.dashboard')

@section('content')
<div class="messages-section">
    <div class="section-header">
        <h2>Messages des Utilisateurs</h2>
        <p>{{ $unreadMessages }} message(s) non lu(s)</p>
    </div>

    <div class="messages-container">
        @if($messages->isEmpty())
            <div class="no-messages">
                <i class="fas fa-inbox"></i>
                <p>Aucun message pour le moment</p>
            </div>
        @else
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
                        <button class="btn-reply" onclick="replyToMessage('{{ $message->email }}')">
                            <i class="fas fa-reply"></i> Répondre
                        </button>
                        @if(!$message->read)
                            <button class="btn-mark-read" onclick="markAsRead({{ $message->id }})">
                                <i class="fas fa-check"></i> Marquer comme lu
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<style>
.messages-section {
    padding: 2rem;
}

.section-header {
    margin-bottom: 2rem;
}

.section-header h2 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.messages-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
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
    background: var(--bg-secondary);
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.message-info h3 {
    color: var(--text-primary);
    margin: 0;
    font-size: 1.1rem;
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
    margin-bottom: 1rem;
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
</style>

<script>
function markAsRead(messageId) {
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
            const unreadBadge = messageCard.querySelector('.unread-badge');
            const markReadBtn = messageCard.querySelector('.btn-mark-read');
            if (unreadBadge) unreadBadge.remove();
            if (markReadBtn) markReadBtn.remove();
            
            // Update unread count
            const unreadCount = document.querySelector('.section-header p');
            const currentCount = parseInt(unreadCount.textContent);
            unreadCount.textContent = `${currentCount - 1} message(s) non lu(s)`;
        }
    });
}

function replyToMessage(email) {
    // Open default email client
    window.location.href = `mailto:${email}`;
}
</script>
@endsection 