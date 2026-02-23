$(document).ready(function () {
    console.log("register.js loaded with toggle logic");
    $('#registerForm').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        const username = $('#username').val();
        const email = $('#email').val();
        const password = $('#password').val();
        const confirmPassword = $('#confirmPassword').val();

        if (password !== confirmPassword) {
            $('#message').html('<div class="alert alert-danger">Passwords do not match!</div>');
            return;
        }

        $.ajax({
            url: 'php/register.php',
            type: 'POST',
            data: {
                username: username,
                email: email,
                password: password
            },
            success: function (response) {
                const res = response;
                if (res.status === 'success') {
                    $('#message').html('<div class="alert alert-success">' + res.message + ' Redirecting to login...</div>');
                    setTimeout(function () {
                        window.location.href = 'login.html';
                    }, 2000);
                } else {
                    $('#message').html('<div class="alert alert-danger">' + res.message + '</div>');
                }
            },
            error: function () {
                $('#message').html('<div class="alert alert-danger">An error occurred while processing your request.</div>');
            }
        });
    });

    // Password Toggle Logic
    $('.password-toggle').on('click', function () {
        const input = $(this).siblings('input');
        const icon = $(this);

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash active');
        } else {
            input.attr('type', 'password');
            icon.removeClass('bi-eye-slash active').addClass('bi-eye');
        }
    });
});
