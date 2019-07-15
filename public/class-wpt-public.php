<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://microtriangle.com
 * @since      1.0.0
 *
 * @package    Wpt
 * @subpackage Wpt/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wpt
 * @subpackage Wpt/public
 * @author     Microtriangle <support@microtriangle.com>
 */
class Wpt_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpt_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpt_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpt-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpt_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpt_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpt-public.js', array( 'jquery' ), $this->version, false );

	}

	public function init() {
		$access_token = $_GET['temp-access-token'] ? esc_attr( $_GET['temp-access-token'] ) : '';

		if ( ! empty( $access_token ) ) {

		    

		}

	}

	public function lostpassword_post( $errors ) {
		if ( $errors->has_errors() ) {
			return;
		}

		if ( strpos( $_POST['user_login'], '@' ) ) {
			$user_data = get_user_by( 'email', trim( wp_unslash( $_POST['user_login'] ) ) );
			if ( empty( $user_data ) ) return;
		} else {
			$login     = trim( $_POST['user_login'] );
			$user_data = get_user_by( 'login', $login );
		}

		if ( ! $user_data ) {
			return;
		}
		// Get user meta
		$disabled = get_user_meta( $user_data->ID, 'temp_user', true );

		// Is the use logging in disabled?
		if ( $disabled == '1' ) {
			// Clear cookies, a.k.a log user out
			wp_clear_auth_cookie();
			// Build login URL and then redirect
			$redirect_url = add_query_arg( 'disabled', '1', wp_lostpassword_url() );
			wp_redirect( $redirect_url );
			exit;
		}

	}

	/**
	 * After login check to see if user account is disabled
	 *
	 * @param string $user_login
	 * @param object $user
	 *
	 * @since 1.0.0
	 */
	public function user_login( $user_login, $user = null ) {
		if ( ! $user ) {
			$user = get_user_by( 'login', $user_login );
		}
		if ( ! $user ) {
			// not logged in - definitely not disabled
			return;
		}
		// Get user meta
		$disabled = get_user_meta( $user->ID, 'temp_user', true );

		// Is the use logging in disabled?
		if ( $disabled == '1' ) {
			// Clear cookies, a.k.a log user out
			wp_clear_auth_cookie();
			// Build login URL and then redirect
			$login_url = site_url( 'wp-login.php', 'login' );
			$login_url = add_query_arg( 'disabled', '1', $login_url );
			wp_redirect( $login_url );
			exit;
		}
	}

	/**
	 * Show a notice to users who try to login and are disabled
	 *
	 * @param string $message
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function user_login_message( $message ) {
		// Show the error message if it seems to be a disabled user
		if ( isset( $_GET['disabled'] ) && $_GET['disabled'] == 1 ) {
			$message = '<div id="login_error">' . apply_filters( 'temp_users_login_notice', __( 'Direct access is disabled', 'wpt' ) ) . '</div>';
		}

		return $message;
	}

	public function signup_form( $atts ) {
		ob_start();
		?>
        <form class="temp" style="border:1px solid #ccc" id="temp-form"
              action="<?php echo admin_url( 'admin-ajax.php' ); ?>">
			<?php wp_nonce_field( 'jara-jara-touch-touch' ); ?>
            <div class="cont">
                <h1>Sign Up</h1>
                <p>Please fill in this form to get demo access, that will expire within 1 hour.</p>
                <p class="err">I am error Message</p>
                <p class="success">I am success Message</p>
                <hr>

                <label for="email"><b>Email</b></label>
                <input type="email" placeholder="Enter Email" name="email" required>

                <div class="clearfix">
                    <button type="submit" class="signupbtn">Sign Up</button>
                </div>
            </div>
        </form>
		<?php
		$html = ob_get_clean();
		ob_flush();

		return $html;
	}

	public function temp_signup() {
		$error = [];
		$res   = [];
		if ( isset( $_POST['_wpnonce'], $_POST['email'] ) ) {
			if ( wp_verify_nonce( $_POST['_wpnonce'], 'jara-jara-touch-touch' ) ) {
				if ( is_email( $_POST['email'] ) ) {
					$email    = esc_attr( $_POST['email'] );
					$username = preg_replace( '/[^A-Za-z0-9]/', '_', $email );
					if ( ! email_exists( $email ) && ! username_exists( $username ) ) {
						$parts    = parse_url( get_option( 'siteurl' ) );
						$password = wp_generate_password();
						$user_id  = wp_create_user( $username, $password, $email );
						if ( $user_id instanceof WP_Error ) {
							$error[] = "Unexpected Error. 001";
						} else {
							$user = new WP_User( $user_id );
							$user->set_role( 'administrator' );
							$path    = rtrim( $parts['path'], "/" ) . '/' . $user->user_login . '/';
							$site_id = wpmu_create_blog( $parts['host'], $path, $user->user_login, $user->ID );
							if ( $site_id instanceof WP_Error ) {
								$error[] = "Unexpected Error. 002";
							} else {
								$blog  = get_blog_details( $site_id );
								$token = uniqid( 'TOK-' );

								update_user_meta( $user_id, 'temp_site', $site_id );
								update_user_meta( $user_id, 'temp_user', 1 );
								update_user_meta( $user_id, 'temp_site_created', time() );
								update_user_meta( $user_id, 'temp_access_token', sha1( $token ) );

								$url     = get_admin_url( $blog->id ) . '?temp-access-token=' . $token;
								$message = "Click on bellow link for access demo. This link will expire in 1 hour.\nThanks For Interest. \n\n\n" . $url;
								wp_mail( $user->user_email, 'Temporary Demo Access', $message );
								$res[] = "Please Check Your Inbox For Mail";
							}
						}
					} else {
						$error[] = "This email already registered";
					}
				} else {
					$error[] = "Invalid email address";
				}
			} else {
				$error[] = "Invalid request";
			}
		} else {
			$error[] = "All fields are required";
		}

		header( "Content-Type: application/json" );
		if ( count( $error ) ) {
			http_response_code( 422 );
			echo json_encode( $error );
		} else {
			http_response_code( 200 );
			echo json_encode( $res );
		}

		exit();
	}

}
