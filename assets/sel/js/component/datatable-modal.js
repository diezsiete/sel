import $ from "jquery";
import PNotify from 'pnotify/dist/es/PNotify';
import 'pnotify/dist/PNotifyBrightTheme.css';
import 'magnific-popup';
import 'magnific-popup/dist/magnific-popup.css';

PNotify.defaults.styling = 'bootstrap4';

export default function(datatable, options) {
    options = typeof options === 'undefined' ? {} : options;
    const tbody = datatable.table().body();

    const defaults = {
        type: 'inline',
        preloader: false,
        focus: '#name',
        modal: true,
        modalSelector: '#modalBasic',
        callbacks: {
            // When elemened is focused, some mobile browsers in some cases zoom in
            // It looks not nice, so we disable it:
            beforeOpen: function (e) {
                this.st.focus = $(window).width() < 700 ? false : '#name';
            },
            elementParse: item => {
                const confirm = item.el.data('confirm');
                if(confirm) {
                    $(options.modalSelector).find('.modal-confirm').data('delete-url', confirm);
                }
            },
        }
    };

    options = $.extend(defaults, options);

    options.delegate = 'a.datatable-modal';
    $(tbody).magnificPopup(options);

    // dismiss formulario
    $(document).on('click', '.modal-dismiss', () => $.magnificPopup.close());

    // confirm
    $(options.modalSelector).on('click', '.modal-confirm', e => {
        const $target = $(e.currentTarget);
        const deleteUrl = $target.data('delete-url');
        $.ajax({
            url: deleteUrl,
            success: () => {
                datatable.draw();
                PNotify.success({
                    title: 'Exito!',
                    text: "Registro borrado exitosamente"
                });
                $.magnificPopup.close();
            },
            error: jqXHR => {
                PNotify.error({
                    title: 'Error!',
                    text: jqXHR.responseText
                });
                $.magnificPopup.close();
            }
        });
    });
}