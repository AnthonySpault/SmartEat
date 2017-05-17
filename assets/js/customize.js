/*var label = $('label');
for(var i = 0; i<label.length;i++){

label[i].onclick = function () {

        this.style.opacity='0.8';


};
}*/
    var label =  $('label');
    var labelDrinksChecked = $('input:checked[name=drinks]');
    var labelDishChecked = $('input:checked[name=dish]');
    var labelDessertsChecked = $('input:checked[name=dessert]');

    var labelDrinks = $('#drinks');
    var labelDish = $('#dish');
    var labelDessert = $('#dessert');
label.click(function(){
    labelDrinks.each(function (){
        $(this).change(function(){
            labelDrinksChecked.next().addClass('label');
        });
    });
});
