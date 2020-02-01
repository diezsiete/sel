import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css'
import './../../css/single-page.scss';

import '@/store/store'

import Archivos from './components/Archivos/Archivos';

Vue.component('archivos', Archivos);

Vue.use(Vuetify);

const vuetify = new Vuetify({});

new Vue({
    vuetify,
}).$mount('#single-page');