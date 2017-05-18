function addtocart(id) {
    $.ajax({
        type: 'post',
        url: '?action=editcart',
        data: {
            kind:"add",
            productId:id,
        },
        success:function(response) {
            item_count();
        }
    });
}
function removetocart(id) {
    $.ajax({
        type: 'post',
        url: '?action=editcart',
        data: {
            kind:"remove",
            productId:id
        },
        success:function(response) {
            show_cart();
            item_count();
        }
    });
}
function removeone(id) {
    $.ajax({
        type: 'post',
        url: '?action=editcart',
        data: {
            kind:'removeone',
            productId:id
        },
        success:function(response) {
            show_cart();
        }
    });
}
function addone(id) {
    $.ajax({
        type: 'post',
        url: '?action=editcart',
        data: {
            kind:"addone",
            productId:id
        },
        success:function(response) {
            show_cart();
        }
    });
}
function show_cart() {
    $.ajax({
        type: 'post',
        url: '?action=refreshcart',
        success:function(response) {
            $(".cart").html(response);
        }
    });
}
function item_count() {
    $.ajax({
        type:'post',
        url:'?action=cartinfo',
        success:function(response) {
            $('.itemcount').html(response);
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
