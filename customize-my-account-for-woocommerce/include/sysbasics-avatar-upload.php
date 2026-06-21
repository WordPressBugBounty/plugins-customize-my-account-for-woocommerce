<?php

class wcmamtx_upload_avatar_tab {


	private $user_id_being_edited;


	public function __construct() {

		add_filter( 'get_avatar_data',			 array( $this, 'wcmamtx_get_avatar_data'               ), 10, 2 );
		add_filter( 'get_avatar',				 array( $this, 'get_avatar'               ), 10, 6 );
		add_filter( 'avatar_defaults',			 array( $this, 'avatar_defaults'          )        );
		
		
		add_shortcode( 'sysbasics_user_avatar',	 array( $this, 'wcmamtx_shortcode'));

		add_action('wp_ajax_handle_ajax_file_upload', array( $this, 'backend_ajax_file_uploader'));

		add_action('wp_ajax_wcmamtx_restore_avatar_function', array( $this, 'wcmamtx_restore_avatar_function'));

	
		add_action('init',array( $this, 'wcmamtx_capture_upload_camera_image'));

		add_action('wp_ajax_wcmam_save_avatar',array( $this, 'wcmam_save_avatar'));
		
	}

	public function wcmam_save_avatar() {

		check_ajax_referer(
			'wcmamtx_ajax_security_nonce',
			'security'
		);

		if ( ! is_user_logged_in() ) {
			wp_send_json_error(
				array(
					'message' => esc_html__("User is not logged in","customize-my-account-for-woocommerce")
				)
			);
		}

		if ( empty( $_FILES['avatar'] ) ) {
			wp_send_json_error();
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';

		$avatar_settings = (array) get_option( 'wcmamtx_avatar_settings' );

		$default_options_format = array ( 0 => 'jpg', 1 => 'jpeg', 2 => 'jpe', 3 => 'gif', 4 => 'png', 5 => 'webp' );

        
        $chosen_formats = isset($avatar_settings['allowed_formats']) ? $avatar_settings['allowed_formats'] : $default_options_format;

		add_filter( 'wp_handle_upload_prefilter', array( $this, 'custom_limit_image_upload_size'), 10, 1 );


        // Allowed file extensions/types
		$mimes = array(
			'jpg'          => 'image/jpeg',
			'jpeg'         => 'image/jpeg',
			'jpe'          => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'webp'         => 'image/webp',
		);


		foreach ($mimes as $mkey=>$mvalue) {

			if (!in_array($mkey, $chosen_formats)) {

				unset($mimes[$mkey]);

			}

		}


		$upload = wp_handle_upload(
			$_FILES['avatar'],
			array(
				'test_form' => false,
				'mimes' => $mimes
			)
		);

		if ( isset( $upload['error'] ) ) {
			wp_send_json_error();
		}

		$attachment = array(
			'post_mime_type' => $upload['type'],
			'post_title'     => basename(
				$upload['file']
			),
			'post_status'    => 'inherit'
		);

		$attachment_id = wp_insert_attachment(
			$attachment,
			$upload['file']
		);

		require_once ABSPATH .
		'wp-admin/includes/image.php';

		$metadata = wp_generate_attachment_metadata(
			$attachment_id,
			$upload['file']
		);

		wp_update_attachment_metadata(
			$attachment_id,
			$metadata
		);

		

		$feat_image_url = wp_get_attachment_url( $attachment_id );

		$user_id = get_current_user_id();

		delete_user_meta( $user_id, 'sysbasics_user_avatar' );

		update_user_meta( $user_id, 'sysbasics_user_avatar', array( 'full' => $feat_image_url) );

		remove_filter('wp_handle_upload_prefilter',array( $this, 'custom_limit_image_upload_size' ),10);

		wp_send_json_success();

	}

	public function wcmamtx_capture_upload_camera_image() {


		if ( ! is_user_logged_in() ) {
			return '';
		}


		if ( empty( $_POST['web_cam_submit'] ) ) {
			return '';
		}

		$img = isset( $_POST['image'] ) ? wp_unslash( $_POST['image'] ) : '';

		if ( empty( $img ) ) {
			return '';
		}

		$slug_raw = isset( $_POST['slug'] ) ? wp_unslash( $_POST['slug'] ) : '';
		$slug = preg_replace( '/[^A-Za-z0-9_-]/', '', (string) $slug_raw );


		if ( ! preg_match( '#^data:image/(?P<mime>[a-zA-Z0-9+]+);base64,(?P<data>.+)$#', $img, $matches ) ) {
			return '';
		}

		$mime = strtolower( $matches['mime'] );
		$base64data = $matches['data'];

		$image_base64 = base64_decode( $base64data, true );
		if ( false === $image_base64 ) {
			return '';
		}

		$ext_map = array(
			'png'  => 'png',
			'jpeg' => 'jpg',
			'jpg'  => 'jpg',
			'gif'  => 'gif',
			'webp' => 'webp',
		);

		if ( ! isset( $ext_map[ $mime ] ) ) {
			return '';
		}

		$ext = $ext_map[ $mime ];

		$upload_dir = wp_upload_dir();
		if ( ! empty( $upload_dir['error'] ) ) {
			return '';
		}

		$filename = uniqid( 'webcam_' ) . '.' . $ext;
		$fullpath = trailingslashit( $upload_dir['path'] ) . $filename;

		$written = file_put_contents( $fullpath, $image_base64 );
		if ( false === $written ) {
			return '';
		}

		$image_info = @getimagesize( $fullpath );
		if ( false === $image_info ) {
			@unlink( $fullpath );
			return '';
		}

		$wp_filetype = wp_check_filetype( $filename, null );
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'] ?? $image_info['mime'],
			'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attach_id = wp_insert_attachment( $attachment, $fullpath );

		if ( is_wp_error( $attach_id ) || ! $attach_id ) {
			@unlink( $fullpath );
			return '';
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';
		$attach_data = wp_generate_attachment_metadata( $attach_id, $fullpath );

		if ( ! is_wp_error( $attach_data ) ) {

			wp_update_attachment_metadata( $attach_id, $attach_data );



			$feat_image_url = wp_get_attachment_url( $attach_id );

			$user_id = get_current_user_id();

			$old_avatars = get_user_meta( $user_id, 'sysbasics_user_avatar', true );
			$upload_path = wp_upload_dir();

			if ( is_array( $old_avatars ) ) {
				foreach ( $old_avatars as $old_avatar ) {
					$old_avatar_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $old_avatar );
					@unlink( $old_avatar_path );
				}
			}

			delete_user_meta( $user_id, 'sysbasics_user_avatar' );

			update_user_meta( $user_id, 'sysbasics_user_avatar', array( 'full' => $feat_image_url) );
		}



		return $attach_id;
	}

	public function custom_limit_image_upload_size( $file ) {


    // Only check if it is an image
		if ( strpos( $file['type'], 'image' ) !== false ) {

			$avatar_settings = (array) get_option( 'wcmamtx_avatar_settings' );
			$max_size = isset($avatar_settings['max_size']) ? $avatar_settings['max_size'] : "1024";

            $image_size_limit = $max_size; // Define your maximum size in Kilobytes (KB)
            $current_size = $file['size'] / 1024; // Convert bytes to KB

            if ( $current_size > $image_size_limit ) {
        	    $file['error'] = sprintf( __( 'ERROR: Images cannot be larger than %d KB.','customize-my-account-for-woocommerce' ), $image_size_limit );
            }
        }
        return $file;
    }

	public function wcmamtx_restore_avatar_function() {

		check_ajax_referer( 'wcmamtx_ajax_security_nonce', 'nonce' );

		$user_id = get_current_user_id();
			// Nuke the current avatar
		$this->avatar_delete( $user_id );

		$message = array();

        $message['message'] = esc_html__('Avatar restored successfully','customize-my-account-for-woocommerce');
       
		wp_send_json_success($message);
	

    
	}


	public function backend_ajax_file_uploader() {

        // 1. Validate security nonce
		if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'ajax_file_upload_nonce')) {

			$st_message = esc_html__('Security validation failed','customize-my-account-for-woocommerce');
			wp_send_json_error($st_message);
			
		}

