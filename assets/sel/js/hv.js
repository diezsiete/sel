import $ from 'jquery';
import 'chosen-js';
import 'chosen-js/chosen.css'
import 'jquery.maskedinput';
import Routing from './router';
import './component/datepicker';

import './../css/registro.scss';

$(function () {
    $(".chosen").chosen();

    $(".pais").change(function () {

        const $pais = $(this);
        const $paisId = $pais.attr('id');
        const paisId = $pais.val();

        const $dpto = $('#' + $paisId.replace('Pais', 'Dpto'));
        const $ciudad = $('#' + $paisId.replace('Pais', 'Ciudad'));
        cleanChosen($dpto);
        cleanChosen($ciudad);

        if (paisId) {
            var route = Routing.generate('hv_json_dpto', {pais: paisId});
            $.ajax({
                url: route,
                success: function (dptos) {
                    updateChosen($dpto, dptos);
                }
            });
        }
    });
    $(".dpto").change(function () {
        const $dpto = $(this);
        const $dptoId = $dpto.attr('id');
        const dptoId = $dpto.val();

        const $ciudad = $('#' + $dptoId.replace('Dpto', 'Ciudad'));
        cleanChosen($ciudad);

        if (dptoId) {
            var route = Routing.generate('hv_json_ciudad', {dpto: dptoId});
            $.ajax({
                url: route,
                success: function (ciudades) {
                    updateChosen($ciudad, ciudades);
                }
            });
        }
    });

    $('[data-plugin-datepicker]').each(function () {
        var $this = $(this),
            opts = {};

        var pluginOptions = $this.data('plugin-options');
        if (pluginOptions)
            opts = pluginOptions;

        $this.themePluginDatePicker(opts);
    });
});


function cleanChosen($select) {
    $select.html("<option>Seleccione...</option>");
    $select.attr("disabled", true);
    $select.trigger("chosen:updated");
}

function updateChosen($select, entities) {
    if(entities.length > 0) {
        for(const entity of entities) {
            $select.append('<option value="'+entity.id+'">'+entity.nombre+'</option>');
        }
        $select.removeAttr('disabled');
        $select.trigger("chosen:updated");
    }
}