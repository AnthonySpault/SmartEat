$(function(){

    function emailValidation(email) {
        var emailRegExp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return emailRegExp.test(email);
    }
    function nameValidation(name) {

        var nameRegExp = /^[a-zA-ZàèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ]+$/;
        return nameRegExp.test(name);
    }
    var parameterFirstname = $('#parameterFirstname');
    var parameterLastname = $('#parameterLastname');
    var parameterEmail = $('#parameterEmail');
    var parameterPhone = $('#parameterPhone');

    var firstnameForm = $('#firstnameForm');
    var lastnameForm = $('#lastnameForm');
    var emailForm = $('#emailForm');
    var phoneForm = $('#phoneForm');


    parameterFirstname.click(function(){
        firstnameForm.css('display','block')

    });
    parameterLastname.click(function() {
        lastnameForm.css('display','block')
    });
    parameterEmail.click(function() {
        emailForm.css('display','block')
    });
    parameterPhone.click(function() {
        phoneForm.css('display','block')
    });




    emailForm.submit(function(){
        var $this = $(this);
        var formValid = true;

        var $email = $('#emailEditing').val();
        if (!emailValidation($email)) {
            formValid = false;
            vNotify.error({text:'Veuillez saisir un email valide.', title:'Erreur !'});
        }

        if(formValid){
            $.ajax({
                url: $this.attr('action'),
                type: $this.attr('method'),
                data: $this.serialize(),
                success: function(response) {
                    if (response !== "true") {
                        vNotify.error({text:response, title:'Erreur !'});
                    }else{
                        vNotify.success({text :'Votre email à bien été modifié', title:'Bien vu bro'});
                        $('#emailRecap').html('Email : ' + $email);
                        $('#emailEditing').val('');
                        emailForm.css('display','none');
                    }
                }
            });
        }
        return false;
    });

    firstnameForm.submit(function(){
        errorFirstname.html('');
        var formValid = true;
        var $this = $(this);

        var $firstname = $('#firstnameEditing').val();
        if (!nameValidation($firstname) ) {
            formValid = false;
            vNotify.error({text:'Veuillez saisir un prénom valide.', title:'Erreur !'});
        }
        if (formValid) {
            $.ajax({
                url: $this.attr('action'),
                type: $this.attr('method'),
                data: $this.serialize(),
                dataType: 'json',
                success: function(data) {
                    if(data.success === false) {
                        errorFirstname.html(data.errors['fields']);
                    }
                    if(data.success === true){
                        $('#firstnameRecap').html('Firstname : ' + $firstname);
                        $('#firstnameEditing').val('');
                        firstnameForm.css('display','none');
                    }
                }
            });
        }
        return false;

    });

    lastnameForm.submit(function(){
        var formValid = true;
        var $this = $(this);
        var $lastname = $('#lastnameEditing').val();
        if(!nameValidation($lastname)){
            formValid = false;
            vNotify.error({text:'Veuillez saisir un nom valide.', title:'Erreur !'});
        }
        if (formValid) {
            $.ajax({
                url: $this.attr('action'),
                type: $this.attr('method'),
                data: $this.serialize(),
                success: function(data) {
                    if(data !== 'true'){
                        vNotify.error({text:data, title:'Erreur !'});
                    }else{
                        vNotify.success({text :'Votre nom à bien été modifié', title:'Bien vu bro'});
                        $('#lastnameRecap').html('Lastname : ' + $lastname);
                        $('#lastnameEditing').val('');
                        lastnameForm.css('display','none');
                    }
                }
            });
        }
        return false;
    });

    firstnameForm.submit(function(){
        var formValid = true;
        var $this = $(this);
        var $firstname = $('#firstnameEditing').val();
        if(!nameValidation($firstname)){
            formValid = false;
            vNotify.error({text:'Veuillez saisir un prénom valide.', title:'Erreur !'});
        }
        if (formValid) {
            $.ajax({
                url: $this.attr('action'),
                type: $this.attr('method'),
                data: $this.serialize(),
                success: function(data) {
                    if(data !== 'true'){
                        vNotify.error({text:data, title:'Erreur !'});
                    }else{
                        vNotify.success({text :'Votre prénom à bien été modifié', title:'Bien vu bro'});
                        $('#firstnameRecap').html('Prénom : ' + $firstname);
                        $('#firstnameEditing').val('');
                        firstnameForm.css('display','none');
                    }
                }
            });
        }
        return false;
    });

});