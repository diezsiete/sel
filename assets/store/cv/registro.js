import Vue from "vue";
import Vuex from "vuex";
import {getField, updateField} from 'vuex-map-fields'
import areaService from "@services/cv/area";
import cvService from "@services/cv/cv";
import estadoCivilService from "@services/cv/estado-civil";
import estudioService from "@services/cv/estudio";
import estudioCodigoService from "@services/cv/estudio-codigo";
import estudioInstitutoService from "@services/cv/estudio-instituto";
import experienciaService from "@services/cv/experiencia";
import experienciaDuracionService from '@services/cv/experiencia-duracion';
import factorRhService from '@services/cv/factor-rh';
import familiarService from '@services/cv/familiar';
import generoService from "@services/cv/genero";
import grupoSanguineoService from '@services/cv/grupo-sanguineo';
import identificacionTipoService from '@services/cv/identificacion-tipo';
import nacionalidadService from "@services/cv/nacionalidad";
import nivelAcademicoService from "@services/cv/nivel-academico";
import ocupacionService from "@services/cv/ocuapcion";
import parentescoService from "@services/cv/parentesco";
import referenciaService from "@services/cv/referencia";
import referenciaTipoService from "@services/cv/referencia-tipo";
import usuarioService from "@services/usuario";
import makeCrudModule from "@store/modules/crud";
import notifications from "@store/modules/notifications";

import testItem from './registro-test-item';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        area: makeCrudModule({
            service: areaService
        }),
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
        experiencia: makeCrudModule({
            service: experienciaService
        }),
        experienciaDuracion: makeCrudModule({
            service: experienciaDuracionService
        }),
        familiar: makeCrudModule({
            service: familiarService
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
        ocupacion: makeCrudModule({
            service: ocupacionService
        }),
        parentesco: makeCrudModule({
            service: parentescoService
        }),
        referencia: makeCrudModule({
            service: referenciaService
        }),
        referenciaTipo: makeCrudModule({
            service: referenciaTipoService
        }),
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
        getField,
        currentComponent: state => {
            return state.steps[state.currentStep - 1].component
        }
    },
    mutations: {
        updateField,
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
            state.item[prop].forEach((item, idx) => item['@id'] = idx);
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
            component: 'ExperienciaRegistro'
        }, {
            title: 'Referencias',
            editable: false,
            component: 'ReferenciaRegistro'
        }, {
            title: 'Familiares',
            editable: false,
            component: 'FamiliarRegistro'
        }, {
            title: 'Cuenta',
            editable: false,
            component: 'CuentaRegistro'
        }],
        currentStep: 6,
        item: testItem,
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
            estudios: 1,
            experiencias: 1,
            referencias: 3,
            familiares: 0
        },
        // para campos que se salen del stepper se cambie el estilo
        overflow: false
    },
});





