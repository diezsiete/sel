import $ from 'jquery';
import 'chosen-js';
import 'chosen-js/chosen.css'
import 'jquery.maskedinput';
import './../component/datepicker';
import './../component/chosen-pais-dpto-ciudad';
import './../datatable';

import HvTable from './hv-table';

$(function () {
    const $datatable = $('#datatable');
    const settings = $datatable.data('settings');

    $datatable.initDataTables(settings).then(dt => {
        const $wrapper = $('#modalForm');
        new HvTable($wrapper, $('#modalBasic'), dt);
    });

    $('[data-plugin-datepicker]').each(function () {
        const $this = $(this);
        let opts = {};
        const pluginOptions = $this.data('plugin-options');
        if (pluginOptions)
            opts = pluginOptions;

        $this.themePluginDatePicker(opts);
    });
});