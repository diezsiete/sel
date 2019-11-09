import $ from 'jquery';
import 'chosen-js';
import 'chosen-js/chosen.css'

$(function(){
    $("#vacante_form_ciudad").chosen();
    $("#vacante_form_area").chosen();
    $("#vacante_form_cargo").chosen();
    $("#vacante_form_profesion").chosen();

    const $nivelSelect = $('.js-vacante-form-nivel');
    const $subnivelTarget = $('.js-subnivel-target');

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


    const $idiomaSelect = $('.js-vacante-form-idioma');
    const $idiomaDestrezaSelect = $('.js-vacante-form-idioma-destreza');

    $idiomaSelect.on('change', function(e){
        const idiomaVal = $idiomaSelect.val();
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
        const $option = $("<option></option>");
        $select.prepend($option).val("");
    }
}