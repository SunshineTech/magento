// JavaScript Document
jQuery(document).ready(function() {
    Boxgrid();
    Mycarousel();
});

function Boxgrid() {
    jQuery('.boxgrid.caption').hover(function() {
        var pos = jQuery(this).height() + 10 - jQuery(".cover", this).height();
        jQuery(".cover", this).stop().animate({top: pos + "px"}, {queue: false, duration: 160});
    }, function() {
        jQuery(".cover", this).stop().animate({top: '135px'}, {queue: false, duration: 160});
    });
}


function Mycarousel() {
    jQuery('.jcarousel-skin-tango.horiz').jcarousel({
        auto: 2,
        scroll: 1,
        wrap: 'last',
        initCallback: mycarousel_initCallback
    });
    
    jQuery('.jcarousel-skin-tango.verti').jcarousel({
        vertical: true,
        auto: 2,
        scroll: 1,
        wrap: 'last',
        initCallback: mycarousel_initCallback
    });
}

function mycarousel_initCallback(carousel)
{
    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
}
;
