const Encore = require('@symfony/webpack-encore');
const dotenv = require('dotenv').config({ path: '.env.local' });
const CaseSensitivePathsPlugin = require('case-sensitive-paths-webpack-plugin');
const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin')
const path = require('path');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

let empresa = dotenv.parsed.EMPRESA.toLowerCase();
if(process.env.EMPRESA) {
    empresa = process.env.EMPRESA.toLowerCase();
}

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

    .setOutputPath('public/build/' + empresa.toLowerCase())
    .setPublicPath('/build/' + empresa.toLowerCase())

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

    .addEntry('hv', './assets/sel/js/vacantes/hv.js')
    .addEntry('hv-entity', './assets/sel/js/vacantes/hv-entity.js')
    .addEntry('hv-adjunto', './assets/sel/js/vacantes/hv-adjunto.js')
    .addEntry('registro-hv', './assets/sel/js/vacantes/registro-hv.js')
    .addEntry('registro-hv-entity', './assets/sel/js/vacantes/registro-hv-entity.js')

    .addEntry('cuenta', './assets/sel/js/cuenta.js')
    .addEntry('admin', './assets/sel/js/admin.js')
    .addEntry('admin-vacante-form', './assets/sel/js/page/admin/vacante/vacante-form.js')
    .addEntry('admin-hv', './assets/sel/js/page/admin/hv/admin-hv.js')
    .addEntry('admin-hv-list', './assets/sel/js/page/admin/hv/admin-hv-list.js')
    .addEntry('admin-scraper-hv-list', './assets/sel/js/admin/scraper-hv-list.js')
    .addEntry('admin-scraper-solicitud-list', './assets/sel/js/admin/scraper-solicitud-list.js')
    .addEntry('portal-clientes', './assets/sel/js/clientes/portal-clientes.js')
    .addEntry('clientes-report', './assets/sel/js/clientes/report.js')
    .addEntry('single-page', './assets/sel/js/single-page/app.js')
    .addEntry('clientes', './assets/clientes/app.js')
    .addEntry('registro', './assets/cv/registro.js')
    .addEntry('cv', './assets/cv/cv.js')


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
        {from: './assets/' + empresa.toLowerCase() + '/img', to: 'images/[path]' + destinationFilename},
        {from: './assets/sel/img', to: 'images/sel/[path]' + destinationFilename}
    ])

    .autoProvideVariables({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery'
    })

    .configureDefinePlugin(options => {
        options['process.env'].GOOGLEMAPS_KEY = JSON.stringify(dotenv.parsed["GOOGLEMAPS_KEY_" + empresa.toUpperCase()]);
    })

    /*.addRule({
        test: /\.s(c|a)ss$/,
        use: [
            'vue-style-loader',
            'css-loader',
            {
                loader: 'sass-loader',
                options: {
                    implementation: require('sass'),
                    fiber: require('fibers'),
                    indentedSyntax: true // optional
                },
            },
        ],
    })*/
    .enableVueLoader()
    .addAliases({
        //"@": path.resolve(__dirname, 'assets/sel/js/single-page'),
        "@": path.resolve(__dirname, 'assets'),
        "@clientes": path.resolve(__dirname, 'assets/clientes'),
        "@empresa": path.resolve(__dirname, 'assets/' + empresa),
        "@plugins": path.resolve(__dirname, 'assets/plugins'),
        "@router": path.resolve(__dirname, 'assets/router'),
        "@sel": path.resolve(__dirname, 'assets/sel'),
        "@store": path.resolve(__dirname, 'assets/store')
    })

    .addPlugin(new CaseSensitivePathsPlugin(), -10)
    .addPlugin(new VuetifyLoaderPlugin())


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

const config = Encore.getWebpackConfig();
// console.log(config);
// process.exit();
module.exports = config;
