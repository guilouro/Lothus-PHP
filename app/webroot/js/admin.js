$(document).ready(function(){
    $('.menu-button').click(function() {
        $('.side-menu').animate({
            top: parseInt($('.side-menu').css('top'),10) == 0 ? 40-$('.side-menu').outerHeight() : 0
        });
    });
});