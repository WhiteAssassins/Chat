const shell = document.querySelector(".chat-shell");

if (shell && window.chatBootstrap) {
    const messagesElement = document.getElementById("chat-messages");
    const usersElement = document.getElementById("online-users");
    const onlineCountElement = document.getElementById("online-count");
    const form = document.getElementById("message-form");
    const input = document.getElementById("message-input");
    const errorElement = document.getElementById("form-error");
    const typingElement = document.getElementById("typing-indicator");
    const counterElement = document.getElementById("char-counter");

    const currentUserId = Number(shell.dataset.currentUserId);
    const currentRoomId = Number(shell.dataset.currentRoomId || window.chatBootstrap.currentRoomId || 0);
    const pollUrl = shell.dataset.pollUrl;
    const sendUrl = shell.dataset.sendUrl;
    const typingUrl = shell.dataset.typingUrl;
    const csrfHeaderName = shell.dataset.csrfHeader || "X-CSRF-TOKEN";
    const csrfCookieName = shell.dataset.csrfCookie || "csrf_cookie_name";

    let lastMessageId = 0;
    let isSending = false;
    let lastTypingPingAt = 0;
    let csrfHash = window.chatBootstrap.csrfHash || "";

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

    const getCookie = (name) => {
        const encodedName = `${encodeURIComponent(name)}=`;
        const parts = document.cookie.split("; ");

        for (const part of parts) {
            if (part.startsWith(encodedName)) {
                return decodeURIComponent(part.slice(encodedName.length));
            }
        }

        return "";
    };

    const getCsrfToken = () => getCookie(csrfCookieName) || csrfHash;

    const updateCsrfHash = (payload) => {
        if (payload && typeof payload.csrfHash === "string" && payload.csrfHash) {
            csrfHash = payload.csrfHash;
        }
    };

    const scrollToBottom = () => {
        messagesElement.scrollTop = messagesElement.scrollHeight;
    };

    const autosizeInput = () => {
        input.style.height = "auto";
        input.style.height = `${Math.min(input.scrollHeight, 180)}px`;
    };

    const updateCounter = () => {
        counterElement.textContent = `${input.value.length}/1000`;
    };

    const syncEmptyState = () => {
        const emptyState = messagesElement.querySelector(".empty-state");
        const messageCount = messagesElement.querySelectorAll(".message").length;

        if (messageCount > 0) {
            emptyState?.remove();
            return;
        }

        if (emptyState) {
            return;
        }

        const card = document.createElement("article");
        const title = document.createElement("strong");
        const copy = document.createElement("p");

        card.className = "empty-state";
        title.textContent = "Todavia no hay mensajes en esta sala.";
        copy.textContent = "Rompe el hielo con una idea, una prueba o una pregunta para el equipo.";

        card.appendChild(title);
        card.appendChild(copy);
        messagesElement.appendChild(card);
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

    const renderTyping = (users) => {
        if (!users.length) {
            typingElement.hidden = true;
            typingElement.textContent = "";
            return;
        }

        const names = users.map((user) => user.username);
        let copy = "";

        if (names.length === 1) {
            copy = `${names[0]} esta escribiendo...`;
        } else if (names.length === 2) {
            copy = `${names[0]} y ${names[1]} estan escribiendo...`;
        } else {
            copy = `${names[0]}, ${names[1]} y ${names.length - 2} personas mas estan escribiendo...`;
        }

        typingElement.hidden = false;
        typingElement.textContent = copy;
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
            syncEmptyState();
            return;
        }

        messagesElement.querySelector(".empty-state")?.remove();

        messages.forEach((message) => {
            messagesElement.appendChild(buildMessageNode(message));
            lastMessageId = Math.max(lastMessageId, Number(message.id));
        });

        scrollToBottom();
    };

    const boot = () => {
        renderUsers(window.chatBootstrap.onlineUsers || []);
        appendMessages(window.chatBootstrap.messages || []);
        renderTyping(window.chatBootstrap.typingUsers || []);
        updateCounter();
        autosizeInput();
        syncEmptyState();
    };

    const poll = async () => {
        try {
            const response = await fetch(`${pollUrl}?after=${lastMessageId}&room_id=${currentRoomId}`, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            if (response.redirected) {
                window.location.href = response.url;
                return;
            }

            if (!response.ok) {
                throw new Error("No se pudo actualizar el chat.");
            }

            const payload = await response.json();
            updateCsrfHash(payload);
            appendMessages(payload.messages || []);
            renderUsers(payload.onlineUsers || []);
            renderTyping(payload.typingUsers || []);
            showError("");
        } catch (error) {
            showError(error.message);
        }
    };

    const pingTyping = async () => {
        const now = Date.now();

        if (!input.value.trim() || now - lastTypingPingAt < 1500) {
            return;
        }

        lastTypingPingAt = now;

        try {
            const response = await fetch(typingUrl, {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    [csrfHeaderName]: getCsrfToken(),
                },
                body: new URLSearchParams({ room_id: String(currentRoomId) }),
            });

            if (!response.ok) {
                return;
            }

            const payload = await response.json();
            updateCsrfHash(payload);
            renderTyping(payload.typingUsers || []);
        } catch (error) {
            // La escritura en vivo es opcional; si falla no interrumpimos el chat.
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
                    [csrfHeaderName]: getCsrfToken(),
                },
                body: new URLSearchParams({
                    message: rawMessage,
                    room_id: String(currentRoomId),
                }),
            });

            if (response.redirected) {
                window.location.href = response.url;
                return;
            }

            const payload = await response.json();
            updateCsrfHash(payload);

            if (!response.ok) {
                throw new Error(payload.error || "No se pudo enviar el mensaje.");
            }

            appendMessages([payload.message]);
            renderUsers(payload.onlineUsers || []);
            renderTyping(payload.typingUsers || []);
            form.reset();
            updateCounter();
            autosizeInput();
            input.focus();
        } catch (error) {
            showError(error.message);
        } finally {
            isSending = false;
        }
    });

    input.addEventListener("input", () => {
        updateCounter();
        autosizeInput();
        pingTyping();
    });

    input.addEventListener("keydown", (event) => {
        if (event.key === "Enter" && !event.shiftKey) {
            event.preventDefault();
            form.requestSubmit();
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
