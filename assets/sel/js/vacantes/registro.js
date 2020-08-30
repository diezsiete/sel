import $ from "jquery";
import Routing from "../router";

const route = Routing.generate('registro_session_maintain');


function callSessionMaintain(callback) {
    $.ajax({
        url: route,
        success: () => callback ? callback() : null
    })
}

export function sessionMaintainer() {
    callSessionMaintain(() => {
        setInterval(() => callSessionMaintain(), 60000)
    })
}

/**
 * click en vinculo que saque de proceso de registro, bota warning
 */
export function clickOutsideAlert() {
    $('a:not(.modal-with-form):not(.process-step):not(#signup-button):not(.chosen-single):not(.crear-entity):not(.anterior):not(.siguiente)')
        .click(e => {
            if (!window.confirm("¿Seguro quiere salir? Perderá el progreso en su registro")) {
                e.preventDefault();
            }
        })
}