document.addEventListener("DOMContentLoaded", function() {
    // Функция для выполнения AJAX-запроса
    function sendAjaxRequest(fieldName, fieldValue, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "auth.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                callback(response, fieldName);
            }
        };
        xhr.send(fieldName + "=" + encodeURIComponent(fieldValue) + "&validate=true");
    }

    // Валидация полей при изменении
    document.getElementById('email').addEventListener('input', function() {
        var email = this.value;
        sendAjaxRequest('email', email, function(response, field) {
            if (response.errors && response.errors.email) {
                document.getElementById('email-error').textContent = response.errors.email;
            } else {
                document.getElementById('email-error').textContent = '';
            }
        });
    });

    document.getElementById('password').addEventListener('input', function() {
        var password = this.value;
        sendAjaxRequest('first-password', password, function(response, field) {
            if (response.errors && response.errors.password) {
                document.getElementById('password-error').textContent = response.errors.password;
            } else {
                document.getElementById('password-error').textContent = '';
            }
        });
    });

    // Обработка отправки формы
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Предотвращаем стандартное отправление формы

        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "auth.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    window.location.href = '../user-page.php'; // Переход на страницу после успешного входа
                } else {
                    if (response.errors.email) {
                        document.getElementById('email-error').textContent = response.errors.email;
                    }
                    if (response.errors.password) {
                        document.getElementById('password-error').textContent = response.errors.password;
                    }
                }
            }
        };
        xhr.send(formData);
    });
});
