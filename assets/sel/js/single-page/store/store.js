import Vue from 'vue';
import Vuex from 'vuex';
import Router from "../../router";
import axios from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    strict: true,
    state: {
        empleado: null,
        archivos: [],
        archivoListUrl: Router.generate('sel_admin_api_archivo_list'),
        archivoCreateUrl: Router.generate('sel_admin_api_archivo_create', {usuario: null}),
        archivoBorrarUrl: Router.generate('sel_admin_api_archivo_delete'),
        archivoVerUrl: null,
        loading: false,
        alert: null,
        archivoSelected: null,
        archivoSelectedOriginalFilename: null,
    },
    mutations: {
        SET_EMPLEADO (state, empleado) {
            state.empleado = empleado;
        },
        UPDATE_URLS(state, id) {
            state.archivoListUrl   = Router.generate('sel_admin_api_archivo_list',   {usuario: id});
            state.archivoCreateUrl = Router.generate('sel_admin_api_archivo_create', {usuario: id});
            state.archivoBorrarUrl = Router.generate('sel_admin_api_archivo_delete', {usuario: id});
        },
        SET_ARCHIVOS (state, archivos) {
            state.archivos = archivos;
        },
        PREPEND_ARCHIVO (state, archivo) {
            state.archivos.unshift(archivo);
        },
        REMOVE_ARCHIVOS_BY_IDS (state, ids) {
            state.archivos = state.archivos.filter(archivo => ids.indexOf(archivo.id) === -1)
        },
        SET_LOADING_STATUS(state, status) {
            state.loading = status;
        },
        SET_ALERT(state, alert) {
            state.alert = alert
        },
        DISABLE_ALERT(state) {
            state.alert = null
        },
        SELECT_ARCHIVO(state, archivo) {
            state.archivoSelected = archivo;
            state.archivoSelectedOriginalFilename = archivo.originalFilename;
            state.archivoVerUrl = Router.generate('sel_admin_api_archivo_view', {
                usuario: state.empleado.id,
                archivo: archivo.id
            });
        },
        UNSELECT_ARCHIVO(state) {
            state.archivoSelected = null;
            state.archivoVerUrl = null;
            state.archivoSelectedOriginalFilename = null;
        },
        END_ORIGINAL_FILENAME_EDITION(state, originalFilenameEdit) {
            state.archivoSelected.originalFilename = originalFilenameEdit;
            state.archivoSelectedOriginalFilename = originalFilenameEdit;
            state.loading = false;
        }
    },
    actions: {
        setEmpleado ({commit}, empleado) {
            commit('SET_EMPLEADO', empleado);
            commit('UPDATE_URLS', empleado.id);
            return this;
        },
        //TODO botar excepcion desde el servidor
        async fetchArchivos({ commit, state }) {
            commit('SET_LOADING_STATUS', true);
            const response = await axios.get(state.archivoListUrl);
            commit('SET_ARCHIVOS', response.data);
            commit('SET_LOADING_STATUS', false);
        },
        addArchivo ({commit}, archivos) {
            if(Array.isArray(archivos)) {
                archivos.forEach(archivo => commit('PREPEND_ARCHIVO', archivo))
            } else {
                commit('PREPEND_ARCHIVO', archivos)
            }
        },
        //TODO botar excepcion desde el servidor
        async deleteArchivos({commit, state}, ids) {
            commit('SET_LOADING_STATUS', true);
            const response = await axios.delete(state.archivoBorrarUrl,{params: {ids}});
            commit('REMOVE_ARCHIVOS_BY_IDS', ids);
            commit('SET_LOADING_STATUS', false);
        },
        enableLoading(context) {
            context.commit('DISABLE_ALERT');
            context.commit('SET_LOADING_STATUS', true)
        },
        disableLoading(context) {
            context.commit('SET_LOADING_STATUS', false)
        },
        showMessage({commit}, payload) {
            const message = typeof payload === 'object' ? payload.message : payload;
            const type = typeof payload === 'object' && payload.type ? payload.type : 'warning';
            commit('SET_ALERT', {message, type});
            setTimeout(() => {
                commit('DISABLE_ALERT');
            }, 10000);
        },
        hideMessage({commit}) {
            commit('DISABLE_ALERT');
        },
        toggleArchivo({commit, state}, archivo) {
            if(!state.archivoSelected || state.archivoSelected.id !== archivo.id) {
                commit('SELECT_ARCHIVO', archivo);
            } else {
                commit('UNSELECT_ARCHIVO')
            }
        },
        unselectArchivo({commit, state}) {
            commit('UNSELECT_ARCHIVO');
        },

        //TODO botar exception servidor
        async endOriginalFilenameEdition({commit, state}, originalFilenameEdit) {
            originalFilenameEdit = originalFilenameEdit.trim();
            if(originalFilenameEdit && originalFilenameEdit !== state.archivoSelected.originalFilename) {
                commit('SET_LOADING_STATUS', true);
                await axios.post(Router.generate('sel_admin_api_archivo_update_original_filename', {
                    usuario: state.empleado.id,
                    archivo: state.archivoSelected.id,
                    originalFilename: encodeURI(originalFilenameEdit)
                }));
                commit('END_ORIGINAL_FILENAME_EDITION', originalFilenameEdit);
            }
            return state.archivoSelected.originalFilename;
        }
    }
})