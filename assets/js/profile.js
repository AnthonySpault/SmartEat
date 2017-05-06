var placeSearchEdition, autocompleteEdition;
var componentFormEdition = {
    street_numberEdition: 'short_name',
    routeEdition: 'long_name',
    localityEdition: 'long_name',
    administrative_area_level_1Edition: 'short_name',
    countryEdition: 'long_name',
    postal_codeEdition: 'short_name'
};

function fillInAddressEdition() {
    var placeEdition = autocompleteEdition.getPlace();
    for (var component in componentFormEdition) {
        document.getElementById(component).value = '';
        document.getElementById(component).disabled = false;
    }

    for (var j = 0; j < placeEdition.address_components.length; j++) {
        var addressTypeEdition = placeEdition.address_components[j].types[0];
        if (componentFormEdition[addressTypeEdition]) {
            var valEdition = placeEdition.address_components[j][componentFormEdition[addressTypeEdition]];
            document.getElementById(addressTypeEdition).value = valEdition;
        }
    }
}
function geolocateEdition() {
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
            autocompleteEdition.setBounds(circle.getBounds());
        });
    }
}

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
    autocompleteEdition = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById('autocompleteEdition')),
        {types: ['geocode']});
    autocompleteEdition.addListener('place_changed', fillInAddressEdition);
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

function emailValidation(email) {
    var emailRegExp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return emailRegExp.test(email);
}
function nameValidation(name) {

    var nameRegExp = /^[a-zA-ZàèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ]+$/;
    return nameRegExp.test(name);
}

function phoneValidation(phone) {
    var phoneRegExp = /^0\d(\s|-)?(\d{2}(\s|-)?){4}$/;
    return phoneRegExp.test(phone);
}
var parameterFirstname = $('#parameterFirstname');
var parameterLastname = $('#parameterLastname');
var parameterEmail = $('#parameterEmail');
var parameterPhone = $('#parameterPhone');

var firstnameForm = $('#firstnameForm');
var lastnameForm = $('#lastnameForm');
var emailForm = $('#emailForm');
var phoneForm = $('#phoneForm');
var addressForm = $('#addressForm');


parameterFirstname.click(function () {
    firstnameForm.css('display', 'block')

});
parameterLastname.click(function () {
    lastnameForm.css('display', 'block')
});
parameterEmail.click(function () {
    emailForm.css('display', 'block')
});
parameterPhone.click(function () {
    phoneForm.css('display', 'block')
});


emailForm.submit(function () {
    var $this = $(this);
    var formValid = true;

    var $email = $('#emailEditing').val();
    if (!emailValidation($email)) {
        formValid = false;
        vNotify.error({text: 'Veuillez saisir un email valide.', title: 'Erreur !'});
    }

    if (formValid) {
        $.ajax({
            url: $this.attr('action'),
            type: $this.attr('method'),
            data: $this.serialize(),
            success: function (response) {
                if (response !== "true") {
                    vNotify.error({text: response, title: 'Erreur !'});
                } else {
                    vNotify.success({text: 'Votre email à bien été modifié', title: 'Félicitation'});
                    $('#emailRecap').html('Email : ' + $email);
                    $('#emailEditing').val('');
                    emailForm.css('display', 'none');
                }
            }
        });
    }
    return false;
});


lastnameForm.submit(function () {
    var formValid = true;
    var $this = $(this);
    var $lastname = $('#lastnameEditing').val();
    if (!nameValidation($lastname)) {
        formValid = false;
        vNotify.error({text: 'Veuillez saisir un nom valide.', title: 'Erreur !'});
    }
    if (formValid) {
        $.ajax({
            url: $this.attr('action'),
            type: $this.attr('method'),
            data: $this.serialize(),
            success: function (data) {
                if (data !== 'true') {
                    vNotify.error({text: data, title: 'Erreur !'});
                } else {
                    vNotify.success({text: 'Votre nom à bien été modifié', title: 'Félicitation'});
                    $('#lastnameRecap').html('Lastname : ' + $lastname);
                    $('#lastnameEditing').val('');
                    lastnameForm.css('display', 'none');
                }
            }
        });
    }
    return false;
});

