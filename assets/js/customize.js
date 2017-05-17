
    var label =  $('label');
    var labelDrinksChecked = $('input:checked[name=drinks]');
    var labelDishChecked = $('input:checked[name=dish]');
    var labelDessertsChecked = $('input:checked[name=dessert]');

    var labelDrinks = $('#drinks');
    var labelDish = $('#dish');
    var labelDessert = $('#dessert');

     var form = $('#formMeal');

     form.submit(function(){
        var  formValid= true;

         var drinks = $('input:checked[name=drinks]').val();
         var dish = $('input:checked[name=dish]').val();
         var dessert = $('input:checked[name=dessert]').val();
         console.log(drinks);
         console.log(dish);
         console.log(dessert);

         if(formValid){
             $.ajax({
                 type: 'post',
                 url: '?action=customize',
                 data: {
                     dish:dish,
                     drinks:drinks,
                     dessert:dessert
                 },
                 success:function(response) {
                     if (response !== "true") {
                         vNotify.error({text:response, title:'Erreur !'});
                     }
                     else {
                         vNotify.success({text:'Votre menu à bien été prit en compte.', title:'Félicitation !'});
                     }
                 }
             });
         }

         return false;

     });

