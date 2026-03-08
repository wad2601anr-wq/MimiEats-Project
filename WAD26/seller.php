<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>MimiEats | Seller Dashboard</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&family=Righteous&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-orange: #fffaf0;
            --main-orange: #ffb74d;
            --mobile-width: 430px;
        }

        html { background-color: #333; display: flex; justify-content: center; margin: 0; }
        body {
            font-family: 'Poppins', sans-serif; margin: 0; background: var(--bg-orange);
            width: 100%; max-width: var(--mobile-width); height: 100vh;
            display: flex; flex-direction: column; overflow: hidden; position: relative;
            box-shadow: 0 0 50px rgba(0,0,0,0.5);
        }

        header {
            background: linear-gradient(135deg, #fff9ae 0%, #ffcc80 100%); 
            padding: 25px 0; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05); z-index: 10;
            position: relative;
        }

        /* Clear Chat Button Style */
        .btn-clear {
            position: absolute; top: 15px; left: 15px; 
            background: rgba(255, 255, 255, 0.4); border: 1px solid rgba(0,0,0,0.1);
            color: #5d4037; padding: 4px 10px; border-radius: 8px;
            font-size: 0.65rem; font-weight: 800; cursor: pointer; transition: 0.2s;
        }
        .btn-clear:hover { background: #ff5252; color: white; border-color: transparent; }

        .logo-container h1 {
            margin: 0; font-family: 'Righteous', cursive; font-size: 2.5rem;
            background: linear-gradient(135deg, #f57c00 0%, #f48fb1 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .chat-section { flex: 1; display: flex; flex-direction: column; position: relative; overflow: hidden; }
        #chat-container { flex: 1; overflow-y: auto; padding: 15px; display: flex; flex-direction: column; gap: 12px; padding-bottom: 90px; scroll-behavior: smooth; }

        .bubble-wrapper { display: flex; align-items: flex-end; gap: 8px; animation: popIn 0.3s; width: 100%; }
        .msg-buyer { justify-content: flex-start; }
        .msg-buyer .msg-content { background: white; padding: 10px 14px; border-radius: 15px 15px 15px 4px; font-size: 0.85rem; max-width: 75%; border: 1px solid #ffe0b2; }
        .msg-buyer .avatar { width: 30px; height: 30px; border-radius: 50%; background: #fce4ec; display: flex; align-items: center; justify-content: center; font-size: 1rem; border: 1px solid #f8bbd0; }

        .msg-seller { justify-content: flex-end; }
        .msg-seller .msg-content { background: var(--main-orange); color: white; padding: 10px 14px; border-radius: 15px 15px 4px 15px; font-size: 0.85rem; max-width: 75%; }
        .msg-seller .avatar { width: 30px; height: 30px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; font-size: 1rem; border: 1px solid #ffcc80; order: 2; }
        .msg-seller .msg-content { order: 1; }

        .input-container { position: absolute; bottom: 15px; left: 5%; width: 90%; background: white; padding: 4px; border-radius: 50px; display: flex; box-shadow: 0 5px 20px rgba(0,0,0,0.1); box-sizing: border-box; }
        .input-container input { flex: 1; border: none; padding: 10px 15px; outline: none; font-size: 0.85rem; background: transparent; }
        .btn-send { background: var(--main-orange); color: white; border: none; padding: 0 20px; border-radius: 50px; font-weight: 800; cursor: pointer; }

        @keyframes popIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

<header>
    <button class="btn-clear" onclick="clearChat()">Clear Chat</button>
    <div class="logo-container"><h1>Seller Panel</h1></div>
</header>

<div class="chat-section">
    <div id="chat-container"></div>
    <div class="input-container">
        <input type="text" id="reply" placeholder="Reply to buyer..." onkeypress="if(event.key==='Enter')sendReply()">
        <button class="btn-send" onclick="sendReply()">Send</button>
    </div>
</div>

<script>
    let lastMessageCount = 0;

    async function loadChats() {
        try {
            const res = await fetch('api.php');
            const data = await res.json();
            const container = document.getElementById('chat-container');
            
            if (data.length === 0) {
                container.innerHTML = "";
                lastMessageCount = 0;
                return;
            }

            if (data.length > lastMessageCount) {
                const isAtBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 100;
                const newMessages = data.slice(lastMessageCount);
                
                newMessages.forEach(m => {
                    const isSeller = m.sender === 'Seller';
                    const div = document.createElement('div');
                    div.className = `bubble-wrapper ${isSeller ? 'msg-seller' : 'msg-buyer'}`;
                    div.innerHTML = `
                        <div class="avatar">${isSeller ? '🏪' : '👤'}</div>
                        <div class="msg-content">
                            <small style="display:block; font-size: 0.65rem; opacity: 0.8; margin-bottom: 3px;">${m.sender}</small>
                            ${m.message.replace(/\n/g, '<br>')}
                        </div>
                    `;
                    container.appendChild(div);
                });

                lastMessageCount = data.length;
                if (isAtBottom) container.scrollTop = container.scrollHeight;
            }
        } catch (e) { console.error(e); }
    }

    async function sendReply() {
        const input = document.getElementById('reply');
        const text = input.value.trim();
        if(!text) return;
        const formData = new FormData();
        formData.append('sender', 'Seller');
        formData.append('message', text);
        try {
            await fetch('api.php', { method: 'POST', body: formData });
            input.value = '';
            loadChats();
        } catch (e) {}
    }

    async function clearChat() {
        if(confirm("Delete all chat history?")) {
            const formData = new FormData();
            formData.append('action', 'clear'); 
            try {
                await fetch('api.php', { method: 'POST', body: formData });
                document.getElementById('chat-container').innerHTML = "";
                lastMessageCount = 0;
            } catch (e) { alert("Failed to clear!"); }
        }
    }

    setInterval(loadChats, 2000);
    loadChats();
</script>
</body>
</html>