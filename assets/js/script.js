function addtocart(id) {
    $.ajax({
        type: 'post',
        url: '?action=editcart',
        async: true,
        data: {
            kind:"add",
            productId:id,
        },
        success:function(response) {
            document.location.href = "?action=cart";
        }
    });
}
$(document).ready(function() {
    $('#burger-toggle').click(function(){
        $(this).toggleClass('active');
        $('#mobile-nav').toggleClass('latAparece');
        $('#mobile-nav').toggleClass('latDesaparece');
        return false;
    });
});
