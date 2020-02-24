import Vue from 'vue';
import Vuex from 'vuex';
import Router from "@/router";
import axios from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    strict: true,
    state: {
        columns: [
            { field: 'empleado', title: 'Empleado'},
            { field: 'devengo', title: 'Devengo'},
            { field: 'deduccion', title: 'Deduccion' },
            { field: 'netos', title: 'Netos' },
            { field: 'aportes', title: 'Aportes empleador' },
            { field: 'bases', title: 'Bases' },
            { field: 'provisionesParafiscales', title: 'Provisiones/Parafiscales'}
        ],
        rows: [],
        convenio: 'INDMIL',
        fecha: '2020-01-31',
        loading: false
    },
    mutations: {
        SET_LOADING_STATUS(state, status) {
            state.loading = status;
        },
        SET_COLUMNS(state, naturalezas) {
            state.columns = naturalezas;
        },
        SET_ROWS(state, rows) {
            state.rows = rows
        }
    },
    actions: {
        async obtainConceptoColumns({commit, state}) {
            commit('SET_LOADING_STATUS', true);
            const response = await axios.get(Router.generate('sel_api_listado_nomina_conceptos', {
                convenio: state.convenio, fecha: state.fecha
            }));
            const naturalezas = [{field: 'Empleado'}].concat(response.data['hydra:member'].map(naturaleza => {
                return { field: naturaleza.nombre }
            }));
            commit('SET_COLUMNS', naturalezas);
            commit('SET_LOADING_STATUS', false)
        },
        async obtainResumenes({commit, state}) {
            const response = await axios.get(Router.generate('sel_api_listado_nomina_resumen', {
                convenio: state.convenio, fecha: state.fecha
            }));
            const rows = response.data['hydra:member'];
            commit('SET_ROWS', rows);
        }
    }
})