(function(window, $) {
    window.modalBorrar = function () {
        $("#modal-borrar").on('show.bs.modal', e => {
            alert("OK");
            /*const path = $(e.relatedTarget).data('path');
            $(this).find('a').attr('href', path);*/
        })
    };

    /*$.extend(window.modalBorrar.prototype, {

    });*/

})(window, jQuery);