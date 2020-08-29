import Vue from 'vue';
import Vuex from 'vuex';
import makeCrudModule from '@store/modules/crud';
import notifications from '@store/modules/notifications';
import cvService from '@services/cv/cv';
import estudioService from '@services/cv/estudio'
import estudioCodigoService from '@services/cv/estudio-codigo';
import estudioInstitutoService from '@services/cv/estudio-instituto';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        notifications,
        cv: makeCrudModule({
            service: cvService
        }),
        estudio: makeCrudModule({
            service: estudioService
        }),
        estudioCodigo: makeCrudModule({
            service: estudioCodigoService
        }),
        estudioInstituto: makeCrudModule({
            service: estudioInstitutoService
        })
    },
    state: {
        cvIri: null,
        // heredado de registro, para campos que se salen del stepper
        overflow: false
    },
    mutations: {
        BOOTSTRAP(state, payload) {
            state.cvIri = payload.cvIri;
        },
    },
    actions: {
        bootstrap({commit}, payload) {
            commit('BOOTSTRAP', payload);
        },
    }
});