import $ from 'jquery';
import 'bootstrap';

import 'datatables.net';
import 'datatables.net-bs4';
import 'datatables.net-fixedheader';
import '../../bundles/datatables/js/datatables';
import dataTableModal from './component/datatable-modal'

import '../css/datatable.scss';


const $searchInput = $('#search-dt-text');
const $searchButton = $('#search-dt-button');

$.fn.selInitDataTables = function () {
    return this.each((index, element) => {
        const $this = $(element);
        const settings = $this.data('settings');
        const options = $this.data('options');
        const searching = $this.data('searching');

        return $this.initDataTables(settings, options).then(dt => {
            dt.on('draw.dt', function (e, settings) {
                if (settings.oInit.hasActions) {
                    $("[data-toggle='tooltip']").tooltip();
                }
            });

            if (searching) {
                const search = () => dt.search($searchInput.val()).draw();
                $searchButton.click(search);
                $searchInput.on('keypress', e => e.which === 13 ? search() : null);
            }

            dt.fixedHeader.adjust();

            dataTableModal(dt);
            return dt;
        })
    });

};

$(function () {
    const $datatable = $('.datatable');
    $datatable.selInitDataTables();
});
