$(function(){
    var route = Routing.generate('registro_can_next_route');
    $('#datatable').on( 'draw.dt', function () {
        $.ajax({
            url: route,
            success: data => {
                if(data.can) {
                    $("a.siguiente").removeClass('disabled');
                    $(".alerts").html('');
                } else {
                    $(".alerts").html('').append('<div class="alert alert-warning">'+data.errorMessage+'</div>');
                    $("a.siguiente").addClass('disabled');
                }
            },
            error: jqXHR => {
                $(".alerts").html('').append('<div class="alert alert-danger">Error del servidor</div>');
            }
        })
    });
});