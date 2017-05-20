function removetocart(id) {
    $.ajax({
        type: 'post',
        url: '?action=editcart',
        async: true,
        data: {
            kind:"remove",
            productId:id
        },
        success:function(response) {
            $(".cart").html(response);
        }
    });
}
function removeone(id) {
    $.ajax({
        type: 'post',
        url: '?action=editcart',
        async: true,
        data: {
            kind:'removeone',
            productId:id
        },
        success:function(response) {
            $(".cart").html(response);
        }
    });
}
function addone(id) {
    $.ajax({
        type: 'post',
        url: '?action=editcart',
        async: true,
        data: {
            kind:"addone",
            productId:id
        },
        success:function(response) {
            $(".cart").html(response);
        }
    });
}
