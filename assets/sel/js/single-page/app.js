import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css'

import '@/store/store'

import Archivos from './components/Archivos/Archivos';

import UsuarioSearch from './components/UsuarioSearch';
import ImageApp from './components/ImageApp';

Vue.component('archivos', Archivos);

//Vue.component('image-app', ImageApp);
//Vue.component('usuario-search', UsuarioSearch);

Vue.use(Vuetify);

const vuetify = new Vuetify({})

new Vue({
    vuetify,
}).$mount('#single-page')