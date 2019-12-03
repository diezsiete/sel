import $ from 'jquery';
import 'chosen-js';
import 'chosen-js/chosen.css'
import 'jquery.maskedinput';
import './component/datepicker';

import './../css/registro.scss';
import './registro';
import './datatable';


import HvTable from './hv-table';


$(function () {
    const $datatable = $('#datatable');
    const settings = $datatable.data('settings');

    $datatable.initDataTables(settings).then(dt => {
        const $wrapper = $('#modalForm');
        new HvTable($wrapper, $('#modalBasic'), dt);
    });
});