import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import listadoNomina from './modules/listadoNomina';
import archivosEmpleado from "@clientes/store/modules/archivosEmpleado";

export default new Vuex.Store({
    strict: true,
    modules: {
        listadoNomina,
        archivosEmpleado,
    },
    state: {
        convenio: null,
        isAdmin: false,
        empleado: null,
    },
    mutations: {
        BOOTSTRAP(state, payload) {
            state.convenio = payload.convenio;
            state.isAdmin = payload.isAdmin;
            state.empleado = payload.empleado;
        }
    },
    getters: {
        convenio: state => {
            return state.convenio ? state.convenio.codigo : null;
        }
    },
    actions: {
        bootstrap({commit}, payload) {
            commit('BOOTSTRAP', {
                convenio: payload.convenio,
                isAdmin: payload.isAdmin,
                empleado: payload.empleado
            });
        }
    }
})