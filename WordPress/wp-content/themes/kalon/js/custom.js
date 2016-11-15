jQuery(document).ready(function($){
    
    $('.header-top .main-navigation').meanmenu({
    	meanScreenWidth: 767,
    	meanRevealPosition: "left"
    });
    
    /** Variables from Customizer for Slider settings */
    if( kalon_data.auto == '1' ){
        var slider_auto = true;
    }else{
        slider_auto = false;
    }
    
    if( kalon_data.loop == '1' ){
        var slider_loop = true;
    }else{
        var slider_loop = false;
    }
    
    if( kalon_data.control == '1' ){
        var slider_control = true;
    }else{
        slider_control = false;
    }

    /** Home Page Slider */
    $('.flexslider').flexslider({
        slideshow: slider_auto,
        animationLoop : slider_loop,
        directionNav: slider_control,
        animation: kalon_data.animation,
        slideshowSpeed: kalon_data.speed,
        animationSpeed: kalon_data.a_speed 
    });
        
});