import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import listadoNomina from './modules/listadoNomina'

export default new Vuex.Store({
    strict: true,
    modules: {
        listadoNomina
    },
    state: {
        convenio: 'ALMLAN'
    },
    mutations: {

    },
    actions: {

    }
})