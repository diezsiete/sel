import Router from "@/sel/js/single-page/router";
import axios from 'axios';

import listadoNominaEmpleado from './listadoNominaEmpleado'

export default {
    namespaced: true,
    modules: {
        empleado: listadoNominaEmpleado
    },
    state: {
        nominas: [],
        resumenes: [],
        fecha: '2020-01-31',
        totales: {}
    },
    mutations: {
        SET_NOMINAS(state, nominas) {
            state.nominas = nominas;
        },
        SET_FECHA(state, fecha) {
            state.fecha = fecha;
        },
        SET_RESUMENES(state, resumenes) {
            state.resumenes = resumenes
        },
        UPDATE_RESUMEN_TO_DETALLE(state, {identificacion, detalle}) {
            state.resumenes.forEach(stateResumen => {
                if(stateResumen.identificacion === identificacion) {
                    state.totales[stateResumen.identificacion] = {
                        devengo: stateResumen.devengo,
                        deduccion: stateResumen.deduccion,
                        netos: stateResumen.netos,
                        aportes: stateResumen.aportes,
                        bases: stateResumen.bases,
                        provisionesParafiscales: stateResumen.provisionesParafiscales
                    };
                    //TODO si detalle[x] es undefined?
                    stateResumen.devengo = detalle[0].conceptos
                    stateResumen.deduccion = detalle[1].conceptos
                    stateResumen.netos = detalle[2].conceptos
                    stateResumen.aportes = detalle[3].conceptos
                    stateResumen.bases = detalle[4].conceptos
                    stateResumen.provisionesParafiscales = detalle[5].conceptos
                }
            })
        },
        UPDATE_DETALLE_TO_RESUMEN(state, identificacion) {
            const totales = state.totales[identificacion];
            state.resumenes.forEach(stateResumen => {
                if(stateResumen.identificacion === identificacion) {
                    for(const totalKey in totales) {
                        stateResumen[totalKey] = totales[totalKey];
                    }
                }
            })
        }
    },
    actions: {
        async requestNominas({commit, rootGetters}){
            const response = await axios.get(Router.generate('sel_api_listado_nomina_fechas', {
                convenio: rootGetters.convenio
            }));
            const nominas = response.data['hydra:member'];
            commit('SET_NOMINAS', nominas)
        },
        updateFecha({commit}, fecha) {
            commit('SET_FECHA', fecha);
        },
        async requestResumenes({commit, state,  rootGetters}) {
            const response = await axios.get(Router.generate('sel_api_listado_nomina_resumen', {
                convenio: rootGetters.convenio, fecha: state.fecha
            }));
            const resumenes = response.data['hydra:member'];
            commit('SET_RESUMENES', resumenes);
        },
        async modifyResumenWithDetalle({commit, state, rootGetters}, resumen) {
            const response = await axios.get(Router.generate('sel_api_listado_nomina_detalle', {
                convenio: rootGetters.convenio, fecha: state.fecha, empleado: resumen.identificacion
            }));
            const detalle = response.data['hydra:member'];
            commit('UPDATE_RESUMEN_TO_DETALLE', {identificacion: resumen.identificacion, detalle})
        },
        modifyDetalleWithResumen({commit}, resumen) {
            commit('UPDATE_DETALLE_TO_RESUMEN', resumen.identificacion)
        }
    },
}