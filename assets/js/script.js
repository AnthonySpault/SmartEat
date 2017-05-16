$(document).ready(function() {
    $('#burger-toggle').click(function(){
        $(this).toggleClass('active');
        $('#mobile-nav').toggleClass('latAparece');
        $('#mobile-nav').toggleClass('latDesaparece');
        return false;
    });

});
