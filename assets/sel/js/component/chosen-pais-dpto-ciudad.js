import $ from "jquery";
import Routing from "../router";

const chosenPaisDptoCiudad = {
    init() {
        $(".chosen.pais").change(event => {

            const $pais = $(event.currentTarget);
            const $paisId = $pais.attr('id');
            const paisId = $pais.val();

            const $dpto = $('#' + $paisId.replace('Pais', 'Dpto').replace('pais', 'dpto'));
            const $ciudad = $('#' + $paisId.replace('Pais', 'Ciudad').replace('pais', 'ciudad'));

            this._cleanChosen($dpto)
                ._cleanChosen($ciudad);

            if (paisId) {
                $.ajax({
                    url: Routing.generate('hv_json_dpto', {pais: paisId}),
                    success: dptos => this._updateChosen($dpto, dptos)
                });
            }
        });
        $(".chosen.dpto").change(event => {
            const $dpto = $(event.currentTarget);
            const $dptoId = $dpto.attr('id');
            const dptoId = $dpto.val();

            const $ciudad = $('#' + $dptoId.replace('Dpto', 'Ciudad').replace('dpto', 'ciudad'));
            this._cleanChosen($ciudad);

            if (dptoId) {
                $.ajax({
                    url: Routing.generate('hv_json_ciudad', {dpto: dptoId}),
                    success: ciudades => this._updateChosen($ciudad, ciudades)
                });
            }
        });
    },

    _cleanChosen($select) {
        $select.html("<option>Seleccione...</option>");
        $select.attr("disabled", true);
        $select.trigger("chosen:updated");
        return this
    },

    _updateChosen($select, entities) {
        if(entities.length > 0) {
            for(const entity of entities) {
                $select.append('<option value="'+entity.id+'">'+entity.nombre+'</option>');
            }
            $select.removeAttr('disabled');
            $select.trigger("chosen:updated");
        }
    }
};

$(() => chosenPaisDptoCiudad.init());