        // 2. Ensure a file was actually sent
		if (!isset($_FILES['file_data'])) {
			$st_message = esc_html__('No file was detected in the payload','customize-my-account-for-woocommerce');
			wp_send_json_error($st_message);
			
		}

		if ( ! is_user_logged_in() ) {
			wp_send_json_error();
		}

		$uploaded_file = $_FILES['file_data'];

        // 3. Include necessary WordPress upload APIs
		if (!function_exists('wp_handle_upload')) {
			require_once(ABSPATH . 'wp-admin/includes/file.php');
		}

		$default_options_format = array ( 0 => 'jpg', 1 => 'jpeg', 2 => 'jpe', 3 => 'gif', 4 => 'png', 5 => 'webp' );

		$avatar_settings        = (array) get_option( 'wcmamtx_avatar_settings' );
			

		$chosen_formats = isset($avatar_settings['allowed_formats']) ? $avatar_settings['allowed_formats'] : $default_options_format;

		add_filter( 'wp_handle_upload_prefilter', array( $this, 'custom_limit_image_upload_size'), 10, 1 );


        // Allowed file extensions/types
		$mimes = array(
			'jpg'          => 'image/jpeg',
			'jpeg'         => 'image/jpeg',
			'jpe'          => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'webp'         => 'image/webp',
		);


