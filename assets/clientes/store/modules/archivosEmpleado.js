import Router from "@/../router";
import axios from 'axios';

export default {
    namespaced: true,
    state: {
        archivos: [],
        archivoListUrl: Router.generate('sel_admin_api_archivo_list'),
        archivoVerUrl: null,
        archivoSelected: null,
        archivoSelectedOriginalFilename: null
    },
    mutations: {
        UPDATE_URLS(state, id) {
            state.archivoListUrl   = Router.generate('sel_admin_api_archivo_list', {usuario: id});
        },
        SET_ARCHIVOS (state, archivos) {
            state.archivos = archivos;
        },
        SELECT_ARCHIVO(state, {archivo, usuario}) {
            state.archivoSelected = archivo;
            state.archivoSelectedOriginalFilename = archivo.originalFilename;
            state.archivoVerUrl = Router.generate('sel_admin_api_archivo_view', {
                usuario: usuario,
                archivo: archivo.id
            });
        },
        UNSELECT_ARCHIVO(state) {
            state.archivoSelected = null;
            state.archivoVerUrl = null;
            state.archivoSelectedOriginalFilename = null;
        },
    },
    actions: {
        async fetchArchivos({ commit, state, rootState }) {
            commit('UPDATE_URLS', rootState.empleado.usuario.id);
            //commit('SET_LOADING_STATUS', true);
            const response = await axios.get(state.archivoListUrl);
            commit('SET_ARCHIVOS', response.data);
            //commit('SET_LOADING_STATUS', false);
        },
        toggleArchivo({commit, state, rootState}, archivo) {
            if(!state.archivoSelected || state.archivoSelected.id !== archivo.id) {
                commit('SELECT_ARCHIVO', {archivo, usuario: rootState.empleado.usuario.id});
            } else {
                commit('UNSELECT_ARCHIVO')
            }
        },
        unselectArchivo({commit, state}) {
            commit('UNSELECT_ARCHIVO');
        },
    },
}