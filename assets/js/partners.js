
function emailValidation(email) {
    var emailRegExp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return emailRegExp.test(email);
}
function nameValidation(name) {

    var nameRegExp = /^[a-zA\s*-ZàèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ-]+$/;
    return nameRegExp.test(name);
}
function phoneValidation(phone) {
    var phoneRegExp = /^0\d(\s|-)?(\d{2}(\s|-)?){4}$/;
    return phoneRegExp.test(phone);
}
var partnersForm = $('#partnersForm');

partnersForm.submit(function() {

    var  $email = $('#email').val(),
        $phone=$('#phone').val(),
        $firstname = $('#firstname').val(),
        $lastname = $('#lastname').val(),
        formValid = true;
    if(!emailValidation($email)){
        formValid = false;
        vNotify.error({text:'Email non conforme.', title:'Erreur !'});
    }
    if(!phoneValidation($phone)){
        formValid = false;
        vNotify.error({text:'Téléphone non conforme.', title:'Erreur !'});
    }

    if(!nameValidation($firstname)){
        formValid = false;
        vNotify.error({text:'Prénom non conforme.', title:'Erreur !'});
    }
    if(!nameValidation($lastname)){
        formValid = false;
        vNotify.error({text:'Nom non conforme.', title:'Erreur !'});
    }

    if(formValid){


        $.ajax({
            type: 'post',
            url: '?action=partners',
            data: {
                email:$email,
                phone:$phone,
                firstname:$firstname,
                lastname:$lastname
            },
            success:function(response) {
                if (response != "true") {
                    vNotify.error({text: response, title: 'Erreur !'});
                }
                else{
                        vNotify.success({text:'Votre demande à bien été envoyé aux serveur', title: 'Féliciation !'});
                      partnersForm[0].reset();

                }
            }
        });
    }

    return false;

});