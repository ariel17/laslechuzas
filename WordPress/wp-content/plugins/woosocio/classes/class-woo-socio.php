<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
include_once( 'facebook.php' );
/**
 * WooSocio Base Class
 *
 * All functionality pertaining to core functionality of the WooSocio plugin.
 *
 * @package WordPress
 * @subpackage WooSocio
 * @author qsheeraz
 * @since 0.0.1
 *
 * TABLE OF CONTENTS
 *
 * public $version
 * private $file
 *
 * private $token
 * private $prefix
 *
 * private $plugin_url
 * private $assets_url
 * private $plugin_path
 *
 * public $facebook
 * private $fb_user_profile
 * private $app_id
 * private $secret
 *
 * - __construct()
 * - init()
 * - woosocio_meta_box()
 * - woosocio_ajax_action()
 * - woosocio_admin_init()
 * - socialize_post()
 * - woosocio_admin_menu()
 * - woosocio_admin_styles()
 * - socio_settings()
 * - products_list()
 * - check_connection()
 * - save_app_info()
 * - update_page_info()
 *
 * - load_localisation()
 * - activation()
 * - register_plugin_version()
 */

class Woo_Socio {
	public $version;
	private $file;

	private $token;
	private $prefix;

	private $plugin_url;
	private $assets_url;
	private $plugin_path;
	
	public $facebook;
	public $fb_user_profile = array();
	public $fb_user_pages = array();
	
	private $fb_app_id;
	private $fb_secret;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct ( $file ) {
		$this->version = '';
		$this->file = $file;
		$this->prefix = 'woo_socio_';
		$this->fb_app_id = get_option( 'fb_app_id' );
		$this->fb_secret = get_option( 'fb_app_secret' );

		/* Plugin URL/path settings. */
		$this->plugin_url = str_replace( '/classes', '', plugins_url( plugin_basename( dirname( __FILE__ ) ) ) );
		$this->plugin_path = str_replace( 'classes', '', plugin_dir_path( __FILE__ ));
		$this->assets_url = $this->plugin_url . '/assets';
		
		$this->facebook = new Facebook(array('appId'  	  => $this->fb_app_id,
  											 'secret' 	  => $this->fb_secret,
											 'status' 	  => true,
											 'cookie' 	  => true,
											 'xfbml' 	  => true,
											 'fileUpload' => true ));
		
	} // End __construct()

	/**
	 * init function.
	 *
	 * @access public
	 * @return void
	 */
	public function init () {
		add_action( 'init', array( $this, 'load_localisation' ) );

		add_action( 'admin_init', array( $this, 'woosocio_admin_init' ) );
		add_action( 'admin_menu', array( $this, 'woosocio_admin_menu' ) );
		add_action( 'post_submitbox_misc_actions', array( $this, 'woosocio_meta_box' ) );
		add_action( 'save_post', array( $this, 'clear_cache' ));
		add_action( 'save_post', array( $this, 'socialize_post' ));
		add_action( 'wp_ajax_my_action', array( $this, 'woosocio_ajax_action' ));
		add_action( 'wp_ajax_save_app_info', array( $this, 'save_app_info' ));
		add_action( 'wp_ajax_update_page_info', array( $this, 'update_page_info' ));
		add_action( 'woocommerce_single_product_summary', array( $this, 'show_sharing_buttons'), 50, 2  );
		add_filter( 'manage_edit-product_columns', array($this, 'woosocio_columns'), 998);
		//add_filter( 'manage_product_posts_columns' , array($this, 'woosocio_columns'),9990,1);
		add_action( 'manage_product_posts_custom_column', array($this, 'woosocio_custom_product_columns') );
		//add_filter( 'manage_post_posts_columns' , array($this, 'woosocio_columns'),9990);
		

		// Run this on activation.
		register_activation_hook( $this->file, array( $this, 'activation' ) );
	} // End init()
	
	function pa($arr){

		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}

		/**
	 * woosocio_columns function.
	 *
	 * @access public
	 * @return columns
	 */
	function woosocio_columns($columns) {
		if ( isset( $_REQUEST['list'] ) && $_REQUEST['list'] == 'woosocio' ) {
			echo '<style>';
			echo '.actions {display: none;}';
			echo '.search-box {display: none;}';
			echo '.subsubsub {display: none;}';
			echo '</style>';

		    $columns = array();
			$columns["cb"] = "<input type=\"checkbox\" />";
			$columns["woo_name"] = __( 'Name', 'woosocio' );
			$columns["like_btn"] = __('Like/ Share Button?', 'woosocio');
			$columns["fb_post"] = __('Posted to Facebook?', 'woosocio');
			$columns["custom_msg"] = __('Custom Message', 'woosocio');

			return $columns;
		}
		else
			return $columns;
	}
		
