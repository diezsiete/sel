import '@sel/css/app.scss';
import '@empresa/css/app.scss';

import Vue from 'vue';
import App from './AppRegistro.vue'
import vuetify from "@plugins/vuetify";
import "@plugins/vuelidate"

import store from "@store/cv/registro";


const rootElement = document.getElementById('app');

const rootComponent = Vue.extend(App);
// noinspection JSValidateTypes
new rootComponent({
    el: rootElement,
    store,
    vuetify
});