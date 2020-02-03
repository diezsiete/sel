import $ from "jquery";
import Routing from "../router";
import './hv-entity';
import './../../css/vacantes/registro.scss'

$(function(){
    const route = Routing.generate('registro_can_next_route');
    $('#datatable').on( 'draw.dt', function () {
        $.ajax({
            url: route,
            success: data => {
                if(data.can) {
                    $("a.siguiente").removeClass('disabled');
                    $(".alerts").html('');
                } else {
                    $(".alerts").html('').append('<div class="alert alert-warning">'+data.errorMessage+'</div>');
                    $("a.siguiente").addClass('disabled');
                }
            },
            error: jqXHR => {
                $(".alerts").html('').append('<div class="alert alert-danger">Error del servidor</div>');
            }
        })
    });

    // click en vinculo que saque de proceso de registro, bota warning
    $('a:not(.modal-with-form):not(.process-step):not(#signup-button):not(.chosen-single):not(.crear-entity):not(.anterior):not(.siguiente)')
        .click(e => {
            if (!window.confirm("¿Seguro quiere salir? Perderá el progreso en su registro")) {
                e.preventDefault();
            }
        })
});