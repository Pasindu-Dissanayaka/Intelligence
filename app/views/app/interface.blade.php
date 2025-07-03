@extends('layouts.default')

@section('title')
Chat Interface
@endsection

@section('content')
<div class='container-fluid'>
    <div class='card card-body vh-100'>
        <div class='card blur shadow-blur vh-100'>
            <div id='chatMessages' class='card-body overflow-hidden max-height-vh-100'>
                <div class="row mt-4">
                    <div class="col-md-12 text-center">
                        <span class="badge text-dark">@php echo tick()->format('ddd, hh:mma'); @endphp</span>
                    </div>
                </div>

            </div>
            <div class='card-footer d-block'>
                <form id='chatForm' class='align-items-center' action='/dashboard/ask-ai' method='POST'>
                    <div class='input-group input-group-outline d-flex'>
                        <input type='text' id='userInput' name='userInput' placeholder='Type your message' class='form-control form-control-lg'>
                        <button type='submit' class='btn bg-gradient-dark mb-0'>
                            <i class='material-symbols-rounded'>send</i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
const userMessages = [];
const botMessages = [];
document.getElementById('chatForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const input = document.getElementById('userInput');
    const message = input.value.trim();
    if (!message) return;

    input.value = '';

    try {
        const response = await fetch('/dashboard/ask-ai', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message })
        });

        const data = await response.json();

        if (!data.input || !data.reply) {
            console.error('Unexpected response:', data);
            alert('Invalid response from AI.');
            return;
        }

        userMessages.push({ text: data.input, time: data.message_time });
        botMessages.push({ text: data.reply, time: data.reply_time });

        if (userMessages.length > 3) userMessages.shift();
        if (botMessages.length > 3) botMessages.shift();

        const allMessages = [
            ...userMessages.map(m => ({ ...m, from: 'user' })),
            ...botMessages.map(m => ({ ...m, from: 'bot' }))
        ].sort((a, b) => new Date(a.time) - new Date(b.time));

        const chatContainer = document.getElementById('chatMessages');
        chatContainer.innerHTML = '';

        const formatTime = iso => new Date(iso).toLocaleTimeString([], {
            hour: 'numeric', minute: '2-digit'
        });

        allMessages.forEach(msg => {
            const html = msg.from === 'user' ? `
                <div class="row justify-content-end text-right mb-4">
                    <div class="col-auto">
                        <div class="card bg-gradient-dark">
                            <div class="card-body py-2 px-3 text-white">
                                <p class="mb-1">${msg.text}</p>
                                <div class="d-flex align-items-center justify-content-end text-sm opacity-6">
                                    <i class="fa-solid fa-check text-sm me-1"></i>
                                    <small>${formatTime(msg.time)}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ` : `
                <div class="row justify-content-start mb-4">
                    <div class="col-auto">
                        <div class="card">
                            <div class="card-body py-2 px-3">
                                <p class="mb-1">${msg.text}</p>
                                <div class="d-flex align-items-center text-sm opacity-6">
                                    <i class="fa-solid fa-check text-sm me-1"></i>
                                    <small>${formatTime(msg.time)}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            chatContainer.insertAdjacentHTML('beforeend', html);
        });

        window.scrollTo(0, document.body.scrollHeight);

    } catch (err) {
        console.error('Request failed:', err);
        alert('Could not contact the AI. See console for details.');
    }
});
</script>

@endsection