<template>
    <autocomplete
            :search="search"
            v-bind:placeholder="placeholder"
            v-bind:aria-label="placeholder"
            :get-result-value="getResultValue"
            @submit="onSubmit"
            :debounce-time="500"
            auto-select
            ref="autocompleteField"
    ></autocomplete>
</template>

<script>
    import autocompleteVue from '@trevoreyre/autocomplete-vue'
    import '@trevoreyre/autocomplete-vue/dist/style.css'
    import axios from 'axios';
    import Routing from './../../router'

    export default {
        name: "UsuarioSearch",
        components: {
            autocomplete: autocompleteVue
        },
        methods: {
            onSubmit(result) {
                if(result) {
                    this.clearInput()
                        .$emit('usuario-selected', result);
                }
            },
            clearInput() {
                this.$refs.autocompleteField.setValue("");
                this.$refs.autocompleteField.$refs.input.blur();
                return this;
            }
        },
        data: function () {
            return {
                search: input => new Promise(resolve => {
                    if (input.length >= 3) {
                        axios
                            .get(Routing.generate('sel_admin_api_search_usuario', {term: encodeURI(input)}))
                            .then(response => resolve(response.data))
                    } else {
                        resolve([])
                    }
                }),
                getResultValue(usuario) {
                    return `${usuario.nombrePrimeros} - ${usuario.identificacion}`
                },
            }
        },
        props: {
            placeholder: {
                type: String,
                default: "Buscar Usuario"
            }
        }
    }
</script>

<style scoped>

</style>