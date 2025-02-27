document.addEventListener("DOMContentLoaded", function () {
    // Функция для отправки данных на сервер через AJAX
    function sendAjaxRequest(fieldName, fieldValue, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "register-validation.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                callback(response, fieldName);
            }
        };

        xhr.send(fieldName + "=" + encodeURIComponent(fieldValue) + "&validate=true");
    }

    // Обработчик для поля ФИО
    document.getElementById('name').addEventListener('input', function () {
        var name = this.value;
        sendAjaxRequest('name', name, function (response,field) {
            if (response.errors && response.errors.name) {
                document.getElementById('name-error').textContent = response.errors.name;
            } else {
                document.getElementById('name-error').textContent = '';
            }
        });
    });

    // Обработчик для поля email
    document.getElementById('email').addEventListener('input', function () {
        var email = this.value;
        sendAjaxRequest('email', email, function (response,field) {
            if (response.errors && response.errors.email) {
                document.getElementById("email-error").textContent = response.errors.email;
            } else {
                document.getElementById("email-error").textContent = '';
            }
        });
    });

    // Обработчик для первого пароля
    document.getElementById('first-password').addEventListener('input', function () {
        var password = this.value;
        sendAjaxRequest('first-password', password, function (response,field) {
            if (response.errors && response.errors.firstPassword) {
                document.getElementById('first-password-error').textContent = response.errors.firstPassword;
            } else {
                document.getElementById('first-password-error').textContent = '';
            }
        });
    });

    // Обработчик для второго пароля
    document.getElementById('second-password').addEventListener('input', function () {
        var password = this.value;
        sendAjaxRequest('second-password', password, function (response,field) {
            if (response.errors && response.errors.secondPassword) {
                document.getElementById('second-password-error').textContent = response.errors.secondPassword;
            } else {
                document.getElementById('second-password-error').textContent = '';
            }
        });
    });

    // Обработчик для номера телефона
    document.getElementById('phone').addEventListener('input', function () {
        var phone = this.value;
        sendAjaxRequest('phone', phone, function (response,field) {
            if (response.errors && response.errors.phone) {
                document.getElementById('phone-error').textContent = response.errors.phone;
            } else {
                document.getElementById('phone-error').textContent = '';
            }
        });
    });

    // Обработчик отправки формы
    document.getElementById('regform').addEventListener('submit', function (event) {
        event.preventDefault();

        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "register-validation.php", true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    window.location.href = '../user-page.php'; // Переход на страницу после успешной регистрации
                } else {
                    window.location.href = 'body.php'; 
                    // Обработка ошибок, если регистрация не успешна
                    if (response.errors) {
                        handleFormErrors(response.errors);
                    }
                }
            }
        };

        xhr.send(formData);
    });

    // Функция обработки ошибок формы
    function handleFormErrors(errors) {
        if (errors.name) document.getElementById('name-error').textContent = errors.name;
        if (errors.email) document.getElementById('email-error').textContent = errors.email;
        if (errors.firstPassword) document.getElementById('first-password-error').textContent = errors.firstPassword;
        if (errors.secondPassword) document.getElementById('second-password-error').textContent = errors.secondPassword;
        if (errors.phone) document.getElementById('phone-error').textContent = errors.phone;
    }
});