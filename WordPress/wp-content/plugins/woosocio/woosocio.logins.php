<?php

global $woosocio, $is_IE;
if(isset($_GET['action']) && $_GET['action'] === 'logout'){
    $woosocio -> facebook -> destroySession();
}
$fb_user = $woosocio -> facebook -> getUser();

// Login or logout url will be needed depending on current user state.

if ($fb_user) {
	$next_url = array( 'next' => admin_url().'admin.php?page=woosocio&logout=yes&action=logout' );
  	$logoutUrl = $woosocio -> facebook -> getLogoutUrl( $next_url );
	$user_profile = $woosocio -> facebook -> api('/me');
	$user_pages = $woosocio -> facebook -> api("/me/accounts");
	$user_groups = $woosocio -> facebook -> api("/me/groups");
} else {
  	$statusUrl = $woosocio->facebook->getLoginStatusUrl();
  	$loginUrl = $woosocio->facebook->getLoginUrl(array('scope' => 'manage_pages, publish_actions, publish_pages, user_photos, user_managed_groups'));
}

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title><?php _e( 'WooSocio Options', 'woosocio' ) ?></title>
</head>
<body>
<div class="woosocio_wrap">
  <h1><?php _e( 'WooSocio Logins', 'woosocio' ) ?></h1>
  <h3 id="woosocio"><?php _e( 'WooSocio', 'woosocio' ) ?></h3>
  <p>
  <?php esc_html_e( 'Connect your site to social networking sites and automatically share new products with your friends.', 'woosocio' ) ?>
  </p>
  <p style="font-size:12px">
  <?php esc_html_e( "You can use like/share buttons without connecting or App ID", 'woosocio' ) ?>
  </p>
  <?php 
	if ($is_IE){
	  echo "<p style='font-size:18px; color:#F00;'>" . __( 'Important Notice:', 'woosocio') . "</p>";
	  echo "<p style='font-size:16px; color:#F00;'>" . 
	  		__( 'You are using Internet Explorer. This plugin may not work properly with IE. Please use any other browser.', 'woosocio') . "</p>";
	  echo "<p style='font-size:16px; color:#F00;'>" . __( 'Recommended: Google Chrome.', 'woosocio') . "</p>";
	}
  ?>
  <div id="woosocio-services-block">
	<div class="woosocio-service-entry" >
		<div id="facebook" class="woosocio-service-left">
			<a href="https://www.facebook.com" id="service-link-facebook" target="_top">Facebook</a><br>
		</div>
		<div class="woosocio-service-right">
			<?php if($fb_user!==0):?>
            <?php _e( 'Connected as:', 'woosocio') ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a class="woosocio-profile-link" href="https://www.facebook.com" target="_top"><?php echo $user_profile['name'] ?></a><br>
            <a id="pub-disconnect-button1" class="woosocio-add-connection button" href="<?php echo $logoutUrl; ?>" target="_top"><?php _e('Disconnect', 'woosocio')?></a><br>
            <?php else: ?>
            <!--Not Connected...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
            <a id="facebook" class="woosocio-add-connection button" href="<?php echo esc_url( $loginUrl ); ?>" target="_top"><?php _e('Connect', 'woosocio')?></a>
            <img id="working" src="<?php echo $woosocio->assets_url.'/spinner.gif' ?>" alt="Wait..." height="22" width="22" style="display: none;"><br>
            <?php endif; ?>

            <?php 
			if (get_option( 'fb_app_id' ) && get_option( 'fb_app_secret' )): 
			    echo '<a id="app-details" href="javascript:">' . __('Show App Details', 'woosocio') . '</a>';
				echo '<div id="app-info" style="display: none;">';
			else:            
            	echo '<div id="app-info">';
			endif;
			?>
            <table class="form-table">
            <tr valign="top">
	  			<th scope="row"><label><?php _e('Your App ID:', 'woosocio') ?></label></th>
	  			<td>
	  				<input type="text" name="app_id" id="fb-app-id" placeholder="<?php _e('App ID', 'woosocio') ?>" value="<?php echo get_option( 'fb_app_id' ); ?>"><br>
                    <p style="font-size:10px"><?php _e("Don't have an app? You can get from ", 'woosocio') ?>
                    <a href="https://developers.facebook.com/apps" target="_new" style="font-size:10px">developers.facebook.com/apps</a>
	  			</td>
	  		</tr>
            <tr valign="top">
	  			<th scope="row"><label><?php _e('Your App Secret:', 'woosocio') ?></label></th>
	  			<td>
	  				<input type="text" name="app_secret" id="fb-app-secret" placeholder="<?php _e('App Secret', 'woosocio') ?>" value="<?php echo get_option( 'fb_app_secret' ); ?>"><br>
                    <p style="font-size:11px"><?php _e('Need more help? ', 'woosocio') ?>
                    <a href="https://developers.facebook.com/docs/opengraph/getting-started/#create-app" target="_new" style="font-size:11px"><?php _e('Click here', 'woosocio') ?></a>
	  			</td>
	  		</tr>
            <tr valign="top">
     	  		<th scope="row"></th>
	  			<td>
                	<a id="btn-save" class="button-primary button" href="javascript:"><?php _e('Save', 'woosocio') ?></a>
	  			</td>
	  		</tr>
            </table>
			<iframe 
            	src="https://player.vimeo.com/video/155268223" 
                width="500" 
            	height="282" 
                frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen>
            </iframe> 
			<p>
            	<a href="https://vimeo.com/155268223">How to create Facebook app for WooSocio</a>
                from <a href="https://vimeo.com/user48932448">Qamar Sheeraz</a>
                on <a href="https://vimeo.com">Vimeo</a>.
            </p>            
            
            </div>
		</div>
	
		<?php
        if($fb_user!==0){
			$user_sign = $user_profile['id'].'_fb_page_id';
			//echo get_option( $user_sign);
			$fb_page_value = get_option( $user_sign, $user_profile['id'] );
			//$this->pa($user_groups);
            echo "<h4>" . __( 'Post to:', 'woosocio' ) . "</h4>";
			echo "<h3>" . __( 'Pages:', 'woosocio' ) . "</h3>";
        ?>
            <img src="http://graph.facebook.com/<?php echo $user_profile['id'] ?>/picture" alt="No Image">
            <input type="radio" name="pages" value="<?php echo $user_profile['id'] ?>" <?php echo (reset($fb_page_value) == $user_profile['id'])?'checked':''?>><?php _e('Personal Page (Wall)', 'woosocio') ?><br>
            <input type="hidden" id="<?php echo $user_profile['id'] ?>" value="me">
        <?php
        $page_names = $user_pages['data'];
        foreach($page_names as $key => $page)
        {
        ?>
            <img src="http://graph.facebook.com/<?php echo $page['id'] ?>/picture" alt="No Image">
            <input type="radio" name="pages" value="<?php echo $page['id'] ?>" <?php echo (reset($fb_page_value) == $page['id']) ? 'checked':''?>><?php echo $page['name'] ?><br>
            <input type="hidden" id="<?php echo $page['id'] ?>" value="page">
        <?php
        }	//$woosocio->pa($user_profile);		 
		echo "<h3>" . __( 'Groups:', 'woosocio' ) . "</h3>";
        $group_names = $user_groups['data'];
        foreach($group_names as $key => $group)
        {
		?>
            <img src="http://graph.facebook.com/<?php echo $group['id'] ?>/picture" alt="No Image">
            <input type="radio" name="pages" value="<?php echo $group['id'] ?>" <?php echo (reset($fb_page_value) == $group['id']) ? 'checked':''?>><?php echo $group['name'] ?><br>
            <input type="hidden" id="<?php echo $group['id'] ?>" value="group">
        <?php
        }
		}	//$woosocio->pa($user_profile);	
		?>
        <img id="working-page" src="<?php echo $woosocio->assets_url.'/spinner.gif' ?>" alt="Wait..." height="15" width="15" style="display: none;"><br>
	</div>        
    
    
    <?php
	if($fb_user!==0){
		echo '<div class="woosocio-service-entry" style="font-size:18px;">';
		/*
			Check permissions
		*/
		$arr_permissions = $woosocio -> facebook -> api("/me/permissions");
		//$this->pa($arr_permissions['data']);
		echo "<h4>" . __( 'App Permissions:', 'woosocio' ) . "</h4>";
		echo '<table>';
		
		foreach ( $arr_permissions['data'] as $permission ) {
			echo '<tr>';
			echo '<td>' . $permission['permission'] . '</td>';
			echo '<td>' . $permission['status']     . '</td>';
			echo '</tr>';
		}
		echo '</table>';
    	echo '</div>';
	}
	?>

    <div class="woosocio-service-entry" style="font-size:18px; color:#03C">
    <?php
        echo '<a href="http://genialsouls.com/product/woosocio-pro/" target="_top">'.__('* WooSocio Pro version *', 'woosocio').'</a>';
		echo "</br></br>";
		_e('* Post on page as page owner (post from page)', 'woosocio'); echo "</br>";
		_e('* post to multiple pages and/or groups at once', 'woosocio'); echo "</br>";
		_e('* post products with optional time delay between posting', 'woosocio'); echo "</br>";
		_e('* Post products multiple times (on every update)', 'woosocio'); echo "</br>";
		_e('* Bulk posts to pages, groups (multiple posts at once)', 'woosocio'); echo "</br>";
		_e('* Add Facebook like/share buttons on product page', 'woosocio'); echo "</br>";
		_e('* Multi user ready', 'woosocio'); echo "</br>";
		_e('* Bulk like/share button on/off option', 'woosocio'); echo "</br>";
		_e('* Rich product page', 'woosocio'); echo "</br>";
		_e('* And many more to come...', 'woosocio'); echo "</br>";
	?>
    </div>
    <div class="woosocio-service-entry" style="font-size:18px; color:#03D">
    <?php
        echo '<br><br><h3>' . _e('* You may also like *', 'woosocio'); echo "</h3>";
		?> <a href="http://genialsouls.com/file-manager/" title="WP File Manager">File Manager</a><br><br> <?php
		_e('* BuddyPress Group File Share.', 'woosocio'); echo "</br>";
		_e('* Create Download Area.', 'woosocio'); echo "</br>";
		_e('* Group file sharing.', 'woosocio'); echo "</br>";
		_e('* Seven types of input fields.', 'woosocio'); echo "</br>";
		_e('* Bulk like/share button on/off option.', 'woosocio'); echo "</br>";
		_e('* Front end File Searching and Pagination.', 'woosocio'); echo "</br>";
		_e('* And many more...', 'woosocio'); echo "</br>";
        
	?>
    <br><a href="http://genialsouls.com/file-manager/" title="http://genialsouls.com"><?php _e('Visit our site for details', 'woosocio');?></a><br><br>
    </div>
  </div>
    <!-- Right Area Widgets -->  
    <?php 
		include_once 'right_area.php';
	 ?>
    <!-- Right Area Widgets -->  
</div>
  </body>
</html>
<script type="text/javascript">
jQuery(document).ready(function($){
		//$("#app-info").hide();
		
	$("#btn-save").click(function(){
		$("#working").show();
		
		var data = {
			action: 'save_app_info',
			fb_app_id: $("#fb-app-id").val(),
			fb_app_secret: $("#fb-app-secret").val()
		};
		
		$.post(ajaxurl, data, function(response) {
			console.log('Got this from the server: ' + response);
		location.reload();
		});	
		
		$("#app-info").hide(2000);
	});

	$("input:radio[name=pages]").click(function() {
		$("#working-page").show();

		var data = {
			action: 'update_page_info',
			fb_page_id: $(this).val(),
			fb_type: $("#"+$(this).val()).val()
		};
		
		$.post(ajaxurl, data, function(response) {
			console.log('Got this from the server: ' + response);
			$("#working-page").hide();
			alert(response);
		});
	});
	
	$("#app-details").click(function(){
		$("#app-info").toggle(1000);
	});
});
</script>