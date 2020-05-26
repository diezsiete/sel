import {required} from "vuelidate/lib/validators";

export default {
    namespaced: true,
    state: {
        cv: {
            nombre: {value: [], error: [], label: 'Título', validations: {
                required,
            }}
        },
        valid: false
    },
    mutations: {

    },
    actions: {

    },
}