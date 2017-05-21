$(function() {
    var nav = $('.itemnavmobile').offset().top,
        dessert = $('#dessert').offset().top,
        drink = $('#drink').offset().top;
    $(window).scroll(function(){
        if($(window).width() <= 1024) {
            var scroll = $(window).scrollTop();
            if(scroll > (drink - 130)){
                $('.itemnavmobile').css('position','fixed').css('top','55px');
                $('#dish').css('margin-top', '80px');
                $('.itemnavmobile .dish').css('border-color', 'rgba(255, 255, 255, 0)');
                $('.itemnavmobile .dessert').css('border-color', 'rgba(255, 255, 255, 0)');
                $('.itemnavmobile .drink').css('border-color', '#31a9cb');
            } else if(scroll > (dessert - 130)){
                $('.itemnavmobile').css('position','fixed').css('top','55px');
                $('#dish').css('margin-top', '80px');
                $('.itemnavmobile .dish').css('border-color', 'rgba(255, 255, 255, 0)');
                $('.itemnavmobile .dessert').css('border-color', '#a02427');
                $('.itemnavmobile .drink').css('border-color', 'rgba(255, 255, 255, 0)');
            } else if(scroll > (nav - 55)){
                $('.itemnavmobile').css('position','fixed').css('top','55px');
                $('#dish').css('margin-top', '80px');
                $('.itemnavmobile .dish').css('border-color', '#157063');
                $('.itemnavmobile .dessert').css('border-color', 'rgba(255, 255, 255, 0)');
                $('.itemnavmobile .drink').css('border-color', 'rgba(255, 255, 255, 0)');
            } else {
                $('.itemnavmobile').css('position','static');
                $('#dish').css('margin-top', '30px');
                $('.itemnavmobile .dish').css('border-color', 'rgba(255, 255, 255, 0)');
                $('.itemnavmobile .dessert').css('border-color', 'rgba(255, 255, 255, 0)');
                $('.itemnavmobile .drink').css('border-color', 'rgba(255, 255, 255, 0)');
            }
        }
    });
    $('.itemnavmobile a').click(function(){
        $('html, body').animate({
            scrollTop: $( $(this).attr('href') ).offset().top - 120
        }, 500);
        return false;
    });
    var form = $('#formMeal');

    form.submit(function(){
        var  formValid= true;
        var drinks = $('input:checked[name=drinks]').val();
        var dish = $('input:checked[name=dish]').val();
        var dessert = $('input:checked[name=dessert]').val();

        if (dish == undefined) {
            vNotify.error({text:'Vous devez choisir un plat dans votre menu', title:'Erreur !'});
            return false;
        }
        if (dessert == undefined) {
            vNotify.error({text:'Vous devez choisir un dessert dans votre menu', title:'Erreur !'});
            return false;
        }
        if (drinks == undefined) {
            vNotify.error({text:'Vous devez choisir une boisson dans votre menu', title:'Erreur !'});
            return false;
        }
        console.log(dish, drinks, dessert);
        $.ajax({
            type: 'post',
            url: '?action=customize',
            data: {
                dish:dish,
                drinks:drinks,
                dessert:dessert
            },
            success:function(response) {
                if (response != "true") {
                    vNotify.error({text:response, title:'Erreur !'});
                }
                else {
                    document.location.href = "?action=cart";
                }
            }
        });

        return false;

    });
});
