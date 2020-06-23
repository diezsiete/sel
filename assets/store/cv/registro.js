import Vue from "vue";
import Vuex from "vuex";
import notifications from "@store/modules/notifications";
import makeCrudModule from "@store/modules/crud";
import cvService from "@services/cv/cv";
import estudioService from "@services/cv/estudio";
import estudioCodigoService from "@services/cv/estudio-codigo";
import estudioInstitutoService from "@services/cv/estudio-instituto";

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
        steps: [{
            title: 'Datos básicos',
            editable: true,
            component: 'CvRegistro'
        }, {
            title: 'Estudios',
            editable: false,
            component: 'EstudioRegistro'
        }, {
            title: 'Experiencia',
            editable: false,
            component: 'Experiencia'
        }, {
            title: 'Referencias',
            editable: false,
            component: 'Referencia'
        }, {
            title: 'Familiares',
            editable: false,
            component: 'Familiares'
        }, {
            title: 'Cuenta',
            editable: false,
            component: 'Cuenta'
        }],
        currentStep: 1,
        item: {
            /*estudios: [
                {
                    '@id': 1,
                    codigo: {
                        '@id' : '/api/estudio-codigo/00001',
                        'id' : '00001',
                        nombre: 'ACTIVIDAD FISICA Y DEPORTE'
                    },
                    instituto: {
                        '@id': '/api/estudio-instituto/000001',
                        'id': '000002',
                        'nombre': 'CENTRO EDUCACIONAL DE COMPUTOS Y SISTEMAS-CEDESIST'
                    },
                    nombre: 'asdasdasd'
                }
            ]*/
            estudios: {
                items: [],
                total: 0
            }
        },
        isLoading: false,
        registroToolbar: {
            next: true,
            prev: false,
            add: false,
            cancel: false,
            save: false,
            addText: 'Agregar'
        },
        // para campos que se salen del stepper se cambie el estilo
        overflow: false
    },
    getters: {
        currentComponent: state => {
            return state.steps[state.currentStep - 1].component
        }
    },
    mutations: {
        SET_CURRENT_STEP(state, step) {
            state.currentStep = step;
            state.registroToolbar.prev = step > 1;
        },
        SET_TOOLBAR(state, toolbar) {
            Object.assign(state.registroToolbar, toolbar);
        },
        SET_CREATED: (state, item) => {
            Object.assign(state, {item});
        },
        PUSH(state, {prop, item}) {
            item['@id'] = state.item[prop].items.length;
            state.item[prop].items.push(item);
            state.item[prop].total = state.item[prop].items.length;
        },
        SPLICE(state, {prop, start, item}) {
            if(typeof item !== 'undefined') {
                state.item[prop].items.splice(start, 1, item);
            } else {
                state.item[prop].items.splice(start, 1);
                state.item[prop].total = state.item[prop].items.length;
            }
        }
    },
    actions: {
        currentStepAugment({commit, state}) {
            commit('SET_CURRENT_STEP', state.currentStep + 1)
        },
        currentStepDecrease({commit, state}) {
            commit('SET_CURRENT_STEP', state.currentStep - 1)
        },
        setCurrentStep({commit}, step) {
            commit('SET_CURRENT_STEP', step)
        },
        buildEmptyTableEntities({commit}) {
            console.log('REGISTRO buildEmptyTableEntities')
            //commit('UPDATE_CV', value)
        },
        create: ({commit}, values) => {
            commit('SET_CREATED', values);
        },
    },
});





