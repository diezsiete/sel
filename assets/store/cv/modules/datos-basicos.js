import Router from "@router/fos";
import axios from 'axios';
import {required} from "vuelidate/lib/validators";

export default {
    namespaced: true,
    state: {
        cv: {
            primerNombre: {
                value: '', error: [], validations: {
                    required,
                    //minLength: minLength(4),
                }
            },
            segundoNombre: {value: '', error: []},
            primerApellido: {
                value: '', error: [], validations: {
                    required
                }
            },
            segundoApellido: {value: '', error: []}
        },
        valid: false
    },
    mutations: {

    },
    actions: {

    },
}