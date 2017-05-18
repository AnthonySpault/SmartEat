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

function nameValidation(name) {
    var nameRegExp = /^[a-zA-ZàèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ]+$/;
    return nameRegExp.test(name);
}

function emailValidation(email) {
    var emailRegExp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return emailRegExp.test(email);
}

function phoneValidation(phone) {
    var phoneRegExp = /(0|(\+33)|(0033))[1-9][0-9]{8}/;
    return phoneRegExp.test(phone);
}

function editProfile(element, kind) {
    if (kind == "firstname") {
        if (!nameValidation(element.innerHTML)) {
            vNotify.error({text: 'Veuillez saisir un prénom valide.', title: 'Erreur !'});
            return false;
        }
    }
    else if (kind == "lastname") {
        if (!nameValidation(element.innerHTML)) {
            vNotify.error({text: 'Veuillez saisir un nom valide.', title: 'Erreur !'});
            return false;
        }
    }
    else if (kind == "email") {
        if (!emailValidation(element.innerHTML)) {
            vNotify.error({text: 'Veuillez saisir un email valide.', title: 'Erreur !'});
            return false;
        }
    }
    else if (kind == "phone") {
        if (!phoneValidation(element.innerHTML)) {
            vNotify.error({text: 'Veuillez saisir un numéro de téléphone valide.', title: 'Erreur !'});
            return false;
        }
    }
    else {
        vNotify.error({text: 'Action interdite.', title: 'Erreur !'});
    }
    $.ajax({
        url: "?action=profile",
        type: "post",
        data: {
            action: "editProfile",
            kind: kind,
            value: element.innerHTML
        },
        success: function (data) {
            if (data !== 'true') {
                vNotify.error({text: data, title: 'Erreur !'});
            } else {
                vNotify.success({text: 'Modification effectué', title: 'Félicitation'});
            }
        }
    });
}

$(function () {
    $('.addresses input[name=defaultAddress]').change(function(){
        $.ajax({
            url: "?action=profile",
            type: "post",
            data: {
                action: "editProfile",
                kind: "changeDefaultAddress",
                value: $(this).val()
            },
            success: function (data) {
                console.log(data);
                if (data !== 'true') {
                    vNotify.error({text: data, title: 'Erreur !'});
                } else {
                    vNotify.success({text: 'Modification effectué', title: 'Félicitation'});
                }
            }
        });
    });
});
var addressForm = $('#addressForm');
console.log(addressForm);
var contentAddress = $('.contentAddress');
addressForm.submit(function () {
    var formValid = true;
    var $this = $(this);
    var $firstnameAddress = $('#firstnameAddress').val(),
        $lastnameAddress = $('#lastnameAddress').val(),
        streetNumber = $('#street_number').val(),
        phone = $('#phone').val(),
        route = $('#route').val(),
        city = $('#locality').val(),
        postalCode = $('#postal_code').val(),
        defaultAddress = $('input:checked[name=default]').val();


    if(defaultAddress === ''){
        formValid = false;
        vNotify.error({text: 'Veuillez saisir le boutton defaut.', title: 'Erreur !'});
    }


    if (!nameValidation($firstnameAddress)) {
        formValid = false;
        vNotify.error({text: 'Veuillez saisir un prénom valide.', title: 'Erreur !'});
    }
    if (!nameValidation($lastnameAddress)) {
        formValid = false;
        vNotify.error({text: 'Veuillez saisir un nom valide.', title: 'Erreur !'});
    }
    if ($firstnameAddress === '' || $lastnameAddress === '') {
        vNotify.error({text: 'Champ(s) manquant(s).', title: 'Erreur !'});
    }
    if (formValid) {
        $.ajax({
            url: $this.attr('action'),
            type: $this.attr('method'),
            data: {
                action:'addAddress',
                defaultAddress : defaultAddress,
                streetNumber: streetNumber,
                route: route,
                postalCode: postalCode,
                city: city,
                firstname:$firstnameAddress,
                lastname: $lastnameAddress,
                phone: phone
            },
            success: function (data) {
                if (data !== 'true') {
                    vNotify.error({text: data, title: 'Erreur !'});
                } else {
                    vNotify.success({text: 'Adresses bien rentré', title: 'Félicitation'});
                    contentAddress.append('<td class="contentAddresssInfo">'  + ': ' + streetNumber + ' ' +route + ' ' +
                        postalCode+ ' ' + $firstnameAddress+ ' '+ $lastnameAddress+ ' '+ phone + ' </td>');
                    addressForm[0].reset();
                }
            }
        });
    }
    return false;
});


/**
 * Created by Dam's on 18/05/2017.
 */
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("addAddress");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}