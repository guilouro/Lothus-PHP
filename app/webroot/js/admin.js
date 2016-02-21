$(document).ready(function(){
    $('.botao-menu').click(function() {
        $('.menu-lateral').animate({
            top: parseInt($('.menu-lateral').css('top'),10) == 0 ? 50-$('.menu-lateral').outerHeight() : 0
        });
        if($('.fa').hasClass("fa-bars")){
        	$('.fa').removeClass("fa-bars");
        	$('.fa').addClass("fa-close");
        }
        else{
        	$('.fa').removeClass("fa-close");
        	$('.fa').addClass("fa-bars");
        }
    });
});