export default {
    namespaced: true,
    getters: {
        id: state => modelExpression => {
            return state.fields[modelExpression];
        },
        modelExpressions: store => Object.keys(store.fields)
    },
    mutations: {
        ADD(state, {modelExpression, id}) {
            state.fields[modelExpression] = id
        }
    },
    state: {
        fields: {}
    }
}