import Vue from 'vue';
import UsuarioSearch from './components/UsuarioSearch';

import ImageApp from './components/ImageApp';

Vue.component('image-app', ImageApp);
Vue.component('usuario-search', UsuarioSearch);



const app = new Vue({
    el: '#single-page'
});