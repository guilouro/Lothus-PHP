$(document).ready(function(){
    $('.botao-menu').click(function() {
        $('.menu').animate({
            left: parseInt($('.menu').css('left'),10) == 0 ? -$('.menu').outerWidth() : 0
        });
    });
}