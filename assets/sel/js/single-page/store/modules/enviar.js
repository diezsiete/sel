import Router from "@/sel/js/single-page/router";
import axios from 'axios';

export default {
    namespaced: true,
    state: {
        convenio: null,
        alert: null,
        loading: false,
        owners: [],
        ownersIdsSelected: [],
        ownerSelected: null
    },
    mutations: {
        SET_CONVENIO(state, convenio) {
            state.convenio = convenio;
        },
        SET_OWNERS(state, owners) {
            state.owners = owners;
        },
        SET_OWNERS_IDS(state, ownersIds) {
            state.ownersIdsSelected = ownersIds;
        },
        ADD_OWNER_ID(state, ownerId) {
            state.ownersIdsSelected.push(ownerId)
        },
        REMOVE_OWNER_ID(state, removeOwnerId) {
            state.ownersIdsSelected = state.ownersIdsSelected.filter(ownerId => ownerId !== removeOwnerId )
        }
    },

    actions: {
        setConvenio({commit}, convenio) {
            commit('SET_CONVENIO', convenio)
        },
        async fetchOwners({commit, state}) {
            // TODO loading que le pegue al global
            // commit('SET_LOADING_STATUS', true);
            const response = await axios.get(
                Router.generate('sel_admin_api_archvio_find_usuarios_by_convenio_with_archivos', {
                    codigo: state.convenio.codigo
                }));

            commit('SET_OWNERS', response.data);
            //commit('SET_LOADING_STATUS', false);
        },
        selectAllOwners({commit, state}) {
            const newOwnersIdsSelected = state.owners.map(item => item.id);
            commit('SET_OWNERS_IDS', newOwnersIdsSelected);
            return newOwnersIdsSelected
        },
        unselectAllOwners({commit}) {
            commit('SET_OWNERS_IDS', []);
        },
        toggleOwnerId({commit, state}, ownerId) {
            if(!state.ownersIdsSelected.includes(ownerId)) {
                commit('ADD_OWNER_ID', ownerId);
                return true;
            } else {
                commit('REMOVE_OWNER_ID', ownerId);
                return false;
            }
        }
    },
}