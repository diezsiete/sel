import '../../sel/css/base.scss';
import '../css/admin.scss';
import themeVars from './theme-vars';
import '../css/theme/elements/_magnific-popup.scss';

import $ from 'jquery';
import 'magnific-popup';
import 'magnific-popup/dist/magnific-popup.css';

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

