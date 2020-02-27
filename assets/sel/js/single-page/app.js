import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css'
import './../../css/single-page.scss';
import '@/store/store'
import App from './App.vue'
import router from "./router/router";


Vue.use(Vuetify);
const vuetify = new Vuetify({});

//import Archivos from './components/Archivos/Archivos';
//Vue.component('archivos', Archivos);


const app = new Vue({
    render: h => h(App),
    router,
    vuetify
}).$mount('#single-page');

// new Vue({
//     vuetify,
// }).$mount('#single-page');