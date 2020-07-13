import Vue from 'vue';
export default () => ({
    namespaced: true,
    getters: {
        id: state => modelExpression => {
            return state.fields[modelExpression];
        },
        modelExpressions: state => Object.keys(state.fields)
    },
    mutations: {
        ADD: (state, {modelExpression, id}) => Vue.set(state.fields, modelExpression, id)
    },
    state: {
        fields: {}
    }
})