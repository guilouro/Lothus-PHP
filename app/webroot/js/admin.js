$(document).ready(function(){
    $('.menu-button').click(function() {
        $('.lateral-menu').animate({
            top: parseInt($('.lateral-menu').css('top'),10) == 0 ? 40-$('.lateral-menu').outerHeight() : 0
        });
    });
});