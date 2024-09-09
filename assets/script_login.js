jQuery(document).ready(function($) {
    $('#login-form').submit(function(event) {
        event.preventDefault();

        var loginField = $('#login_field').val();
        var password = $('#password').val();

        $.ajax({
            url: feedInstagramLoginAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'feed_instagram_login',
                login_field: loginField,
                password: password
            },
            success: function(response) {
                if (response && response.success) {
                    window.location.href = response.data.redirect_url; 
                } else {
                    $('#login-message').text(response.data.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX error:', textStatus, errorThrown);
                $('#login-message').text('Error en la solicitud.');
            }
        });
    });
});