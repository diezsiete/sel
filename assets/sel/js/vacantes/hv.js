import $ from 'jquery';
import 'chosen-js';
import 'chosen-js/chosen.css'
import 'jquery.maskedinput';

import './../component/datepicker';
import './../component/chosen-pais-dpto-ciudad'


$(function () {
    $(".chosen").chosen();

    $('[data-plugin-datepicker]').each(function () {
        var $this = $(this),
            opts = {};

        var pluginOptions = $this.data('plugin-options');
        if (pluginOptions)
            opts = pluginOptions;

        $this.themePluginDatePicker(opts);
    });
});