		foreach ($mimes as $mkey=>$mvalue) {

			if (!in_array($mkey, $chosen_formats)) {

				unset($mimes[$mkey]);

			}

		}

		if (isset($uploaded_file)) {

			$movefile = wp_handle_upload( $uploaded_file, array( 
				 'mimes' => $mimes, 
				 'test_form' => false, 
				 'unique_filename_callback' => array( $this, 'unique_filename_callback' )
				)
		    );

			if ($movefile && !isset($movefile['error'])) {
            // Success: $movefile['url'] contains the public link to your file
				$user_id = get_current_user_id();
			// Nuke the current avatar
				$this->avatar_delete( $user_id );
				update_user_meta( $user_id, 'sysbasics_user_avatar', array( 'full' => $movefile['url'] ) );
				$sucess['message'] = esc_html__('Avatar uploaded sucessfully','customize-my-account-for-woocommerce');
				$sucess['url'] = $movefile['url'];
				wp_send_json_success($sucess);
			} else {
                // Failure: Output the specific system error
				wp_send_json_error($movefile['error']);
			}

		} elseif ( ! empty( $_POST['basic-user-avatar-erase'] ) ) {

			$user_id = get_current_user_id();
			// Nuke the current avatar
			$this->avatar_delete( $user_id );

			$sucess['message'] = esc_html__('Avatar restored sucessfully','customize-my-account-for-woocommerce');
			$sucess['url'] = '';
			wp_send_json_success($sucess);
		}

