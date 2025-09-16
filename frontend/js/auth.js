// js/auth.js
function pingDB(outputId) {
    fetch('../backend/api/ping.php')
        .then(r => r.json())
        .then(data => {
            document.getElementById(outputId).textContent = data.message;
        })
        .catch(e => document.getElementById(outputId).textContent = 'Ошибка: '+e);
}

// Общая функция регистрации
function registerUser() {
    const el = document.getElementById('regResult');
    const login = document.getElementById('regLogin').value.trim();
    const pass = document.getElementById('regPass').value.trim();
    const pass2 = document.getElementById('regPass2').value.trim();
    const prompt = document.getElementById('regPrompt').value;
    const answer = document.getElementById('regAnswer').value.trim();
    const email = document.getElementById('regEmail').value.trim();

    // Проверки
    if (login.length === 0 || login.length > 10) {
        el.textContent = "Логин обязателен и не более 10 символов";
        el.className = "output error";
        return;
    }
    if (pass.length < 6) {
        el.textContent = "Пароль должен быть минимум 6 символов";
        el.className = "output error";
        return;
    }
    if (pass !== pass2) {
        el.textContent = "Пароли не совпадают";
        el.className = "output error";
        return;
    }
    if (!prompt) {
        el.textContent = "Выберите вопрос для восстановления пароля";
        el.className = "output error";
        return;
    }
    if (answer.length === 0) {
        el.textContent = "Ответ на вопрос обязателен";
        el.className = "output error";
        return;
    }
    if (!email) {
        el.textContent = "Email обязателен";
        el.className = "output error";
        return;
    }

    // Отправка на сервер
    const formData = new FormData();
    formData.append('login', login);
    formData.append('passwd', pass);
    formData.append('Prompt', prompt);
    formData.append('answer', answer);
    formData.append('email', email);

    fetch('../server/api/register.php', {
        method: 'POST',
        body: formData
    })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'ok') {
                el.textContent = "Регистрация успешна!";
                el.className = "output";
            } else {
                el.textContent = data.message;
                el.className = "output error";
            }
        })
        .catch(e => {
            el.textContent = "Ошибка: " + e;
            el.className = "output error";
        });
}


function loginUser() {
    const el = document.getElementById('loginResult');
    const login = document.getElementById('loginName').value.trim();
    const pass = document.getElementById('loginPass').value.trim();

    if (!login) {
        el.textContent = "Введите логин";
        el.className = "output error";
        return;
    }
    if (!pass) {
        el.textContent = "Введите пароль";
        el.className = "output error";
        return;
    }

    const formData = new FormData();
    formData.append('login', login);
    formData.append('passwd', pass);

    fetch('../server/api/login.php', {
        method: 'POST',
        body: formData
    })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'ok') {
                el.textContent = `Вход успешен: ${data.user.name}, email: ${data.user.email}`;
                el.className = "output";
            } else {
                el.textContent = data.message;
                el.className = "output error";
            }
        })
        .catch(e => {
            el.textContent = "Ошибка: " + e;
            el.className = "output error";
        });
}
