//import '@empresa/css/app.scss';
import '@sel/css/single-page.scss';

import Vue from 'vue';
import router from "@router/cv";
import store from "@store/cv";
import App from './AppCv.vue'
import vuetify from "@plugins/vuetify";
import "@plugins/vuelidate"

const rootElement = document.getElementById('app');

const rootComponent = Vue.extend(App);
// noinspection JSValidateTypes
new rootComponent({
    el: rootElement,
    propsData: { ...rootElement.dataset },
    router,
    store,
    vuetify
});