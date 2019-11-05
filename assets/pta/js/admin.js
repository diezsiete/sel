import '../../sel/css/base.scss';
import '../css/admin.scss';
import themeVars from './theme-vars';

import $ from 'jquery';

$(function () {
    // en navegador en sel el menu secundario se muestra abierto.
    if (window.matchMedia('(min-width: '+themeVars.menuMinWidth+')').matches) {
        const $body = $('body');
        if($body.hasClass('is-sel')) {
            $body.addClass('pp-header-open');
            $('#body').addClass('menu-open');
        }
    }
});

