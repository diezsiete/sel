const themeVars = require('./js/theme-vars');
const sass = require("node-sass");
const sassUtils = require("node-sass-utils")(sass);

// tomado de https://itnext.io/sharing-variables-between-js-and-sass-using-webpack-sass-loader-713f51fa7fa0
const convertStringToSassDimension = function(result) {
    // Only attempt to convert strings
    if (typeof result !== "string") {
        return result;
    }
    const cssUnits = ["rem","em","vh","vw","vmin","vmax","ex","%","px","cm","mm","in","pt","pc","ch"];
    const parts = result.match(/[a-zA-Z]+|[0-9]+/g);
    const value = parts[0];
    const unit = parts[parts.length - 1];
    if (cssUnits.indexOf(unit) !== -1) {
        result = new sassUtils.SassDimension(parseInt(value, 10), unit);
    }
    return result;
};
const getVarsToSass = function (keys) {
    keys = keys.getValue().split(".");
    let result = themeVars;
    let i;
    for (i = 0; i < keys.length; i++) {
        result = result[keys[i]];
        if (typeof result === "string") {
            result = convertStringToSassDimension(result);
        } else if (typeof result === "object") {
            Object.keys(result).forEach(function (key) {
                const value = result[key];
                result[key] = convertStringToSassDimension(value);
            });
        }
    }
    result = sassUtils.castToSass(result);
    return result;
};

module.exports = function(encore) {
    encore.addEntry('app', './assets/pta/js/app.js')
        .addEntry('index', './assets/pta/js/index.js')
        .addEntry('servicios', './assets/pta/js/servicios.js')
        .addEntry('nosotros', './assets/pta/js/nosotros.js')
        .addEntry('blog', './assets/pta/js/blog.js')
        .addEntry('contacto', './assets/pta/js/contacto.js')
        .addEntry('admin', './assets/pta/js/admin.js')
        .addStyleEntry('induccion-pta', './assets/pta/css/evaluacion.scss')
        .enableSassLoader(options => {
            options.functions = {
                "get($keys)": getVarsToSass
            }
        })
};
