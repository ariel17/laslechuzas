jQuery(document).ready(function ($) {
    
    $('.header-bottom  .main-navigation').meanmenu({
    	meanScreenWidth: 767,
    	meanRevealPosition: "center"
    });
    
    /** Variables from Customizer for Slider settings */
    if( the_minimal_data.auto == '1' ){
        var slider_auto = true;
    }else{
        slider_auto = false;
    }
    
    if( the_minimal_data.loop == '1' ){
        var slider_loop = true;
    }else{
        var slider_loop = false;
    }
    
    if( the_minimal_data.control == '1' ){
        var slider_control = true;
    }else{
        slider_control = false;
    }
    
    if( the_minimal_data.thumbnail == '1' ){
        var slider_thumb = "thumbnails";
    }else{
        var slider_thumb = false;
    }
    
    /** Home Page Slider */
    $('.flexslider').flexslider({
        slideshow: slider_auto,
        animationLoop : slider_loop,
        directionNav: slider_control,
        animation: the_minimal_data.animation,
        controlNav: slider_thumb,
        slideshowSpeed: the_minimal_data.speed,
        animationSpeed: the_minimal_data.a_speed 
    });
        
});