(function($) {
    $(".chosen").chosen();

    $(".pais").change(function() {
        $pais = $(this);
        var $paisId = $pais.attr('id');
        var paisId = $pais.val();

        $dpto = $('#' + $paisId.replace('Pais', 'Dpto'));
        $ciudad = $('#' + $paisId.replace('Pais', 'Ciudad'));
        cleanChosen($dpto);
        cleanChosen($ciudad);

        if(paisId) {
            var route = Routing.generate('hv_json_dpto', {pais: paisId});
            $.ajax({
                url: route,
                success: function (dptos) {
                    updateChosen($dpto, dptos);
                }
            });
        }
    });
    $(".dpto").change(function() {
        $dpto = $(this);
        var $dptoId = $dpto.attr('id');
        var dptoId = $dpto.val();

        $ciudad = $('#' + $dptoId.replace('Dpto', 'Ciudad'));
        cleanChosen($ciudad);

        if(dptoId) {
            var route = Routing.generate('hv_json_ciudad', {dpto: dptoId});
            $.ajax({
                url: route,
                success: function (ciudades) {
                    updateChosen($ciudad, ciudades);
                }
            });
        }
    });

    $('[data-plugin-datepicker]').each(function() {
        var $this = $( this ),
            opts = {};

        var pluginOptions = $this.data('plugin-options');
        if (pluginOptions)
            opts = pluginOptions;

        $this.themePluginDatePicker(opts);
    });

}).apply(this, [jQuery]);

function cleanChosen($select) {
    $select.html("<option>Seleccione...</option>");
    $select.attr("disabled", true);
    $select.trigger("chosen:updated");
}

function updateChosen($select, objects) {
    if(objects.length > 0) {
        for(object of objects) {
            $select.append('<option value="'+object.id+'">'+object.nombre+'</option>');
        }
        $select.removeAttr('disabled');
        $select.trigger("chosen:updated");
    }
}