	/**
	 * woosocio_custom_product_columns function.
	 *
	 * @access public
	 * @return void
	 */
	function woosocio_custom_product_columns( $column ) {
	global $post, $woocommerce, $the_product;

	if ( empty( $the_product ) || $the_product->id != $post->ID )
		$the_product = get_product( $post );

	switch ($column) {
		case "woo_name" :
			$edit_link = get_edit_post_link( $post->ID );
			$title = _draft_or_post_title();
			$post_type_object = get_post_type_object( $post->post_type );
			$can_edit_post = current_user_can( $post_type_object->cap->edit_post, $post->ID );

			echo '<strong><a class="row-title" href="'.$edit_link.'">' . $title.'</a>';
		break;
		case "like_btn" :
			$woo_like_fb = metadata_exists('post', $post -> ID, '_woosocio_like_facebook') ? get_post_meta( $post -> ID, '_woosocio_like_facebook', true ) : 'No';
			echo $woo_like_fb == 'checked' ? '<img src="'.$this->assets_url.'/yes.png" alt="Yes" width="25">' : '<img src="'.$this->assets_url.'/no.png" alt="No" width="25">';
		break;
		case "fb_post" :
			$woo_post_fb = metadata_exists('post', $post -> ID, '_woosocio_facebook') ? get_post_meta( $post -> ID, '_woosocio_facebook', true ) : 'No';
			echo $woo_post_fb == 'checked' ? '<img src="'.$this->assets_url.'/yes.png" alt="Yes" width="25">' : '<img src="'.$this->assets_url.'/no.png" alt="No" width="25">';			
		break;
		case "custom_msg" :
			echo get_post_meta( $post -> ID, '_woosocio_msg', true );
		break;
	}
}

