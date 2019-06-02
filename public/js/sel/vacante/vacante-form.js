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
});