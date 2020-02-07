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
    >
        <template #result="{ result, props }">
            <li v-bind="props" v-if="typeof result === 'object'">
                {{ getResultValue(result) }}
            </li>
            <li v-else class="autocomplete-no-result red lighten-4" @click="importNoResult(result)">
                <small>Empleado no encontrado</small>
                Importar de novasoft
            </li>
        </template>
    </autocomplete>
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
            },
            importNoResult(search) {
                //TODO handle errores de conexion del servidor
                this.$store.dispatch('enableLoading');
                axios
                    .put(Routing.generate('sel_admin_api_empleado_importar', {
                        identificacion: encodeURI(search),
                    }))
                    .then(response => {
                        const empleado = response.data.empleado;
                        if(empleado) {
                            this.onSubmit(empleado)
                        } else {
                            this.$store.dispatch('showMessage', 'Empleado no encontrado');
                            this.clearInput();
                        }
                        this.$store.dispatch('disableLoading');
                    })
            }
        },
        data: function () {
            return {
                search: input => new Promise(resolve => {
                    if (input.length >= 3) {
                        //TODO handle errores de conexion del servidor
                        axios
                            .get(Routing.generate('sel_admin_api_search_usuario', {
                                term: encodeURI(input),
                                rol: 'empleado'
                            }))
                            .then(response => {
                                const results = response.data.length > 0 ? response.data : [input];
                                resolve(results)
                            })
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

<style lang="scss"  type="text/scss">
    .autocomplete ul{
        padding-left: 0 !important;
        padding-bottom: 0 !important;

        .autocomplete-no-result {
            cursor: pointer;
            padding: 12px 12px 12px 48px;
        }
    }
</style>