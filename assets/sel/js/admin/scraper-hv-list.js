import $ from 'jquery'
import Routing from './../router';
import './../../css/admin/scraper-hv-list.scss'

$(function () {
    $('.datatable').on( 'draw.dt', e => {
        const $tbody = $(e.target).find('tbody');
        $tbody.on('mfpOpen', function(e) {
            $.magnificPopup.instance.content.find('.salidas.console-view').html("");
            const $el = $.magnificPopup.instance.currItem.el;
            const route = Routing.generate('admin_scraper_message_log', { queue: $el.data('queue') , id: $el.data('id') });
            $.get(route, response => {
                $.magnificPopup.instance.content.find('.salidas.console-view').html(response.log.replace(/(?:\r\n|\r|\n)/g, '<br>'))
            })

        });
    });
});