	/**
	 * show_sharing_buttons function.
	 *
	 * @access public
	 * @return void
	 */
	public function show_sharing_buttons() {
		$post_id = get_the_ID();
		$socio_link = get_permalink( $post_id );
		$fb_like = metadata_exists('post', $post_id, '_woosocio_like_facebook') ? get_post_meta( $post_id, '_woosocio_like_facebook', true ) : 'checked';
		if ($fb_like) {
			if($this->fb_app_id)
				$fb_appid_option = '&appId='.$this->fb_app_id;
		  ?>
		  <div class="fb-like" data-href="<?php echo $socio_link; ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
		  <div id="fb-root"></div>
		  <script><!--
		  (function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/all.js#xfbml=1<?php echo $fb_appid_option; ?>";
			fjs.parentNode.insertBefore(js, fjs);
		  }(document, 'script', 'facebook-jssdk'));//-->
          </script> 
		  <?php
		}
	}

	/**
	 * woosocio_meta_box function.
	 *
	 * @access public
	 * @return void
	 */
	public function woosocio_meta_box() {
		global $post;
		global $post_type;
		$post_id = get_the_ID();
		
		if ( $post_type == 'product' )
		{
			?>

		<div id="woosocio" class="misc-pub-section misc-pub-section-last">
			<?php
			$content = '';

			_e( 'WooSocio:', 'woosocio' );
			//metadata_exists('post', $post_id, '_woosocio_facebook');
			$like_chkbox_val = metadata_exists('post', $post_id, '_woosocio_like_facebook') ? get_post_meta( $post_id, '_woosocio_like_facebook', true ) : 'checked';
			$chkbox_val = metadata_exists('post', $post_id, '_woosocio_facebook') ? get_post_meta( $post_id, '_woosocio_facebook', true ) : 'checked';
			$saved_msg = ( get_post_meta( $post_id, '_woosocio_msg', true ) ? get_post_meta( $post_id, '_woosocio_msg', true ) : $post->title );
			if ( $this->check_connection() ): 
				echo '&nbsp;<img src="'.$this->assets_url.'/connected.gif" alt="Connected "> as: '."<b>".$this->fb_user_profile['name']."</b>";
				//echo "&nbsp;" . __( 'Connected as: '."<b>".$this->fb_user_profile['name']."</b>", 'woosocio' );
			else:
				echo "&nbsp;<b>" . __( 'Not Connected', 'woosocio' )."</b>";
				?>&nbsp;<a href="<?php echo admin_url( 'options-general.php?page=woosocio' ); ?>" target="_blank"><?php _e( 'Connect', 'woosocio' ); ?></a>
			<?php endif; ?>
			<div id="woosocio-form" style="display: none;">
            	<br />
                <input type="checkbox" name="like_facebook" id="like-facebook" <?php echo $like_chkbox_val; ?> />
                <label for="like-facebook"><b><?php _e( 'Show Like/Share buttons?', 'woosocio' ); ?></b></label><br />
                <input type="checkbox" name="chk_facebook" id="chk-facebook" <?php echo $chkbox_val; ?> />
                <label for="chk-facebook"><b><?php _e( 'Post to Facebook?', 'woosocio' ); ?></b></label><br />
				<label for="woosocio-custom-msg"><?php _e( 'Custom Message: (No html tags)', 'woosocio' ); ?></label>
				<textarea name="woosocio_custom_msg" id="woosocio-custom-msg"><?php echo $saved_msg; ?></textarea>
				<a href="#" id="woosocio-form-ok" class="button"><?php _e( 'Save', 'woosocio' ); ?></a>
				<a href="#" id="woosocio-form-hide"><?php _e( 'Cancel', 'woosocio' ); ?></a>
                <input type="hidden" name="postid" id="postid" value="<?php echo get_the_ID()?>" />
			</div>
             &nbsp; <a href="#" id="woosocio-form-edit"><?php _e( 'Edit', 'woosocio' ); ?></a>
		</div> 
        
		<script type="text/javascript"><!--
        jQuery(document).ready(function($){
                $("#woosocio-form").hide();
                
            $("#woosocio-form-edit").click(function(){
				$("#woosocio-form-edit").hide();
                $("#woosocio-form").show(1000);
            });
            
            $("#woosocio-form-hide").click(function(){
                $("#woosocio-form").hide(1000);
				$("#woosocio-form-edit").show();
            });
           
		    $("#woosocio-form-ok").click(function(){
				var custom_msg;
       			custom_msg = $("#woosocio-custom-msg").val();
				var data = {
					action: 'my_action',
					text1: custom_msg,
					postid: $("#postid").val(),
					chk_facebook: $("#chk-facebook").attr("checked"),
					like_facebook: $("#like-facebook").attr("checked")
				};
				$.post(ajaxurl, data, function(response) {
					console.log('Got this from the server: ' + response);
				});
                $("#woosocio-form").hide(1000);
				$("#woosocio-form-edit").show();
            });

        });
		//-->
        </script>
		<?php 
		}
	}

	/**
	 * woosocio_ajax_action function.
	 *
	 * @access public
	 * @return void
	 */	
	public function woosocio_ajax_action($post) {
		//global $post;
		//$post_id = get_the_ID();
		if ( ! update_post_meta ($_POST['postid'], '_woosocio_msg', 
								 $_POST['text1'] ) ) 
			   add_post_meta(    $_POST['postid'], '_woosocio_msg', 
			   				     $_POST['text1'], true );
		if ( ! update_post_meta ($_POST['postid'], '_woosocio_facebook', 
								 $_POST['chk_facebook'] ) ) 
			   add_post_meta(    $_POST['postid'], '_woosocio_facebook', 
			   				     $_POST['chk_facebook'], true );
	    if ( ! update_post_meta ($_POST['postid'], '_woosocio_like_facebook', 
								 $_POST['like_facebook'] ) ) 
			   add_post_meta(    $_POST['postid'], '_woosocio_like_facebook', 
			   				     $_POST['like_facebook'], true );

		//echo $_POST['text1'].$_POST['postid'];
		die(0);
		//die(); // this is required to return a proper result
	}
	
	/**
	 * woosocio_admin_init function.
	 *
	 * @access public
	 * @return void
	 */		
	public function woosocio_admin_init() {
       /* Register stylesheet. */
        wp_register_style( 'woosocioStylesheet', $this->assets_url.'/woosocio.css' );
		
		register_setting( 'woosocio_options', 'woosocio_settings' );
	
		add_settings_section(
			'woosocio_options_section', 
			__( 'WooSocio Options', 'woosocio' ), 
			array($this, 'woosocio_settings_section_callback'), 
			'woosocio_options'
		);
	
		add_settings_field( 
			'woosocio_checkbox_post_update', 
			__( 'Post to facebook everytime on product update?', 'woosocio' ), 
			array($this, 'woosocio_checkbox_post_update'), 
			'woosocio_options', 
			'woosocio_options_section' 
		);
	
		add_settings_field( 
			'woosocio_checkbox_notifications', 
			__( 'Get error notifications by email?', 'woosocio' ), 
			array($this, 'woosocio_checkbox_notifications'), 
			'woosocio_options', 
			'woosocio_options_section' 
		);

		add_settings_field( 
			'woosocio_option_posting_type', 
			__( 'Post product as ', 'woosocio' ), 
			array($this, 'woosocio_option_posting_type'), 
			'woosocio_options', 
			'woosocio_options_section' 
		);
	   
    }

	/**
	 * woosocio_options function.
	 *
	 * @access public
	 * @return void
	 */		
	public function woosocio_options () {
		
	?>
	<form action='options.php' method='post'>
		
		<h2>woosocio</h2>
		
		<?php
		settings_fields( 'woosocio_options' );
		do_settings_sections( 'woosocio_options' );
		submit_button();
		?>
		
	</form>
	<?php

	}

	function woosocio_checkbox_post_update(  ) { 
		$options = get_option( 'woosocio_settings' );
		if ( !isset ( $options['woosocio_checkbox_post_update'] ) )
			$options['woosocio_checkbox_post_update'] = 0;
		?>
		<input type='checkbox' name='woosocio_settings[woosocio_checkbox_post_update]' <?php checked( $options['woosocio_checkbox_post_update'], 1 ); ?> value='1'>
		<?php
	
	}
	
	
	function woosocio_checkbox_notifications(  ) { 
		$options = get_option( 'woosocio_settings' );
		if ( !isset ( $options['woosocio_checkbox_notifications'] ) )
			$options['woosocio_checkbox_notifications'] = 0;
		?>
		<input type='checkbox' name='woosocio_settings[woosocio_checkbox_notifications]' <?php checked( $options['woosocio_checkbox_notifications'], 1 ); ?> value='1'>
		<?php
	
	}

	function woosocio_option_posting_type(  ) { 
		$options = get_option( 'woosocio_settings' );
		if ( !isset ( $options['woosocio_option_posting_type'] ) )
			$options['woosocio_option_posting_type'] = 'link';
		?>
		<input type="radio" name='woosocio_settings[woosocio_option_posting_type]' value='link' 
				<?php echo ($options['woosocio_option_posting_type'] == 'link') ? 'checked' : '' ?> > 
				<?php echo __( 'Link', 'woosocio' ) ?>
		<input type="radio" name='woosocio_settings[woosocio_option_posting_type]' value='pic'
        		<?php echo ($options['woosocio_option_posting_type'] == 'pic') ? 'checked' : '' ?> > 
		        <?php echo __( 'Picture', 'woosocio' ) ?>
		<?php
	
	}

	function woosocio_settings_section_callback(  ) { 
	
		echo __( 'Settings', 'woosocio' );
	
	}

	/**
	 * socialize_post function.
	 *
	 * @access public
	 * @return void
	 */		
	public function socialize_post($post_id){

		global $wpdb;
		
		//$post_id = get_the_ID();
		$message = get_the_title($post_id);
		$this->check_connection();
		$fb_post = metadata_exists('post', $post_id, '_woosocio_facebook') ? get_post_meta( $post_id, '_woosocio_facebook', true ) : 'checked';
		$fb_posted = metadata_exists('post', $post_id, '_woosocio_fb_posted') ? get_post_meta( $post_id, '_woosocio_fb_posted', true ) : '';
		$message.= metadata_exists('post', $post_id, '_woosocio_msg') ? " - ".get_post_meta( $post_id, '_woosocio_msg', true ) : '';
		$fb_page_value = get_option( $this->fb_user_profile['id'].'_fb_page_id', array('me' => $this->fb_user_profile['id']));
		$options = get_option( 'woosocio_settings' );
		$repost = !$fb_posted ? true : $options['woosocio_checkbox_post_update'];
		
		$querystr = "
    		SELECT $wpdb->posts.* 
   			FROM   $wpdb->posts
    		WHERE  $wpdb->posts.ID = $post_id
    		AND    $wpdb->posts.post_status = 'publish' 
    		AND    $wpdb->posts.post_type = 'product'
 			";
 		$socio_post = $wpdb->get_row($querystr, OBJECT);
		
		if ( $this->check_connection() && $fb_post && !$socio_post->ID == '' && $repost)
		{
			if (key($fb_page_value) == 'page'){
				$page_info = $this -> facebook -> api("/".reset($fb_page_value)."?fields=access_token");
				$access_token = $page_info['access_token'];
			} else {
				$access_token = $this->facebook->getAccessToken();
			}

			if ( !isset ( $options['woosocio_option_posting_type'] ) )
				$options['woosocio_option_posting_type'] = 'link';
			
			if ( $options['woosocio_option_posting_type'] == 'link' ){

				$socio_link = get_permalink( $post_id );
				$this -> facebook -> api('/','POST',array('id'=>$socio_link, 'scrape'=>'true'));
				$post_arr = array(  'access_token'  => $access_token,
									'link' 			=> $socio_link,
                                    'message'		=> $message
								 );
				$node = '/feed';
				wp_remote_post("https://graph.facebook.com?id=".get_permalink($post_id)."&scrape=true");
	
			} else {
	
				$_pf = new WC_Product_Factory();  
				$_product = $_pf->get_product($post_id);

				$post_desc = strip_tags( get_post_field( 'post_content', $post_id ) );
				$message.= "\n" . __( 'Price: ', 'woosocio') . $_product->get_price() . "\n" . $post_desc . "\n" . __( 'Link: ', 'woosocio') . get_permalink( $post_id );

				$post_arr = array(	'access_token'  => $access_token,
		  							'message'		=> $message,
									'image'			=> '@' . get_attached_file( get_post_thumbnail_id( $post_id ) ),
	   							 );
				$node = '/photos';
			}

			try {

				$ret_obj = $this -> facebook -> api('/'.reset($fb_page_value).$node, 'POST', $post_arr);
				
				$this -> facebook -> api('/','POST',array('id'=>$socio_link, 'scrape'=>'true'));
				wp_remote_post("https://graph.facebook.com?id=".get_permalink($post_id)."&scrape=true");

				if ($ret_obj) {
					if ( ! update_post_meta ($post_id, '_woosocio_fb_posted', 'checked' ) ) 
						   add_post_meta(    $post_id, '_woosocio_fb_posted', 'checked', true );
				}

				if ( ! update_post_meta ($post_id, '_woosocio_facebook', 'checked' ) ) 
			   		   add_post_meta(    $post_id, '_woosocio_facebook', 'checked', true );
      		} 
			catch(FacebookApiException $e) {
				if ( $options['woosocio_checkbox_notifications'] ){
					$admin_email = get_option( 'admin_email' );
					if ( empty( $admin_email ) ) {
						$current_user = wp_get_current_user();
						$admin_email = $current_user->user_email;
					}
					
					$msg = "Dear user,\r\n";
					$msg.= "Your product ID ".$socio_post->ID." not posted on facebook due to following reason.\r\n";
					$msg.= $e->getMessage();
					
					wp_mail($admin_email, 'WooSocio - Notification', $msg, $this->woosocio_headers());
				}
				return false;
				//console.log($e->getType());
      		}   
		}
	}

	/**
	 * clear cache function.
	 *
	 * @access public
	 * @return void
	 */		
	function clear_cache($postid){
		if(get_post_status($postid) == "publish"){
			wp_remote_post("https://graph.facebook.com?id=".get_permalink($postid)."&scrape=true");
		}
	}

	/**
	 * woosocio_admin_menu function.
	 *
	 * @access public
	 * @return void
	 */		
	public function woosocio_admin_menu () {
		add_menu_page( 'WooSocio', 'WooSocio', 'manage_woocommerce', 'woosocio', '', $this->assets_url.'/menu_icon_wc.png', 51 );
		$page_logins   = add_submenu_page( 'woosocio', 'WooSocio Logins', 'WooSocio Logins', 'manage_woocommerce', 'woosocio', array( $this, 'socio_settings' ) );
		$page_products = add_submenu_page( 'woosocio', 'WooSocio Products', 'WooSocio Products', 'manage_woocommerce', 'products_list', array( $this, 'products_list' ) );
		$page_options  = add_submenu_page( 'woosocio', 'WooSocio Options', 'WooSocio Options', 'manage_woocommerce', 'woosocio_options', array( $this, 'woosocio_options' ) );
		/*$page = add_options_page( 'Socio Logins', 'WooSocio Options', 'manage_options', 'woosocio', array( $this, 'socio_settings' ) );*/
		add_action( 'admin_print_styles-' . $page_logins, array( $this, 'woosocio_admin_styles' ) );
		add_action( 'admin_print_styles-' . $page_products, array( $this, 'woosocio_admin_styles' ) );
		add_action( 'admin_print_styles-' . $page_options, array( $this, 'woosocio_admin_styles' ) );
	}

	/**
	 * woosocio_admin_styles function.
	 *
	 * @access public
	 * @return void
	 */			
	public function woosocio_admin_styles() {
       /*
        * It will be called only on plugin admin page, enqueue stylesheet here
        */
       wp_enqueue_style( 'woosocioStylesheet' );
   }

	/**
	 * socio_settings function.
	 *
	 * @access public
	 * @return void
	 */		
	public function socio_settings () {
		
		$filepath = $this->plugin_path.'woosocio.logins.php';
		if (file_exists($filepath))
			include_once($filepath);
		else
			die('Could not load file '.$filepath);
	}

	/**
	 * products_list function.
	 *
	 * @access public
	 * @return void
	 */		
	public function products_list () {
		
		?>
		<script type="text/javascript"><!--
			url = '<?php echo add_query_arg( array('post_type' => 'product',
											   	   'list'	   => 'woosocio'), admin_url('edit.php')) ?>';
			window.location.replace(url);//-->
		</script>
        <?php
		
    	/*wp_safe_redirect( add_query_arg( array('post_type' => 'product',
											   'list'	   => 'woosocio'), $url) );*/
		//wp_safe_redirect( 'www.yahoo.com' );

	}


	/**
	 * creating email headers.
	 *
	 * @access public
	 */
	public function woosocio_headers(){
		$admin_email = get_option( 'admin_email' );
		if ( empty( $admin_email ) ) {
			$admin_email = 'support@' . $_SERVER['SERVER_NAME'];
		}

		$from_name = get_option( 'blogname' );

		$header = "From: \"{$from_name}\" <{$admin_email}>\n";
		$header.= "MIME-Version: 1.0\r\n"; 
		$header.= "Content-Type: text/plain; charset=\"" . get_option( 'blog_charset' ) . "\"\n";
		$header.= "X-Priority: 1\r\n"; 

		return $header;
	}

	/**
	 * check connection function.
	 *
	 * @access public
	 */
	public function check_connection() {

 		try { 
			$this->fb_user_profile = $this->facebook->api('/me');
			$this->fb_user_pages = $this->facebook->api('/me/accounts');
		 	return $this->fb_user_profile;
		} catch (FacebookApiException $e) {
			return false;
		}
	}


	/**
	 * save facebook app id and secret function.
	 *
	 * @access public
	 */
	public function save_app_info() {
		update_option( 'fb_app_id', $_POST['fb_app_id'] );
		update_option( 'fb_app_secret', $_POST['fb_app_secret'] );
 	}

	/**
	 * update facebook page id function.
	 *
	 * @access public
	 */
	public function update_page_info() {
		$this->check_connection();
		$user_sign = $this->fb_user_profile['id'].'_fb_page_id';
		if(update_option( $user_sign, array($_POST['fb_type'] => $_POST['fb_page_id']) ))
			_e( 'Page Info Updated!', 'woosocio');
		else
			_e( 'Unable to update page info! Please try again.', 'woosocio');
		die(0);
		//update_option( 'fb_app_secret', $_POST['fb_app_secret'] );
 	}
	
	/**
	 * load_localisation function.
	 *
	 * @access public
	 * @return void
	 */
	public function load_localisation () {
		$lang_dir = trailingslashit( str_replace( 'classes', 'lang', plugin_basename( dirname(__FILE__) ) ) );
		load_plugin_textdomain( 'woosocio', false, $lang_dir );
	} // End load_localisation()

	/**
	 * activation function.
	 *
	 * @access public
	 * @return void
	 */
	public function activation () {
		$this->register_plugin_version();
	} // End activation()

	/**
	 * register_plugin_version function.
	 *
	 * @access public
	 * @return void
	 */
	public function register_plugin_version () {
		if ( $this->version != '' ) {
			update_option( 'woosocio' . '-version', $this->version );
		}
	} // End register_plugin_version()
} // End Class
?>