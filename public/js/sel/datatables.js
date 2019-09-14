/**
 * Symfony DataTables Bundle
 * (c) Omines Internetbureau B.V. - https://omines.nl/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Niels Keurentjes <niels.keurentjes@omines.com>
 */

(function($) {
    $.fn.selInitDataTables = function (config, options) {
        return this.initDataTables(config, options).then(dt => {
            dt.on('draw.dt', function(e, settings) {
                if(settings.oInit.hasActions) {
                    $("[data-toggle='tooltip']").tooltip();
                }
            });
            return dt;
        })
    };
    // $.extend( $.fn.dataTable.defaults, {
    //     drawCallback: function( settings ) {
    //         if(settings.oInit.hasActions) {
    //             $("[data-toggle='tooltip']").tooltip();
    //         }
    //     }
    // } );
}(jQuery));
