import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    strict: true,
    state: {
        empleado: null
    },
    mutations: {
        setEmpleado (state, empleado) {
            state.empleado = empleado
        }
    },
    actions: {
        setEmpleado ({commit}, empleado) {
            commit('setEmpleado', empleado)
        }
    }
})