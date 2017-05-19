/**
 * Created by Dam's on 18/05/2017.
 */
// Get the modal
var modal = $('#myModal');

// Get the button that opens the modal
var btn = document.getElementById("addAddress");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.css('display', 'none');
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

var platesForm = $('#platesForm');
platesForm.submit(function () {
    var formValid = true;
    var $this = $(this);
    var $category =  $( "#category option:selected").val(),
        $ingredients = $('#ingredients').val(),
        $description = $('#description').val(),
        $tricks = $('#tricks').val(),
        $name = $( "#plateName").val(),
        $price = $('#price').val(),
        $image = $('#file').val();

    console.log($image);
    if ($name === '') {
        formValid = false;
        vNotify.error({text: 'Veuillez rentrer un nom.', title: 'Erreur !'});
    }

    if ($category === '' || $ingredients === '' || $tricks === '' || $price === '' || $description === '' || $image  === '') {
        vNotify.error({text: 'Champ(s) manquant(s).', title: 'Erreur !'});
    }

    var formData = new FormData(this);
    formData.append('image', $image.files);

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
});