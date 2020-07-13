<template>
    <v-autocomplete
        v-if="edit"
        autocomplete="nope"
        attach
        :class="classes"
        clearable
        dense
        :error-messages="errors"
        hide-no-data
        hide-selected
        :items="items"
        :item-text="itemText"
        :item-value="identifier"
        :label="label"
        :loading="isLoading"
        outlined
        :placeholder="placeholder"
        ref="autocomplete"
        :return-object="true"
        :search-input.sync="search"
        :value="ciudadId"
        @input="updateValue"
        @blur="blur"
        @focus="open = true"
    >
        <template v-slot:item="data">
            <v-list-item-content>
                <v-list-item-title v-html="data.item.nombre"></v-list-item-title>
                <v-list-item-subtitle v-html="data.item.dpto.nombre + ' - ' + data.item.pais.nombre"></v-list-item-subtitle>
            </v-list-item-content>
        </template>
    </v-autocomplete>
    <v-input
        v-else
        :label="label">
        <p>
            {{ ciudadId && items.length ? items[0].nombre : '' }}
        </p>
    </v-input>
</template>

<script>
    import debounce from 'debounce'
    import ciudadService from '@/services/ciudad';
    import AutocompleteMixin from "@mixins/field/AutocompleteMixin";

    export default {
        name: "CiudadField",
        mixins: [AutocompleteMixin],
        data: () => ({
            items: []
        }),
        computed: {
            ciudadId() {
                if (this.value && !this.items.length) {
                    this.isLoading = 'secondary';
                    // TODO si el valor no es encontrado, se queda en loop
                    ciudadService
                        .find(this.value)
                        .then(response => response.json())
                        .then(retrieved => {
                            this.items = [retrieved]
                        })
                        .catch(e => this.items = [])
                        .finally(() => this.isLoading = false)
                }
                return this.value
            }
        },
        methods: {
            querySelections: async (search, self) => {
                if (!search || search.length < 3) {
                    self.items = [];
                    return
                }
                if (self.isLoading) {
                    return;
                }
                self.isLoading = 'secondary';
                ciudadService
                    .findAll({params: {nombre: search}})
                    .then(response => response.json())
                    .then(retrieved => {
                        self.items = retrieved['hydra:member']
                    })
                    .catch(e => self.$store.commit('ciudad/SET_ERROR', e.message))
                    .finally(() => self.isLoading = false)
            },
            updateValue: function (value) {
                this.$emit('input', value ? value[this.identifier] : null)
            },

        },
        props: {
            placeholder: {
                type: String,
                default: 'Escriba para buscar'
            },
        },
        watch: {
            search(val) {
                if (!val || !this.open) {
                    return
                }
                debounce(this.querySelections, 200)(val, this)
            },
        },
    }
</script>

<style scoped>
    .v-input--selection-controls {
        margin-top: 0;
        padding-top: 0;
    }

    .v-input--radio-group--row legend {
        display: block;
        width: 100%;
        padding-bottom: 24px;
    }
</style>
