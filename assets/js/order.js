function updateInput(which) {
    if (which == "billing") {
        var element = $('select#billingaddress option:selected'),
            street = element.attr("data-street"),
            zip = element.attr("data-zip"),
            city = element.attr("data-city"),
            firstname = element.attr("data-firstname"),
            lastname = element.attr("data-lastname"),
            phone = element.attr("data-phone");
        $('input[name=billingstreet]').val(street);
        $('input[name=billingzip]').val(zip);
        $('input[name=billingcity]').val(city);
        $('input[name=billingfirstname]').val(firstname);
        $('input[name=billinglastname]').val(lastname);
        $('input[name=billingphone]').val(phone);
    }
    else if (which == "shipping") {
        var element = $('select#shippingaddress option:selected'),
            street = element.attr("data-street"),
            zip = element.attr("data-zip"),
            city = element.attr("data-city"),
            firstname = element.attr("data-firstname"),
            lastname = element.attr("data-lastname"),
            phone = element.attr("data-phone");
        $('input[name=shippingstreet]').val(street);
        $('input[name=shippingzip]').val(zip);
        $('input[name=shippingcity]').val(city);
        $('input[name=shippingfirstname]').val(firstname);
        $('input[name=shippinglastname]').val(lastname);
        $('input[name=shippingphone]').val(phone);
    }
}

function validateStep1() {
    $.ajax({
        type: 'post',
        url: '?action=order',
        data: {
            kind:"step1",
            billing:$('select#billingaddress').val(),
            shipping:$('select#shippingaddress').val(),
        },
        success:function(response) {
            if (response != "true") {
                vNotify.error({text: response, title: 'Erreur !'});
            }
            else {
                location.reload();
            }
        }
    });
}

$(document).ready( function() {
    $('.step').each(function(index, element) {
        // element == this
        $(element).not('.active').addClass('done');
        $('.done').html('<i class="icon-ok"></i>');
        if($(this).is('.active')) {
          return false;
        }
    });

    $("#billingaddress").change(updateInput("billing"));
    $("#shippingaddress").change(updateInput("shipping"));

});
