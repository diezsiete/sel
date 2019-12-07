const Encore = require('@symfony/webpack-encore');
const dotenv = require('dotenv').config({ path: '.env.local' });

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

const empresa = dotenv.parsed.EMPRESA.toLowerCase();

const assetDestinationProd = '[name].[hash:8].[ext]';
const assetDestinationDev = '[name].[ext]';
const destinationFilename = Encore.isProduction() ? assetDestinationProd : assetDestinationDev;

Encore
// directory where compiled assets will be stored
    //.setOutputPath('public/build/')
    // public path used by the web server to access the output path
    //.setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    .setOutputPath('public/build/' + empresa)
    .setPublicPath('/build/' + empresa)

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('datatable', './assets/sel/js/datatable.js')
    .addStyleEntry('induccion', './assets/sel/css/evaluacion/induccion.scss')
    .addStyleEntry('induccion-titulo', './assets/sel/css/evaluacion/induccion-titulo.scss')
    .addEntry('hv', './assets/sel/js/hv.js')
    .addEntry('hv-entity', './assets/sel/js/hv-entity.js')
    .addEntry('hv-adjunto', './assets/sel/js/hv-adjunto.js')
    .addEntry('cuenta', './assets/sel/js/cuenta.js')
    .addEntry('admin', './assets/sel/js/admin.js')
    .addEntry('admin-vacante-form', './assets/sel/js/page/admin/vacante/vacante-form.js')
    .addEntry('admin-hv', './assets/sel/js/page/admin/hv/admin-hv.js')
    .addEntry('admin-hv-list', './assets/sel/js/page/admin/hv/admin-hv-list.js')
    .addEntry('admin-scraper-hv-list', './assets/sel/js/admin/scraper-hv-list.js')

    //.addEntry('page1', './assets/js/page1.js')
    //.addEntry('page2', './assets/js/page2.js')


    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()
    //.disableSingleRuntimeChunk()
    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables @babel/preset-env polyfills
    .configureBabel(() => {
    }, {
        useBuiltIns: 'usage',
        corejs: 3
    })

    // enables Sass/SCSS support
    .enableSassLoader()
    .enablePostCssLoader()

    .copyFiles([
        {from: './assets/' + empresa + '/img', to: 'images/[path]' + destinationFilename},
        {from: './assets/sel/img', to: 'images/sel/[path]' + destinationFilename}
    ])

    .autoProvideVariables({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery'
    })

    .configureDefinePlugin(options => {
        options['process.env'].GOOGLEMAPS_KEY = JSON.stringify(dotenv.parsed["GOOGLEMAPS_KEY_" + empresa.toUpperCase()]);
    });

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()

    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/js/admin.js')
;

require(`./assets/${empresa}/webpack.config`)(Encore);

module.exports = Encore.getWebpackConfig();
