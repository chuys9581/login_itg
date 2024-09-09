<?php
/*
Plugin Name: Feed Instagram Login
Description: Plugin para iniciar sesión y verificar datos en Airtable.
Version: 1.0
Author: Jesus Jimenez
*/

function feed_instagram_login_form() {
    $plugin_url = plugins_url('/assets/instagram.png', __FILE__); 
    $phone_img_url = plugins_url('/assets/img-phones.png', __FILE__); 

    ob_start();
    ?>
    <div class="login-container">
        <div class="login-image-side">
            <img class="img-phone" src="<?php echo esc_url($phone_img_url); ?>" alt="Phone Image">
        </div>
        <div class="login-form-side">
            <form id="login-form" method="post">
                <div class="login-image">
                    <img class="img-login" src="<?php echo esc_url($plugin_url); ?>" alt="Instagram Logo">
                </div>
                <input type="text" id="login_field" name="login_field" placeholder="Phone number, name or email" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button class="btn-login" type="submit">Log in</button>
                <div id="login-message"></div>
            </form>
            <div class="container-register-firts">
                 <p>Don't have an account? <a class="text-register-firts" href="<?php echo esc_url(home_url('/register')); ?>">Sign up</a></p>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('feed_instagram_login', 'feed_instagram_login_form');

// Manejar el inicio de sesión de usuarios
function feed_instagram_handle_login() {
    if (isset($_POST['login_field'], $_POST['password'])) {
        $login_field = sanitize_text_field($_POST['login_field']);
        $password = sanitize_text_field($_POST['password']);
        
        // Consultar datos en Airtable
        $airtable_url = 'https://api.airtable.com/v0/appzmB3zBmwWkhnkn/Usuarios';
        $airtable_key = 'patv59bjnbEGUFZG8.cd0546b6e89b9368307894b52c97ef81268d5253071ed72b4d94d955b441b576'; 
        $response = wp_remote_get($airtable_url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $airtable_key,
                'Content-Type' => 'application/json',
            ),
        ));
        
        if (is_wp_error($response)) {
            wp_send_json_error(array('message' => 'Error al enviar datos a Airtable: ' . $response->get_error_message()));
        }
        
        $response_body = wp_remote_retrieve_body($response);
        $response_data = json_decode($response_body, true);

        $user_found = false;
        $user_email = '';

        foreach ($response_data['records'] as $record) {
            $fields = $record['fields'];
            $telefono = isset($fields['Telefono']) ? $fields['Telefono'] : '';
            $nombre = isset($fields['Nombre']) ? $fields['Nombre'] : '';
            $email = isset($fields['email']) ? $fields['email'] : '';
            $password_from_db = isset($fields['Password']) ? $fields['Password'] : '';

            if (
                ($telefono === $login_field || $nombre === $login_field || $email === $login_field) &&
                $password_from_db === $password
            ) {
                $user_found = true;
                $user_email = $email; 
                break;
            }
        }

        if ($user_found) {
            // Si el usuario no existe en WordPress, crear un nuevo usuario
            if (!email_exists($user_email)) {
                $username = sanitize_user($login_field);
                $user_id = wp_create_user($username, $password, $user_email);
                if (is_wp_error($user_id)) {
                    wp_send_json_error(array('message' => $user_id->get_error_message()));
                }
            } else {
                // Si el usuario ya existe, obtener el ID de usuario
                $user = get_user_by('email', $user_email);
                $user_id = $user->ID;
            }

            // Iniciar sesión en WordPress
            wp_set_auth_cookie($user_id);
            wp_set_current_user($user_id);
            do_action('wp_login', $user->user_login, $user);

            wp_send_json_success(array('redirect_url' => home_url('/perfil')));
        } else {
            wp_send_json_error(array('message' => 'Credenciales incorrectas.'));
        }
    } else {
        wp_send_json_error(array('message' => 'Datos faltantes.'));
    }
}

add_action('wp_ajax_feed_instagram_login', 'feed_instagram_handle_login');
add_action('wp_ajax_nopriv_feed_instagram_login', 'feed_instagram_handle_login');

function feed_instagram_login_enqueue_scripts() {
    wp_enqueue_script('feed-instagram-login-script', plugins_url('/assets/script_login.js', __FILE__), array('jquery'), null, true);
    
    wp_enqueue_style('feed-instagram-login-style', plugins_url('/assets/style_login.css', __FILE__));
    
    wp_localize_script('feed-instagram-login-script', 'feedInstagramLoginAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'feed_instagram_login_enqueue_scripts');
