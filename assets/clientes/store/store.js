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
        convenio: null,
        isAdmin: false
    },
    mutations: {
        SET_CONVENIO(state, convenio) {
            state.convenio = convenio;
        },
        SET_IS_ADMIN(state, isAdmin) {
            state.isAdmin = isAdmin;
        }
    },
    getters: {
        convenio: state => {
            return state.convenio ? state.convenio.codigo : null;
        }
    },
    actions: {
        bootstrap({commit}, payload) {
            commit('SET_CONVENIO', payload.convenio);
            commit('SET_IS_ADMIN', payload.isAdmin);
        }
    }
})