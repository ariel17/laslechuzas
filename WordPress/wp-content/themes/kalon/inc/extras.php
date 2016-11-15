<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Kalon
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function kalon_body_classes( $classes ) {

    global $post;
    
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
    
    // Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}
    
    // Adds a class of custom-background-color to sites with a custom background color.
    if ( get_background_color() != 'ffffff' ) {
		$classes[] = 'custom-background-color';
	}
    
    if( !( is_active_sidebar( 'right-sidebar' ) ) ) {
        $classes[] = 'full-width'; 
    }
    
    if( is_page() ){
        $sidebar_layout = get_post_meta( $post->ID, 'kalon_sidebar_layout', true );
        if( $sidebar_layout == 'no-sidebar' )
        $classes[] = 'full-width';
    }

	return $classes;
}
add_filter( 'body_class', 'kalon_body_classes' );

if( ! function_exists( 'kalon_excerpt' ) ) :
/**
 * kalon_excerpt can truncate a string up to a number of characters while preserving whole words and HTML tags
 *
 * @param string $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param string $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 *
 * @return string Trimmed string.
 * 
 * @link http://alanwhipple.com/2011/05/25/php-truncate-string-preserving-html-tags-words/
 */
function kalon_excerpt($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
    if ($considerHtml) {
        // if the plain text is shorter than the maximum length, return the whole text
        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        // splits all html-tags to scanable lines
        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
        $total_length = strlen($ending);
        $open_tags = array();
        $truncate = '';
        foreach ($lines as $line_matchings) {
            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
            if (!empty($line_matchings[1])) {
                // if it's an "empty element" with or without xhtml-conform closing slash
                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                    // do nothing
                // if tag is a closing tag
                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                    // delete tag from $open_tags list
                    $pos = array_search($tag_matchings[1], $open_tags);
                    if ($pos !== false) {
                    unset($open_tags[$pos]);
                    }
                // if tag is an opening tag
                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                    // add tag to the beginning of $open_tags list
                    array_unshift($open_tags, strtolower($tag_matchings[1]));
                }
                // add html-tag to $truncate'd text
                $truncate .= $line_matchings[1];
            }
            // calculate the length of the plain text part of the line; handle entities as one character
            $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
            if ($total_length+$content_length> $length) {
                // the number of characters which are left
                $left = $length - $total_length;
                $entities_length = 0;
                // search for html entities
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                    // calculate the real length of all entities in the legal range
                    foreach ($entities[0] as $entity) {
                        if ($entity[1]+1-$entities_length <= $left) {
                            $left--;
                            $entities_length += strlen($entity[0]);
                        } else {
                            // no more characters left
                            break;
                        }
                    }
                }
                $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                // maximum lenght is reached, so get off the loop
                break;
            } else {
                $truncate .= $line_matchings[2];
                $total_length += $content_length;
            }
            // if the maximum length is reached, get off the loop
            if($total_length>= $length) {
                break;
            }
        }
    } else {
        if (strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = substr($text, 0, $length - strlen($ending));
        }
    }
    // if the words shouldn't be cut in the middle...
    if (!$exact) {
        // ...search the last occurance of a space...
        $spacepos = strrpos($truncate, ' ');
        if (isset($spacepos)) {
            // ...and cut the text in this position
            $truncate = substr($truncate, 0, $spacepos);
        }
    }
    // add the defined ending to the text
    $truncate .= $ending;
    if($considerHtml) {
        // close all unclosed html-tags
        foreach ($open_tags as $tag) {
            $truncate .= '</' . $tag . '>';
        }
    }
    return $truncate;
}
endif; // End function_exists

/**
 * Callback for Social Links 
 */
