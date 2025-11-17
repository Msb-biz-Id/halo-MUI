<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">🤖 AI Chatbot - Ask Anything</h5>
                </div>
                <div class="card-body" style="height: 500px; overflow-y: auto;" id="chat-messages">
                    <div class="text-center text-muted py-5">
                        <i class="uil-robot font-size-48"></i>
                        <p class="mt-3">Hello! I'm your Islamic knowledge assistant. Ask me anything!</p>
                    </div>
                </div>
                <div class="card-footer">
                    <form id="chat-form">
                        <div class="input-group">
                            <input type="text" class="form-control" id="user-message" placeholder="Type your question..." required>
                            <button type="submit" class="btn btn-primary">
                                <i class="uil-message"></i> Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('chat-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const input = document.getElementById('user-message');
    const message = input.value.trim();
    if (!message) return;
    
    // Add user message
    addMessage('user', message);
    input.value = '';
    
    // Send to backend
    try {
        const response = await fetch('<?= url('/chatbot/message') ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ message })
        });
        const data = await response.json();
        addMessage('bot', data.reply || 'Sorry, I could not process that.');
    } catch (error) {
        addMessage('bot', 'Error connecting to server.');
    }
});

function addMessage(type, text) {
    const container = document.getElementById('chat-messages');
    const div = document.createElement('div');
    div.className = `mb-3 ${type === 'user' ? 'text-end' : ''}`;
    div.innerHTML = `
        <div class="d-inline-block p-3 rounded ${type === 'user' ? 'bg-primary text-white' : 'bg-light'}" style="max-width: 70%;">
            ${text}
        </div>
    `;
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}
</script>
