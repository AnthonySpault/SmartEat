
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

    $('#registeremail').keyup(function () {
        if (emailValidation(this.value) === false) {
            $(this).css({
                borderColor: 'red'
            });
        } else {
            $(this).css({
                borderColor: 'green'
            });
        }
    });
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
        postalCode = $('#postal_code').val(),
        formValid = true;

        if(!emailValidation(email)){
            formValid = false;
            vNotify.error({text:'Email non conforme.', title:'Erreur !'});
        }
    if (!nameValidation(firstname) ) {
        formValid = false;
        vNotify.error({text:'Nom non conforme.', title:'Erreur !'});

    }
    if(!nameValidation(lastname)){
        formValid = false;
        vNotify.error({text:'Prénom non conforme.', title:'Erreur !'});
    }
    if (!phoneValidation(phone)) {
        formValid = false;
        vNotify.error({text:'Téléphone non conforme.', title:'Erreur !'});
    }
    if (password.length < 8) {
        formValid = false;
        vNotify.error({text:'Mot de passe non conforme.', title:'Erreur !'});
    }
    if (passwordconfirm != password) {
        formValid = false;
        vNotify.error({text:'Entrez le même mot de passe', title:'Erreur !'});
    }

    if(formValid){


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
            postalCode:postalCode,
            defaultAddress: "true"
        },
        success:function(response) {
            if (response !== "true") {
                vNotify.error({text:response, title:'Erreur !'});
            }
            else {
                vNotify.success({text:'Votre compte à bien été enregistré. Vous pouvez désormais vous connecter.', title:'Félicitation !'});
                $('form[name=registerform]')[0].reset();
                $('#loginemail').val(email);
            }
        }
    });
    }

    return false;

});

    $('form[name=loginform]').submit(function() {
        var $this = $('this');
        var  email = $('#loginemail').val(),
            password = $('#loginpassword').val(),
            formValid = true;

        if(!emailValidation(email)){
            formValid = false;
            vNotify.error({text:'Email non conforme.', title:'Erreur !'});
        }

        if (password.length < 8) {
            formValid = false;
            vNotify.error({text:'Mot de passe non conforme.', title:'Erreur !'});
        }

        if(formValid){


            $.ajax({
                type: 'post',
                url: '?action=login',
                data: {
                    email:email,
                    password:password
                },
                success:function(response) {
                    if (response != "true") {
                        vNotify.error({text: response, title: 'Erreur !'});

                    }

                    else{
                        window.location.href='?action=profile';
                    }
                }
            });
        }

        return false;

    });
