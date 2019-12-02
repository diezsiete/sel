module.exports = function(encore) {
    encore
        .addEntry('servilabor', './assets/servilabor/js/servilabor.js')
        //.addEntry('servilabor-inicio', './assets/servilabor/js/inicio.js')
        .addEntry('servilabor-admin', './assets/servilabor/js/admin.js')
        .addEntry('servilabor-inicio', [
            './assets/servilabor/js/inicio.js',
            //'./assets/servilabor/js/theme/plugin/rs-plugin/js/jquery.themepunch.tools.min.js',
            './assets/servilabor/vendor/rs-plugin/js/jquery.themepunch.revolution.js',
            './assets/servilabor/vendor/rs-plugin/js/extensions/revolution.extension.slideanims.min.js',
            './assets/servilabor/vendor/rs-plugin/js/extensions/revolution.extension.actions.min',
            './assets/servilabor/vendor/rs-plugin/js/extensions/revolution.extension.layeranimation.min.js'
        ])
    ;
};