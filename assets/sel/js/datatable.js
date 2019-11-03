import jQuery from 'jquery';
import 'bootstrap';
import 'popper.js';
import 'datatables.net';
import 'datatables.net-bs4';
import 'datatables.net-fixedheader';
import '../../bundles/datatables/js/datatables';

import '../css/datatable.scss';

(function($) {
    $.fn.selInitDataTables = function (config, options) {
        return this.initDataTables(config, options).then(dt => {
            dt.on('draw.dt', function (e, settings) {
                if (settings.oInit.hasActions) {
                    $("[data-toggle='tooltip']").tooltip();
                }
            });
            return dt;
        })
    };

    $(function () {

        const $datatable = $('#datatable');
        const $searchInput = $('#search-dt-text');
        const $searchButton = $('#search-dt-button');
        const settings = $datatable.data('settings');
        const options = $datatable.data('options');
        const searching = $datatable.data('searching');

        $datatable
            .selInitDataTables(settings, options).then(dt => {
            if (searching) {
                const search = () => dt.search($searchInput.val()).draw();
                $searchButton.click(search);
                $searchInput.on('keypress', e => e.which === 13 ? search() : null);
            }
            dt.fixedHeader.adjust();
        });
    });
}(jQuery));