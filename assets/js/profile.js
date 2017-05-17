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
            edit: "editProfile",
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
    $('.addresses input[type=radio]').change(function(){
        $.ajax({
            url: "?action=profile",
            type: "post",
            data: {
                edit: "editProfile",
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
