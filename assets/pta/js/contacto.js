import './theme/theme';
import './theme/animate';
import './theme/animate-init';

import Routing from './../../sel/js/router';
import $ from 'jquery'

require('./../../sel/js/component/map')('googlemaps');



function changeInnerForm(formName) {
    const url = Routing.generate('pta_contacto_inner_form', {'form_name': formName});
    $.ajax({url: url, success: html => {
        $('#contacto-inner-form').html(html)
    }})
}

$(() => {
    let currentInnerForm = 'contacto';
    $('select.asunto').on('change', e => {
        if(e.currentTarget.value === 'Solicitud servicios') {
            if(currentInnerForm !== 'solicitud-servicio') {
                currentInnerForm = 'solicitud-serivicio';
                changeInnerForm('solicitud-servicio')
            }
        } else {
            if(currentInnerForm !== 'contacto') {
                currentInnerForm = 'contacto';
                changeInnerForm('contacto')
            }
        }
    });
});
