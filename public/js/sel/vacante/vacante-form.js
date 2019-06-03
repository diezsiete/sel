$(function(){
    $("#vacante_form_ciudad").chosen();
    $("#vacante_form_area").chosen();
    $("#vacante_form_cargo").chosen();
    $("#vacante_form_profesion").chosen();

    var $nivelSelect = $('.js-vacante-form-nivel');
    var $subnivelTarget = $('.js-subnivel-target');

    $nivelSelect.on('change', function(e) {
        $.ajax({
            url: $nivelSelect.data('subnivel-url'),
            data: {
                nivel: $nivelSelect.val()
            },
            success: function (html) {
                if (!html) {
                    $subnivelTarget.find('select').remove();
                    $subnivelTarget.addClass('d-none');
                    return;
                }
                // Replace the current field and show
                $subnivelTarget
                    .html(html)
                    .removeClass('d-none')
            }
        });
    });


    var $idiomaSelect = $('.js-vacante-form-idioma');
    var $idiomaDestrezaSelect = $('.js-vacante-form-idioma-destreza');

    $idiomaSelect.on('change', function(e){
        var idiomaVal = $idiomaSelect.val();
        console.log(idiomaVal);
        changeDisableStateSelectNotRequired($idiomaDestrezaSelect, idiomaVal);
    })
});

function changeDisableStateSelectNotRequired($select, val) {
    if(val) {
        $select.removeAttr('disabled');
        $select.find('option:first').remove();
    } else {
        $select.attr('disabled', true);
        $option = $("<option></option>");
        $select.prepend($option).val("");
    }
}