function kalon_social_cb(){

    $facebook  = get_theme_mod( 'kalon_facebook' );
    $twitter   = get_theme_mod( 'kalon_twitter' );
    $instagram = get_theme_mod( 'kalon_instagram' );
    $pinterest = get_theme_mod( 'kalon_pinterest' );
    $linkedin  = get_theme_mod( 'kalon_linkedin' );
    $youtube   = get_theme_mod( 'kalon_youtube' );
    
    if( $facebook || $twitter || $instagram || $pinterest || $linkedin || $youtube ){
    ?>
    <ul class="social-networks">
		<?php if( $facebook ){?>
            <li><a href="<?php echo esc_url( $facebook );?>" target="_blank" title="<?php esc_html_e( 'Facebook', 'kalon' ); ?>"><span class="fa fa-facebook"></span></a></li>
         <?php } if( $instagram ){?>
            <li><a href="<?php echo esc_url( $instagram );?>" target="_blank" title="<?php esc_html_e( 'Instagram', 'kalon' ); ?>"><span class="fa fa-instagram"></span></a></li>
		<?php } if( $twitter ){?>    
            <li><a href="<?php echo esc_url( $twitter );?>" target="_blank" title="<?php esc_html_e( 'Twitter', 'kalon' ); ?>"><span class="fa fa-twitter"></span></a></li>
            <?php } if( $pinterest ){?>
            <li><a href="<?php echo esc_url( $pinterest );?>" target="_blank" title="<?php esc_html_e( 'Pinterest', 'kalon' ); ?>"><span class="fa fa-pinterest-p"></span></a></li>
		<?php } if( $linkedin ){?>
            <li><a href="<?php echo esc_url( $linkedin );?>" target="_blank" title="<?php esc_html_e( 'LinkedIn', 'kalon' ); ?>"><span class="fa fa-linkedin"></span></a></li>
        <?php } if( $youtube ){?>
            <li><a href="<?php echo esc_url( $youtube );?>" target="_blank" title="<?php esc_html_e( 'YouTube', 'kalon' ); ?>"><span class="fa fa-youtube"></span></a></li>    
        <?php } ?>
	</ul>
    <?php
    }
}
add_action( 'kalon_social', 'kalon_social_cb' );
 
/**
 * Callback for Home Page Slider 
 **/
function kalon_slider_cb(){
    
    $slider_caption  = get_theme_mod( 'kalon_slider_caption', '1' );    
    $slider_readmore = get_theme_mod( 'kalon_slider_readmore', __( 'Continue Reading', 'kalon' ) );
    $sticky_post     = get_option( 'sticky_posts' ); //get all sticky posts
    
    $kalon_qry = new WP_Query ( array( 
        'post_type'           => 'post', 
        'post_status'         => 'publish',
        'posts_per_page'      => -1,
        'post__in'            => $sticky_post,
        'ignore_sticky_posts' => 1 
    ) );
    
    if( $kalon_qry->have_posts() ){?>
        <div class="slider">
            <div class="flexslider">
                <ul class="slides">
                <?php
                while( $kalon_qry->have_posts() ){
                    $kalon_qry->the_post();
                    $kalon_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'kalon-slider' );
                ?>
                    <?php if( has_post_thumbnail() ){?>
                    <li>
                        <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url( $kalon_image[0] ); ?>" alt="<?php the_title_attribute(); ?>" /></a>
                        <?php if( $slider_caption ){ ?>
                        <div class="banner-text">
                            <div class="container">
                                <div class="text">
                                    <?php kalon_category_list(); ?>
                                    <h2><?php the_title(); ?></h2>
                                    <a class="read-more" href="<?php the_permalink(); ?>"><?php echo esc_html( $slider_readmore );?></a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </li>
                    <?php } ?>
                <?php
                }
                ?>
                </ul>
            </div>
        </div>
        <?php
    }
    wp_reset_postdata();       
        
}
add_action( 'kalon_slider', 'kalon_slider_cb' );

/**
 * Function to exclude sticky post from main query
*/
function kalon_exclude_sticky_post( $query ){
    //get all sticky posts
    $stickies = get_option( 'sticky_posts' );

    if ( ! is_admin() && $query->is_home() && $query->is_main_query() && get_theme_mod( 'kalon_ed_slider' ) && $stickies ) {
        $query->set( 'post__not_in',        $stickies );
        $query->set( 'ignore_sticky_posts', true      );
    }    
}
add_filter( 'pre_get_posts', 'kalon_exclude_sticky_post' );

/**
 * Callback function for Comment List
 * 
 * @link https://codex.wordpress.org/Function_Reference/wp_list_comments 
 */
