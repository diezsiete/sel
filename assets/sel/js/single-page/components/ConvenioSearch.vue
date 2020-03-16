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
            <li v-else class="autocomplete-no-result red lighten-4">
                <small>Convenio no encontrado</small>
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
        name: "ConvenioSearch",
        components: {
            autocomplete: autocompleteVue
        },
        methods: {
            onSubmit(result) {
                if(result) {
                    this.clearInput()
                        .$emit('convenio-selected', result);
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
                        //TODO handle errores de conexion del servidor
                        axios
                            .get(Routing.generate('sel_admin_api_search_convenio', {
                                term: encodeURI(input)
                            }))
                            .then(response => {
                                const results = response.data.length > 0 ? response.data : [input];
                                resolve(results)
                            })
                    } else {
                        resolve([])
                    }
                }),
                getResultValue(convenio) {
                    return `${convenio.nombre} - ${convenio.codigo}`
                },
            }
        },
        props: {
            placeholder: {
                type: String,
                default: "Buscar Convenio"
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