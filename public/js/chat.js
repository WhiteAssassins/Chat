const shell = document.querySelector(".chat-shell");

if (shell && window.chatBootstrap) {
    const messagesElement = document.getElementById("chat-messages");
    const usersElement = document.getElementById("online-users");
    const onlineCountElement = document.getElementById("online-count");
    const form = document.getElementById("message-form");
    const input = document.getElementById("message-input");
    const errorElement = document.getElementById("form-error");

    const currentUserId = Number(shell.dataset.currentUserId);
    const pollUrl = shell.dataset.pollUrl;
    const sendUrl = shell.dataset.sendUrl;

    let lastMessageId = 0;
    let isSending = false;

    const initials = (name) => name.trim().slice(0, 1).toUpperCase();

    const formatTime = (value) => {
        if (!value) {
            return "";
        }

        const date = new Date(value.replace(" ", "T"));

        return new Intl.DateTimeFormat("es-ES", {
            hour: "2-digit",
            minute: "2-digit",
        }).format(date);
    };

    const showError = (message) => {
        if (!message) {
            errorElement.hidden = true;
            errorElement.textContent = "";
            return;
        }

        errorElement.hidden = false;
        errorElement.textContent = message;
    };

    const scrollToBottom = () => {
        messagesElement.scrollTop = messagesElement.scrollHeight;
    };

    const renderUsers = (users) => {
        usersElement.innerHTML = "";
        onlineCountElement.textContent = String(users.length);

        users.forEach((user) => {
            const item = document.createElement("li");
            const avatar = document.createElement("span");
            const meta = document.createElement("div");
            const name = document.createElement("strong");
            const status = document.createElement("small");

            avatar.className = "user-avatar";
            avatar.textContent = initials(user.username);
            name.textContent = user.username;
            status.textContent = Number(user.id) === currentUserId ? "Tu sesion actual" : "Activo recientemente";

            meta.appendChild(name);
            meta.appendChild(status);
            item.appendChild(avatar);
            item.appendChild(meta);
            usersElement.appendChild(item);
        });
    };

    const buildMessageNode = (message) => {
        const article = document.createElement("article");
        const meta = document.createElement("div");
        const author = document.createElement("span");
        const time = document.createElement("span");
        const body = document.createElement("p");

        article.className = "message";
        if (Number(message.user_id) === currentUserId) {
            article.classList.add("is-own");
        }

        meta.className = "message-meta";
        author.className = "message-author";
        time.className = "message-time";
        body.className = "message-body";

        author.textContent = message.username;
        time.textContent = formatTime(message.created_at);
        body.textContent = message.body;

        meta.appendChild(author);
        meta.appendChild(time);
        article.appendChild(meta);
        article.appendChild(body);

        return article;
    };

    const appendMessages = (messages) => {
        if (!messages.length) {
            return;
        }

        messages.forEach((message) => {
            messagesElement.appendChild(buildMessageNode(message));
            lastMessageId = Math.max(lastMessageId, Number(message.id));
        });

        scrollToBottom();
    };

    const boot = () => {
        renderUsers(window.chatBootstrap.onlineUsers || []);
        appendMessages(window.chatBootstrap.messages || []);
    };

    const poll = async () => {
        try {
            const response = await fetch(`${pollUrl}?after=${lastMessageId}`, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            if (!response.ok) {
                throw new Error("No se pudo actualizar el chat.");
            }

            const payload = await response.json();
            appendMessages(payload.messages || []);
            renderUsers(payload.onlineUsers || []);
        } catch (error) {
            showError(error.message);
        }
    };

    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        if (isSending) {
            return;
        }

        const rawMessage = String(input.value || "").trim();

        if (!rawMessage) {
            showError("Escribe un mensaje antes de enviar.");
            return;
        }

        isSending = true;
        showError("");

        try {
            const response = await fetch(sendUrl, {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: new URLSearchParams({ message: rawMessage }),
            });

            const payload = await response.json();

            if (!response.ok) {
                throw new Error(payload.error || "No se pudo enviar el mensaje.");
            }

            appendMessages([payload.message]);
            renderUsers(payload.onlineUsers || []);
            form.reset();
            input.focus();
        } catch (error) {
            showError(error.message);
        } finally {
            isSending = false;
        }
    });

    document.addEventListener("visibilitychange", () => {
        if (!document.hidden) {
            poll();
        }
    });

    boot();
    window.setInterval(poll, 2000);
}