function kalon_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ( 'div' == $args['style'] ) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
?>
    <<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
    <?php if ( 'div' != $args['style'] ) : ?>
    <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
    <?php endif; ?>
    <div class="comment-author vcard">
    <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
    <?php printf( __( '<b class="fn">%s</b>', 'kalon' ), get_comment_author_link() ); ?>
    </div>
    <?php if ( $comment->comment_approved == '0' ) : ?>
        <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'kalon' ); ?></em>
        <br />
    <?php endif; ?>

    <div class="comment-metadata commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
        <?php
            /* translators: 1: date, 2: time */
            printf( __('%1$s', 'kalon' ), get_comment_date() ); ?></a><?php edit_comment_link( __( 'Edit', 
            'kalon' ), '  ', '' );
        ?>
    </div>
    
    <div class="comment-content">
       <?php comment_text(); ?>
    </div>
    
    <div class="reply">
    <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
    </div>
    
    <?php if ( 'div' != $args['style'] ) : ?>
    </div>
    <?php endif; ?>
<?php
}

/**
 * Hook to move comment text field to the bottom in WP 4.4
 * 
 * @link http://www.wpbeginner.com/wp-tutorials/how-to-move-comment-text-field-to-bottom-in-wordpress-4-4/  
 */
function kalon_move_comment_field_to_bottom( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}
add_filter( 'comment_form_fields', 'kalon_move_comment_field_to_bottom' ); 

/**
 * 
*/
function kalon_intro_header(){
    
    if( is_archive() ){ ?>
        <div class="category">
			<?php the_archive_title( '<h4 class="category-title">', '</h4>' ); ?>
		</div><!-- .category -->
    <?php
    }
    
    if( is_search() ){ 
        global $wp_query;    
    ?>
        <div class="search-section">
			<p class="page-title"><?php printf( esc_html__( 'Search Results for %s', 'kalon' ), get_search_query() ); ?>
			<span><?php printf( esc_html__( '(Showing %s Results)','kalon' ), $wp_query->found_posts ); ?></span>
			</p>
		</div><!-- .search-section -->
    <?php
    }
}
add_action( 'kalon_header', 'kalon_intro_header' );

/**
 * Custom CSS
*/
function kalon_custom_css(){
    $custom_css = get_theme_mod( 'kalon_custom_css' );
    if( !empty( $custom_css ) ){
		echo '<style type="text/css">';
		echo wp_strip_all_tags( $custom_css );
		echo '</style>';
	}
}
add_action( 'wp_head', 'kalon_custom_css', 100 );

if ( ! function_exists( 'kalon_excerpt_more' ) && ! is_admin() ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... * 
 */
function kalon_excerpt_more() {
	return ' &hellip; ';
}
add_filter( 'excerpt_more', 'kalon_excerpt_more' );
endif;

if ( ! function_exists( 'kalon_excerpt_length' ) ) :
/**
 * Changes the default 55 character in excerpt 
*/
function kalon_excerpt_length( $length ) {
	return 60;
}
add_filter( 'excerpt_length', 'kalon_excerpt_length', 999 );
endif;

/**
 * Footer Credits 
*/
function kalon_footer_credit(){
        
    $text  = '<div class="site-info"><p>';
    $text .= esc_html__( 'Copyright &copy; ', 'kalon' ) . date('Y'); 
    $text .= ' <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a> &verbar; ';
    $text .= '<a href="' . esc_url( 'http://raratheme.com/wordpress-themes/kalon/' ) .'" rel="author" target="_blank">' . esc_html__( 'Kalon by: Rara Theme', 'kalon' ) . '</a> &verbar; ';
    $text .= sprintf( esc_html__( 'Powered by: %s', 'kalon' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'kalon' ) ) .'" target="_blank">WordPress</a>' );
    $text .= '</p></div>';
    echo apply_filters( 'kalon_footer_text', $text );    
}
add_action( 'kalon_footer', 'kalon_footer_credit' );

/**
 * Return sidebar layouts for pages
*/
function kalon_sidebar_layout(){
    global $post;
    
    if( get_post_meta( $post->ID, 'kalon_sidebar_layout', true ) ){
        return get_post_meta( $post->ID, 'kalon_sidebar_layout', true );    
    }else{
        return 'right-sidebar';
    }
}