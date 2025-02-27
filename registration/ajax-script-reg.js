document.addEventListener("DOMContentLoaded", function () {
    function sendAjaxRequest(formData, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "register-validation.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                callback(response);
            }
        };
        xhr.send(formData);
    }

    // Функция обработки ошибок формы
    function handleFormErrors(errors) {
        document.getElementById('name-error').textContent = errors.name || '';
        document.getElementById('email-error').textContent = errors.email || '';
        document.getElementById('first-password-error').textContent = errors.firstPassword || '';
        document.getElementById('second-password-error').textContent = errors.secondPassword || '';
        document.getElementById('phone-error').textContent = errors.phone || '';
    }

    // Обработчик для всех полей
    document.querySelectorAll('#regForm input').forEach(function (input) {
        input.addEventListener('input', function () {
            var formData = new FormData(document.getElementById('regForm'));
            formData.append("validate", "true");

            sendAjaxRequest(new URLSearchParams(formData).toString(), function (response) {
                handleFormErrors(response.errors || {});
            });
        });
    });

    // Обработчик отправки формы
    document.getElementById('regForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "register-validation.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    window.location.href = '../user-page.php'; // Успешная регистрация
                } else {
                    handleFormErrors(response.errors);
                }
            }
        };

        xhr.send(formData);
    });
});
