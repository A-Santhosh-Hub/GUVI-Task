$(document).ready(function () {
    const token = localStorage.getItem('session_token');
    const user_id = localStorage.getItem('user_id');
    const username = localStorage.getItem('username');

    if (username) {
        $('#welcomeMessage').text('Welcome, ' + username + '!');
    }

    if (!token) {
        window.location.href = 'login.html';
        return;
    }

    // Load Profile Data
    function loadProfile() {
        $.ajax({
            url: 'php/profile.php',
            type: 'GET',
            headers: {
                'Authorization': token
            },
            data: { user_id: user_id },
            success: function (response) {
                const res = response;
                if (res.status === 'success') {
                    if (res.data) {
                        $('#age').val(res.data.age || '');
                        $('#dob').val(res.data.dob || '');
                        $('#contact').val(res.data.contact || '');
                        $('#address').val(res.data.address || '');
                    }
                } else {
                    if (res.message === 'Session expired') {
                        localStorage.clear();
                        window.location.href = 'login.html';
                    }
                }
            }
        });
    }

    loadProfile();

    // Update Profile Data
    $('#profileForm').on('submit', function (e) {
        e.preventDefault();

        const profileData = {
            user_id: user_id,
            age: $('#age').val(),
            dob: $('#dob').val(),
            contact: $('#contact').val(),
            address: $('#address').val()
        };

        $.ajax({
            url: 'php/profile.php',
            type: 'POST',
            headers: {
                'Authorization': token
            },
            data: profileData,
            success: function (response) {
                const res = response;
                if (res.status === 'success') {
                    $('#profileMessage').html('<div class="alert alert-success">Profile updated successfully!</div>');
                } else {
                    $('#profileMessage').html('<div class="alert alert-danger">' + res.message + '</div>');
                }
            }
        });
    });

    // Logout
    $('#logoutBtn').on('click', function () {
        localStorage.clear();
        window.location.href = 'login.html';
    });
});
