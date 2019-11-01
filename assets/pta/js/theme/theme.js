// Theme
window.theme = {};

// Theme Common Functions
window.theme.fn = {

    getOptions: function(opts) {

        if (typeof(opts) == 'object') {

            return opts;

        } else if (typeof(opts) == 'string') {

            try {
                return JSON.parse(opts.replace(/'/g,'"').replace(';',''));
            } catch(e) {
                return {};
            }

        } else {

            return {};

        }

    }

};