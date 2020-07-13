import Vue from "vue";
import Vuex from "vuex";
import cvService from "@services/cv/cv";
import estadoCivilService from "@services/cv/estado-civil";
import estudioService from "@services/cv/estudio";
import estudioCodigoService from "@services/cv/estudio-codigo";
import estudioInstitutoService from "@services/cv/estudio-instituto";
import factorRhService from '@services/cv/factor-rh';
import generoService from "@services/cv/genero";
import grupoSanguineoService from '@services/cv/grupo-sanguineo';
import identificacionTipoService from '@services/cv/identificacion-tipo';
import nacionalidadService from "@services/cv/nacionalidad";
import nivelAcademicoService from "@services/cv/nivel-academico";
import usuarioService from "@services/usuario";
import alidation from "@store/modules/alidation";
import makeCrudModule from "@store/modules/crud";
import notifications from "@store/modules/notifications";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        alidation,
        cv: makeCrudModule({
            service: cvService
        }),
        estadoCivil: makeCrudModule({
            service: estadoCivilService
        }),
        estudio: makeCrudModule({
            service: estudioService
        }),
        estudioCodigo: makeCrudModule({
            service: estudioCodigoService
        }),
        estudioInstituto: makeCrudModule({
            service: estudioInstitutoService
        }),
        factorRh: makeCrudModule({
            service: factorRhService
        }),
        genero: makeCrudModule({
            service: generoService
        }),
        grupoSanguineo: makeCrudModule({
            service: grupoSanguineoService
        }),
        identificacionTipo: makeCrudModule({
            service: identificacionTipoService
        }),
        nacionalidad: makeCrudModule({
            service: nacionalidadService
        }),
        nivelAcademico: makeCrudModule({
            service: nivelAcademicoService
        }),
        notifications,
        usuario: makeCrudModule({
            service: usuarioService
        })
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
            item['@id'] = state.item[prop].length;
            state.item[prop].push(item);
            state.totals[prop] = state.item[prop].length;
        },
        SPLICE(state, {prop, start, item}) {
            if(typeof item !== 'undefined') {
                state.item[prop].splice(start, 1, item);
            } else {
                state.item[prop].splice(start, 1);
                state.totals[prop] = state.item[prop].length;
            }
        }
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
            barrio: 'Marly',
            celular: '3202123926',
            direccion: 'Calle 50 13-43',
            email: 'guerrerojosedario@gmail.com',
            estadoCivil: '/api/estado-civil/1',
            factorRh: '/api/factor-rh/+',
            genero: '/api/genero/2',
            grupoSanguineo: '/api/grupo-sanguineo/A',
            identCiudad: '/api/ciudad/1023',
            identificacion: '101841066',
            identificacionTipo: '/api/identificacion-tipo/01',
            nacCiudad: '/api/ciudad/149',
            nacimiento: '2012-05-02',
            nacionalidad: '/api/nacionalidad/1',
            nivelAcademico: '/api/nivel-academico/08',
            primerApellido: 'Guerrero',
            primerNombre: 'Jose',
            resiCiudad: '/api/ciudad/149',
            telefono: '2123444',
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
            estudios: []
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
        totals: {
            estudios: 0
        },
        // para campos que se salen del stepper se cambie el estilo
        overflow: false
    },
});





