$(function(){
    $("#modal-borrar").on('show.bs.modal', e => {
        const path = $(e.relatedTarget).data('path');
        $(this).find('a').attr('href', path);
    })
});