
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
                    vNotify.success({text: 'Plat bien rentré', title: 'Félicitation'});
                    platesForm[0].reset();
                }
            }
        });
    }
    return false;
});
