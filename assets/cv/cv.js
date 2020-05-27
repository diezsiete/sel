import '@empresa/css/app.scss';

import Vue from 'vue';
import store from "@store/cv/cv";
import router from "@router/cv";
import App from './AppCv.vue'
import vuetify from "@plugins/vuetify";
import "@plugins/vuelidate"

const rootElement = document.getElementById('app');

const rootComponent = Vue.extend(App);
// noinspection JSValidateTypes
new rootComponent({
    el: rootElement,
    router,
    store,
    vuetify
});