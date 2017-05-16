$(function() {
    $(".rslides").responsiveSlides({
        random: true,          // Boolean: Randomize the order of the slides, true or false
        pause: true,           // Boolean: Pause on hover, true or false
    });
    var nav = $('.itemnavmobile').offset().top,
        dessert = $('#dessert').offset().top,
        drink = $('#drink').offset().top;
    $(window).scroll(function(){
        var scroll = $(window).scrollTop();
        console.log(scroll);
        if(scroll > (drink - 130)){
            $('.itemnavmobile').css('position','fixed').css('top','55px');
            $('#dish').css('margin-top', '80px');
            $('.itemnavmobile .dish').css('border-color', 'rgba(255, 255, 255, 0)');
            $('.itemnavmobile .dessert').css('border-color', 'rgba(255, 255, 255, 0)');
            $('.itemnavmobile .drink').css('border-color', '#31a9cb');
        } else if(scroll > (dessert - 130)){
            $('.itemnavmobile').css('position','fixed').css('top','55px');
            $('#dish').css('margin-top', '80px');
            $('.itemnavmobile .dish').css('border-color', 'rgba(255, 255, 255, 0)');
            $('.itemnavmobile .dessert').css('border-color', '#a02427');
            $('.itemnavmobile .drink').css('border-color', 'rgba(255, 255, 255, 0)');
        } else if(scroll > (nav - 55)){
            $('.itemnavmobile').css('position','fixed').css('top','55px');
            $('#dish').css('margin-top', '80px');
            $('.itemnavmobile .dish').css('border-color', '#157063');
            $('.itemnavmobile .dessert').css('border-color', 'rgba(255, 255, 255, 0)');
            $('.itemnavmobile .drink').css('border-color', 'rgba(255, 255, 255, 0)');
        } else {
            $('.itemnavmobile').css('position','static');
            $('#dish').css('margin-top', '30px');
            $('.itemnavmobile .dish').css('border-color', 'rgba(255, 255, 255, 0)');
            $('.itemnavmobile .dessert').css('border-color', 'rgba(255, 255, 255, 0)');
            $('.itemnavmobile .drink').css('border-color', 'rgba(255, 255, 255, 0)');
        }
    });
    $('.itemnavmobile a').click(function(){
        $('html, body').animate({
            scrollTop: $( $(this).attr('href') ).offset().top - 120
        }, 500);
        return false;
    });
});