firstnameForm.submit(function () {
    var formValid = true;
    var $this = $(this);
    var $firstname = $('#firstnameEditing').val();
    if (!nameValidation($firstname)) {
        formValid = false;
        vNotify.error({text: 'Veuillez saisir un prénom valide.', title: 'Erreur !'});
    }
    if (formValid) {
        $.ajax({
            url: $this.attr('action'),
            type: $this.attr('method'),
            data: $this.serialize(),
            success: function (data) {
                if (data !== 'true') {
                    vNotify.error({text: data, title: 'Erreur !'});
                } else {
                    vNotify.success({text: 'Votre prénom à bien été modifié', title: 'Félicitation'});
                    $('#firstnameRecap').html('Prénom : ' + $firstname);
                    $('#firstnameEditing').val('');
                    firstnameForm.css('display', 'none');
                }
            }
        });
    }
    return false;
});
phoneForm.submit(function () {
    var formValid = true;
    var $this = $(this);
    var $phone = $('#phoneEditing').val();
    if (!phoneValidation($phone)) {
        formValid = false;
        vNotify.error({text: 'Veuillez saisir un téléphone valide.', title: 'Erreur !'});
    }
    if (formValid) {
        $.ajax({
            url: $this.attr('action'),
            type: $this.attr('method'),
            data: $this.serialize(),
            success: function (data) {
                if (data !== 'true') {
                    vNotify.error({text: data, title: 'Erreur !'});
                } else {
                    vNotify.success({text: 'Votre téléphone à bien été modifié', title: 'Félicitation'});
                    $('#phoneRecap').html('Phone: ' + $phone);
                    $('#phoneEditing').val('');
                    phoneForm.css('display', 'none');
                }
            }
        });
    }
    return false;
});
var contentAddress = $('#contentAddress');
addressForm.submit(function () {
    var formValid = true;
    var $this = $(this);
    console.log('ok');
    var $firstnameAddress = $('#firstnameAddress').val(),
        $lastnameAddress = $('#lastnameAddress').val(),
        streetNumber = $('#street_number').val(),
        phone = $('#phone').val(),
        route = $('#route').val(),
        city = $('#locality').val(),
        postalCode = $('#postal_code').val(),
        $name = $('#name ').val();


    if (!nameValidation($firstnameAddress)) {
        formValid = false;
        vNotify.error({text: 'Veuillez saisir un prénom valide.', title: 'Erreur !'});
    }
    if (!nameValidation($lastnameAddress)) {
        formValid = false;
        vNotify.error({text: 'Veuillez saisir un nom valide.', title: 'Erreur !'});
    }
    if ($firstnameAddress === '' || $lastnameAddress === '' || $name === '') {
        vNotify.error({text: 'Champ(s) manquant(s).', title: 'Erreur !'});
    }
    if (formValid) {
        $.ajax({
            url: $this.attr('action'),
            type: $this.attr('method'),
            data: {
                addressName: $name,
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
                    console.log(route);
                    vNotify.success({text: 'Adresses bien rentré', title: 'Félicitation'});
                    contentAddress.append('<div class="listAddress">' +$name + ': ' + streetNumber + ' ' +route + ' ' +
                        postalCode+ ' ' + $firstnameAddress+ ' '+ $lastnameAddress+ ' '+ phone + ' </div>');
                    addressForm[0].reset();
                }
            }
        });
    }
    return false;
});
 var addressEdition = $('#addressEdition');
addressEdition.submit(function () {
    var formValid = true;
    var $this = $(this);
    console.log('ok');
    var $firstnameAddressEdition = $('#firstnameAddressEdition').val(),
        $lastnameAddressEdition = $('#lastnameAddressEdition').val(),
        streetNumber = $('#street_numberEdition').val(),
        phone = $('#phoneAddressEdition').val(),
        route = $('#routeEdition').val(),
        city = $('#localityEdition').val(),
        postalCode = $('#postal_codeEdition').val(),
        $name = $( "#addressName option:selected").val() ;
    console.log('ok',streetNumber,route,city,postalCode);

    if (!nameValidation($firstnameAddressEdition)) {
        formValid = false;
        vNotify.error({text: 'Veuillez saisir un prénom valide.', title: 'Erreur !'});
    }
    if (!nameValidation($lastnameAddressEdition)) {
        formValid = false;
        vNotify.error({text: 'Veuillez saisir un nom valide.', title: 'Erreur !'});
    }
    if ($firstnameAddressEdition === '' || $lastnameAddressEdition === '' || $name === '') {
        vNotify.error({text: 'Champ(s) manquant(s).', title: 'Erreur !'});
    }
    if (formValid) {
        $.ajax({
            url: $this.attr('action'),
            type: $this.attr('method'),
            data: {
                addressName: $name,
                streetNumber: streetNumber,
                route: route,
                postalCode: postalCode,
                city: city,
                firstname:$firstnameAddressEdition,
                lastname: $lastnameAddressEdition,
                phone: phone
            },
            success: function (data) {
                if (data !== 'true') {
                    vNotify.error({text: data, title: 'Erreur !'});
                } else {
                    console.log($name);
                    vNotify.success({text: 'Adresses bien rentré', title: 'Félicitation'});
                    /*contentAddress.append('<div class="listAddress">' +$name + ': ' + streetNumber + ' ' +route + ' ' +
                        postalCode+ ' ' + $firstnameAddress+ ' '+ $lastnameAddress+ ' '+ phone + ' </div>');*/
                    addressForm[0].reset();
                }
            }
        });
    }
    return false;
});

var platesForm = $('#platesForm');
platesForm.submit(function () {
    var formValid = true;
    var $this = $(this);
    console.log('ok');
    var $category =  $( "#category option:selected").val(),
        $ingredients = $('#ingredients').val(),
        $description = $('#description').val(),
        $tricks = $('#tricks').val(),
        $name = $( "#plateName").val(),
        $price = $('#price').val(),
        $image = $('#image').val();

console.log($image);
    if (!nameValidation($name)) {
        formValid = false;
        vNotify.error({text: 'Veuillez saisir un prénom valide.', title: 'Erreur !'});
    }

    if ($category === '' || $ingredients === '' || $tricks === '' || $price === '' || $description === '' || $image  === '') {
        vNotify.error({text: 'Champ(s) manquant(s).', title: 'Erreur !'});
    }

    var formData = new FormData(this);
    if (formValid) {
        $.ajax({
            url: $this.attr('action'),
            type: $this.attr('method'),
            contentType: false,
            processData: false,
            data: formData,
            success: function (data) {
                if (data !== 'true') {
                    vNotify.error({text: data, title: 'Erreur !'});
                } else {
                    vNotify.success({text: 'Plat bien rentré', title: 'Félicitation'});
                    /*contentAddress.append('<div class="listAddress">' +$name + ': ' + streetNumber + ' ' +route + ' ' +
                     postalCode+ ' ' + $firstnameAddress+ ' '+ $lastnameAddress+ ' '+ phone + ' </div>');*/
                    platesForm[0].reset();
                }
            }
        });
    }
    return false;
});



