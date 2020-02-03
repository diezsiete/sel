import './../../css/vacantes/registro.scss'
import './hv'
import $ from "jquery";


$(function(){
    // click en vinculo que saque de proceso de registro, bota warning
    $('a:not(.modal-with-form):not(.process-step):not(#signup-button):not(.chosen-single):not(.anterior):not(.siguiente)')
        .click(e => {
            if (!window.confirm("¿Seguro quiere salir? Perderá el progreso en su registro")) {
                e.preventDefault();
            }
        })
});