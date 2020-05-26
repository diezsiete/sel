import Vue from 'vue';
import Vuex from 'vuex';
import DatosBasicos from './modules/datos-basicos'
import Estudio from './modules/estudio'
import axios from "axios";
import Router from "@router/fos";


Vue.use(Vuex);


export default new Vuex.Store({
    strict: true,
    modules: {
        DatosBasicos,
        Estudio
    },
    state: {
        validationMessages: {
            required: 'campo obligatorio',
            minLength: 'campo debe tener al menos 15 caracteres'
        },
    },
    mutations: {
        UPDATE_CV(state, {module, field, value, index}) {
            if(typeof index !== 'undefined') {
                state[module].cv[field].value[index] = value
            } else {
                state[module].cv[field].value = value;
            }
        },
        PUSH_ERROR(state, {module, field, message}) {
            state[module].cv[field].error.push(message)
        },
        CLEAN_ERROR(state, {module, field}) {
            state[module].cv[field].error = [];
        }
    },
    actions: {
        updateCv({commit}, value) {
            commit('UPDATE_CV', value)
        },
        async fetchTableEntities({commit}, entity){
            const response = await axios.get(Router.generate('sel_api_listado_nomina_fechas', {

            }));
        },
    }
})
