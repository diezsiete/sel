import 'vuetify/dist/vuetify.min.css'
// import '@mdi/font/css/materialdesignicons.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import Vue from 'vue'
import Vuetify from 'vuetify/lib'


Vue.use(Vuetify);



export default new Vuetify({
    icons: {
        iconfont: 'md', // default - only for display purposes
    },
    theme: {
        dark: false,
        themes: {
            light: {
                primary: '#362a6b',
                secondary: '#2baab1',
                accent: '#c70019',
            },
            dark: {},
        },
    },
})