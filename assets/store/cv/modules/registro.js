import store from '@store/cv/cv';
import axios from "axios";
import Router from "@router/fos";

store.registerModule('registro',  {
    state: {
        steps: [{
            title: 'Datos básicos',
            editable: true,
            component: 'DatosBasicos'
        }, {
            title: 'Estudios',
            editable: false,
            component: 'Estudios'
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
        currentComponent: 'DatosBasicos'
    },
    mutations: {
        SET_CURRENT_STEP(state, step) {
            state.currentStep = step;
            state.currentComponent = state.steps[step - 1].component
        },
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
    },
});

export default store




