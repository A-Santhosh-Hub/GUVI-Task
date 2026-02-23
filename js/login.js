$(document).ready(function () {
    console.log("login.js loaded with toggle logic");
    // Redirect if already logged in
    if (localStorage.getItem('session_token')) {
        window.location.href = 'profile.html';
    }

    $('#loginForm').on('submit', function (e) {
        e.preventDefault();

        const email = $('#email').val();
        const password = $('#password').val();

        $.ajax({
            url: 'php/login.php',
            type: 'POST',
            data: {
                email: email,
                password: password
            },
            success: function (response) {
                const res = response;
                if (res.status === 'success') {
                    // Store token and user data in localStorage
                    localStorage.setItem('session_token', res.token);
                    localStorage.setItem('user_id', res.user_id);
                    localStorage.setItem('username', res.username);

                    $('#message').html('<div class="alert alert-success">Login successful! Redirecting...</div>');
                    setTimeout(function () {
                        window.location.href = 'profile.html';
                    }, 1500);
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
