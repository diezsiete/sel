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
        loading: false,
        totales: {}
    },
    mutations: {
        SET_LOADING_STATUS(state, status) {
            state.loading = status;
        },
        SET_COLUMNS(state, naturalezas) {
            state.columns = naturalezas;
        },
        SET_RESUMENES(state, rows) {
            state.rows = rows
        },
        UPDATE_RESUMEN_TO_DETALLE(state, {identificacion, detalle}) {
            state.rows.forEach(stateResumen => {
                if(stateResumen.identificacion === identificacion) {
                    state.totales[stateResumen.identificacion] = {
                        devengo: stateResumen.devengo,
                        deduccion: stateResumen.deduccion,
                        netos: stateResumen.netos,
                        aportes: stateResumen.aportes,
                        bases: stateResumen.bases,
                        provisionesParafiscales: stateResumen.provisionesParafiscales
                    };
                    stateResumen.devengo = detalle[0].conceptos
                    stateResumen.deduccion = detalle[1].conceptos
                    stateResumen.netos = detalle[2].conceptos
                    stateResumen.aportes = detalle[3].conceptos
                    stateResumen.bases = detalle[4].conceptos
                    stateResumen.provisionesParafiscales = detalle[5].conceptos
                }
            })
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
            const resumenes = response.data['hydra:member'];
            commit('SET_RESUMENES', resumenes);
        },
        async modifyResumenWithDetalle({commit, state}, resumen) {
            const response = await axios.get(Router.generate('sel_api_listado_nomina_detalle', {
                convenio: state.convenio, fecha: state.fecha, empleado: resumen.identificacion
            }));
            const detalle = response.data['hydra:member'];
            commit('UPDATE_RESUMEN_TO_DETALLE', {identificacion: resumen.identificacion, detalle})
        }
    }
})