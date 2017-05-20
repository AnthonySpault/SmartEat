/**
 * Created by Dam's on 18/05/2017.
 */
// Get the modal
var modal = $('#myModal');
var modalEdition = $('#myModalEdition');


// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
var span2 = document.getElementsByClassName("closeEdition")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.css('display', 'none');
}
span2.onclick = function() {
    modalEdition.css('display', 'none');
}




var platesForm = $('#platesForm');
platesForm.submit(function () {
    var formValid = true;
    var $this = $(this);
    var $category =  $( "#category option:selected").val(),
        $ingredients = $('#ingredients').val(),
        $description = $('#description').val(),
        $tricks = $('#tricks').val(),
        $allergenes = $('#allergenes').val(),
        $name = $( "#plateName").val(),
        $price = $('#price').val(),
        $image = $('#file').val();

    console.log($image);
    if ($name === '') {
        formValid = false;
        vNotify.error({text: 'Veuillez rentrer un nom.', title: 'Erreur !'});
    }

    if ($category === '' || $ingredients === '' || $tricks === '' || $price === '' || $description === '' || $image  === '' || $allergenes ==='') {
        vNotify.error({text: 'Champ(s) manquant(s).', title: 'Erreur !'});
    }

    var formData = new FormData(this);
    formData.append('image', $image.files);
    console.log(formData);
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
                    printPlates();
                    modal.css('display','none');
                    platesForm[0].reset();
                }
            }
        });
    }
    return false;
});

function printPlates(){
    $.ajax({
        type: 'post',
        url: '?action=printplates',
        success:function(response) {
            $(".plates").html(response);
        }
    });
}

function deletePlates(id){
    $.ajax({
        url: "?action=admin",
        type: "post",
        data: {
            id:id
        },
        success: function (data) {
            if (data !== 'true') {
                vNotify.error({text: data, title: 'Erreur !'});
            } else {
                printPlates();
                vNotify.success({text: 'Votre adresse à bien été supprimé', title: 'Félicitation !'});
            }
        }
    });
}

$(function () {
    var check = $('.plates input[name=plate]');
    check.change(function () {
    var $this = $(this);

        if ($this.prop('checked')) {
            $.ajax({
                url: "?action=admin",
                type: "post",
                data: {
                    idStatus: $this.val(),
                    status: 'active'
                },
                success: function (data) {
                    if (data !== 'true') {
                        vNotify.error({text: data, title: 'Erreur !'});
                    } else {
                        vNotify.success({text: 'Votre produit est bien actif', title: 'Félicitation !'});
                    }
                }
            });
        } else  {
            $.ajax({
                url: "?action=admin",
                type: "post",
                data: {
                    idStatus: $this.val(),
                    status: 'inactive'
                },
                success: function (data) {
                    if (data !== 'true') {
                        vNotify.error({text: data, title: 'Erreur !'});
                    } else {
                        vNotify.success({text: 'Votre produit à bien été rendu inactif', title: 'Félicitation !'});
                    }
                }
            });
        }
    });

    var plateEditing = $('.plateEditing');
    var idEditingInput = $('.idEditing');
    var plateNameEditingInput = $('#plateNameEditing');
    var descriptionEditingInput = $('#descriptionEditing');
    var ingredientsEditingInput = $('#ingredientsEditing');
    var categoryEditingInput =  $( '#categoryEditing')
    var allergenesEditingInput = $('#allergenesEditing');
    var tricksEditingInput = $('#tricksEditing');
    var priceEditingInput = $('#priceEditing');

        plateEditing.click(function(){
            plateNameEditingInput.val($(this).parent().parent().children('.name').html());
            descriptionEditingInput.val($(this).parent().parent().children('.description').html());
            ingredientsEditingInput.val($(this).parent().parent().children('.ingredients').html());
            allergenesEditingInput.val($(this).parent().parent().children('.allergenes').html());
            tricksEditingInput.val($(this).parent().parent().children('.tricks').html());
            priceEditingInput.val($(this).parent().parent().children('.price').html());
            idEditingInput.val($(this).parent().parent().children('.id').html());
            var category = $(this).parent().parent().children('.category').html();
            categoryEditingInput .val(category).trigger('change');
        });
    var platesFormEditing = $('#platesFormEditing');
    platesFormEditing.submit(function () {

        var formValid = true;
        var $category =  categoryEditingInput.val(),
            $ingredients = ingredientsEditingInput.val(),
            $description = descriptionEditingInput.val(),
            $tricks = tricksEditingInput.val(),
            $allergenes = allergenesEditingInput.val(),
            $name = plateNameEditingInput.val(),
            $price = priceEditingInput.val(),
            $id = priceEditingInput.val();




        if ($name === '') {
            formValid = false;
            vNotify.error({text: 'Veuillez rentrer un nom.', title: 'Erreur !'});
        }

        if ($category === '' || $ingredients === '' || $tricks === '' || $price === '' || $description === ''  || $allergenes ==='') {

            vNotify.error({text: 'Champ(s) manquant(s).', title: 'Erreur !'});
        }



        if (formValid) {
            $.ajax({
                url: '?action=admin',
                type: 'post',
                data: {
                    idEditing: $id,
                    nameEditing:$name,
                    description:$description,
                    ingredients:$ingredients,
                    allergenes:$allergenes,
                    tricks:$tricks,
                    price:$price,
                    category : $category

               },
                success: function (data) {
                    if (data !== 'true') {
                        vNotify.error({text: data, title: 'Erreur !'});
                    } else {
                        printPlates();
                        vNotify.success({text: 'Votre produit à bien été éditer', title: 'Félicitation !'});
                        modalEdition.css('display','none');

                    }
                }
            });
        }
        return false;
    });



});