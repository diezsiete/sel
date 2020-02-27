import Router from "@/router";
import axios from 'axios';

export default {
    namespaced: true,
    state: {
        nominas: [],
        resumenes: [],
        totales: {}
    },
    mutations: {
        SET_RESUMENES(state, resumenes) {
            state.resumenes = resumenes
        },
        UPDATE_RESUMEN_TO_DETALLE(state, {fecha, detalle}) {
            state.resumenes.forEach(stateResumen => {
                if(stateResumen.fecha === fecha) {
                    state.totales[stateResumen.fecha] = {
                        devengo: stateResumen.devengo,
                        deduccion: stateResumen.deduccion,
                        netos: stateResumen.netos,
                        aportes: stateResumen.aportes,
                        bases: stateResumen.bases,
                        provisionesParafiscales: stateResumen.provisionesParafiscales
                    };
                    //TODO si detalle[x] es undefined?
                    stateResumen.devengo = detalle[0].conceptos;
                    stateResumen.deduccion = detalle[1].conceptos;
                    stateResumen.netos = detalle[2].conceptos;
                    stateResumen.aportes = detalle[3].conceptos;
                    stateResumen.bases = detalle[4].conceptos;
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



        async requestResumenesEmpleado({commit, rootState, rootGetters}) {
            const response = await axios.get(Router.generate('sel_api_listado_nomina_resumen_empleado', {
                convenio: rootGetters.convenio, empleado: rootState.empleado
            }));
            const resumenes = response.data['hydra:member'];
            commit('SET_RESUMENES', resumenes);
        },
        async modifyResumenWithDetalle({commit, state, rootGetters}, resumen) {
            const response = await axios.get(Router.generate('sel_api_listado_nomina_detalle', {
                convenio: rootGetters.convenio, fecha: state.fecha, empleado: resumen.identificacion
            }));
            const detalle = response.data['hydra:member'];
            commit('UPDATE_RESUMEN_TO_DETALLE', {identificacion: resumen.fecha, detalle})
        },
        modifyDetalleWithResumen({commit}, resumen) {
            commit('UPDATE_DETALLE_TO_RESUMEN', resumen.identificacion)
        }
    },
}