		remove_filter('wp_handle_upload_prefilter',array( $this, 'custom_limit_image_upload_size' ),10);
		
	}

	/**
	 * File names are magic
	 *
	 * @since 1.0.0
	 * @param string $dir
	 * @param string $name
	 * @param string $ext
	 * @return string
	 */
	public function unique_filename_callback( $dir, $name, $ext ) {

		$user_id = get_current_user_id();
		
		$user = get_user_by( 'id', $user_id );
		$name = $base_name = sanitize_file_name( strtolower( $user->display_name ) . '_avatar' );

		$number = 1;

		while ( file_exists( $dir . "/$name$ext" ) ) {
			$name = $base_name . '_' . $number;
			$number++;
		}

		return $name . $ext;
	}


	public function sanitize_options( $input ) {
		$new_input['wcmamtx_upload_avatar_tab_caps'] = empty( $input ) ? 0 : 1;
		return $new_input;
	}


	public function wcmamtx_get_avatar_data( $args, $id_or_email ) {
		if ( ! empty( $args['force_default'] ) ) {
			return $args;
		}

		global $wpdb;

		$return_args = $args;

		if ( is_numeric( $id_or_email ) && 0 < $id_or_email ) {
			$user_id = (int) $id_or_email;
		} elseif ( is_object( $id_or_email ) && isset( $id_or_email->user_id ) && 0 < $id_or_email->user_id ) {
			$user_id = $id_or_email->user_id;
		} elseif ( is_object( $id_or_email ) && isset( $id_or_email->ID ) && isset( $id_or_email->user_login ) && 0 < $id_or_email->ID ) {
			$user_id = $id_or_email->ID;
		} elseif ( is_string( $id_or_email ) && false !== strpos( $id_or_email, '@' ) ) {
			$_user = get_user_by( 'email', $id_or_email );

			if ( ! empty( $_user ) ) {
				$user_id = $_user->ID;
			}
		}

		if ( empty( $user_id ) ) {
			return $args;
		}

		$user_avatar_url = null;

		// Get the user's local avatar from usermeta.
		$local_avatars = get_user_meta( $user_id, 'sysbasics_user_avatar', true );

		if ( empty( $local_avatars ) || empty( $local_avatars['full'] ) ) {
			// Try to pull avatar from WP User Avatar.
			$wp_user_avatar_id = get_user_meta( $user_id, $wpdb->get_blog_prefix() . 'user_avatar', true );
			if ( ! empty( $wp_user_avatar_id ) ) {
				$wp_user_avatar_url = wp_get_attachment_url( intval( $wp_user_avatar_id ) );
				$local_avatars = array( 'full' => $wp_user_avatar_url );
				update_user_meta( $user_id, 'sysbasics_user_avatar', $local_avatars );
			} else {
				// We don't have a local avatar, just return.
				return $args;
			}	
		}


		$size = apply_filters( 'wcmamtx_upload_avatar_tab_default_size', (int) $args['size'], $args );

		// Generate a new size
		if ( empty( $local_avatars[$size] ) ) {

			$upload_path      = wp_upload_dir();
			$avatar_full_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $local_avatars['full'] );
			$image            = wp_get_image_editor( $avatar_full_path );
			$image_sized      = null;

			if ( ! is_wp_error( $image ) ) {
				$image->resize( $size, $size, true );
				$image_sized = $image->save();
			}

			// Deal with original being >= to original image (or lack of sizing ability).
			if ( empty( $image_sized ) || is_wp_error( $image_sized ) ) {
				$local_avatars[ $size ] = $local_avatars['full'];
			} else {
				$local_avatars[ $size ] = str_replace( $upload_path['basedir'], $upload_path['baseurl'], $image_sized['path'] );
			}

			// Save updated avatar sizes
			update_user_meta( $user_id, 'sysbasics_user_avatar', $local_avatars );

		} elseif ( substr( $local_avatars[ $size ], 0, 4 ) != 'http' ) {
			$local_avatars[ $size ] = home_url( $local_avatars[ $size ] );
		}

		if ( is_ssl() ) {
			$local_avatars[ $size ] = str_replace( 'http:', 'https:', $local_avatars[ $size ] );
		}

		$user_avatar_url = $local_avatars[ $size ];

		if ( $user_avatar_url ) {
			$return_args['url'] = $user_avatar_url;
			$return_args['found_avatar'] = true;
		}


		return apply_filters( 'sysbasics_user_avatar_data', $return_args );
	}


	public function get_avatar( $avatar, $id_or_email, $size = 96, $default = '', $alt = false, $args = array() ) {

		return apply_filters( 'sysbasics_user_avatar', $avatar, $id_or_email );
	}



	

	


	public function wcmamtx_shortcode($atts=null) {

		$attributes = shortcode_atts( array(
		  'size' => 150,
		  'min_height' => 150,
		  'min_width' => 150,
		  'max_height' => 150,
		  'max_width' => 150,
	    ), $atts );

		ob_start();


		if ( ! is_user_logged_in() ) {
			return;
		}

		$user_id     = get_current_user_id();
		$profileuser = get_userdata( $user_id );

		if ( isset( $_POST['manage_avatar_submit'] ) ){
			$this->edit_user_profile_update( $user_id );
		}

		$avatar_settings = (array) get_option( 'wcmamtx_avatar_settings' );

		if (array_key_exists(0, $avatar_settings)) {

			$avatar_settings['round_avatar'] = "yes";

			$avatar_settings['avatar_size'] = "150";
			
			$avatar_settings['webcam_capture'] = "yes";

		}

		$round_avatar = isset($avatar_settings['round_avatar']) && ($avatar_settings['round_avatar'] == "yes") ? "round" : "square";

		?>
		<center>

		<div class="wcmamtx_upload_div <?php echo $round_avatar; ?>">
			<?php

			

			$avatar_size = isset($avatar_settings['avatar_size']) ? $avatar_settings['avatar_size'] : "150";



			$avatar_size = isset($atts['size']) ? esc_attr((int) $atts['size']) : $avatar_size;


			echo wcmamtx_get_avatar_default($profileuser,$avatar_size,$atts);



			$allow_avatar_change = 'yes';

			$avatar_settings = (array) get_option( 'wcmamtx_avatar_settings' );

			if (isset($avatar_settings['allow_avatar_change']) && ($avatar_settings['allow_avatar_change'] == "yes")) {

				$allow_avatar_change = 'no';
			} else {
				$allow_avatar_change = 'yes';
			}

			$default_upload_icon = ''.wcmamtx_PLUGIN_URL.'assets/images/camera.svg';


			$swatchimage = isset($avatar_settings['upload_icon']) ? $avatar_settings['upload_icon'] : "";

			if (isset($swatchimage) && ($swatchimage != "")) {
				$swatchurl     = wp_get_attachment_thumb_url( $swatchimage );
			} 

			$default_upload_icon = isset($swatchurl) && ($swatchimage != "") ? $swatchurl : $default_upload_icon;

			if (isset($allow_avatar_change) && ($allow_avatar_change == 'yes')) { ?>
				<a href="#" class="wcmamtx_upload_avatar"><img class="camera wcmamtx_avatar <?php echo $round_avatar; ?>" src="<?php echo $default_upload_icon; ?> "></a>
			<?php } ?>
		</div>	
	    </center>

		<?php

		

		$this->upload_wcmamtx_modal_avatar($profileuser,$avatar_size,$atts);
		
		return ob_get_clean();
	}


	public function upload_wcmamtx_modal_avatar($profileuser,$avatar_size,$atts) {
		?>
		<!-- Trigger/Open The wcmamtx_modal -->

		<!-- The wcmamtx_modal -->
		<div id="mywcmamtx_modal" class="wcmamtx_modal">

			<!-- wcmamtx_modal content -->
			<div class="wcmamtx_modal-content">
				<span class="wcmamtx_modal_close">&times;</span>
                <?php include('avatar/upload_form.php'); ?>
			</div>

		</div>

		<!-- Trigger/Open The wcmamtx_modal -->

		<!-- The wcmamtx_modal -->

		
		<div id="mywcmamtx_modal_webcam" class="wcmamtx_modal webcam">

			<!-- wcmamtx_modal content -->
			<div class="wcmamtx_modal-content webcam">
				<span class="wcmamtx_modal_close_webcam">&times;</span>
                <?php include('avatar/webcam_modal.php'); ?>
                <div class="woocommerce-message wcmamtx_no_camera_message" style="display:none;">
                	<?php echo esc_html__('Camera access is blocked. Allow it in your browser settings','customize-my-account-for-woocommerce'); ?>
                </div>
			</div>

		</div>


		<?php
	}





	public function avatar_defaults( $avatar_defaults ) {
		remove_action( 'get_avatar', array( $this, 'get_avatar' ) );
		return $avatar_defaults;
	}


	public function avatar_delete( $user_id ) {
		$old_avatars = get_user_meta( $user_id, 'sysbasics_user_avatar', true );
		$upload_path = wp_upload_dir();

		if ( is_array( $old_avatars ) ) {
			foreach ( $old_avatars as $old_avatar ) {
				$old_avatar_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $old_avatar );
				@unlink( $old_avatar_path );
			}
		}

		delete_user_meta( $user_id, 'sysbasics_user_avatar' );
	}



}
$wcmamtx_upload_avatar_tab = new wcmamtx_upload_avatar_tab;