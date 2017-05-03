var placeSearch, autocomplete;
var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
};
function initAutocomplete() {
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
        {types: ['geocode']});
    autocomplete.addListener('place_changed', fillInAddress);
}
function fillInAddress() {
    var place = autocomplete.getPlace();

    for (var component in componentForm) {
        document.getElementById(component).value = '';
        document.getElementById(component).disabled = false;
    }

    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
        }
    }
}
function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
        });
    }
}
var displayadresse = false;
$('form[name=registerform]').submit(function() {
    var firstname = $('#firstname').val(),
        lastname = $('#lastname').val(),
        email = $('#registeremail').val(),
        phone = $('#phone').val(),
        password = $('#registerpassword').val(),
        passwordconfirm = $('#passwordconfirm').val(),
        streetNumber = $('#street_number').val(),
        route = $('#route').val(),
        city = $('#locality').val(),
        postalCode = $('#postal_code').val();
    $.ajax({
        type: 'post',
        url: '?action=register',
        data: {
            lastname:lastname,
            firstname:firstname,
            email:email,
            phone:phone,
            password:password,
            passwordconfirm:passwordconfirm,
            streetNumber:streetNumber,
            route:route,
            city:city,
            postalCode:postalCode
        },
        success:function(response) {
            if (response != "true") {
                vNotify.error({text:response, title:'Erreur !'});
            }
            else {
                vNotify.success({text:'Votre compte à bien été enregistré. Vous pouvez désormais vous connecter.', title:'Félicitation !'});
                $('form[name=registerform]')[0].reset();
                $('#loginemail').val(email);
            }
        }
    });
    return false;
});
