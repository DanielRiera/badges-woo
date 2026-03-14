jQuery(document).ready(function($){
    //Flex Gallery
    $(".flex-viewport").ready(function(){

        if($(".flex-viewport").length == 0) { return; }

        setTimeout(function(){
            var flexBoxW = $(".flex-viewport .flex-active-slide").first().width();
            $(".master-badge-container").css({
                position: 'sticky',
                zIndex: 1,
                height: flexBoxW+'px',
                pointerEvents: 'none'
            });
            $(".master-badge-container").appendTo(".flex-viewport");
        },100);
    });
    
    $(".woocommerce-mini-cart .badge-overlay").remove();
    $(".woocommerce-mini-cart .badge-position").removeAttr("style");
    $("figure.woocommerce-product-gallery__wrapper").ready(function(){
        $(".master-badge-container").appendTo(".woocommerce-product-gallery");
    });
});
