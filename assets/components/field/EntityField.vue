<template>
    <v-autocomplete
            :loading="isLoading"
            :items="items"
            :search-input.sync="search"
            :error-messages="errorMessages"
            clearable
            hide-selected
            :item-text="itemText"
            :item-value="identifier"
            :label="label"
            :placeholder="placeholder"
            :return-object="returnObject"
            attach
            @click="fetch"
            @input="updateValue"
            :value="identifierValue"
            ref="autocomplete"
            v-on:click:append="menuArrow"
            @blur="blur"
            @focus="open = true"
    >
        <template v-slot:no-data>
            <v-list-item>
                <v-list-item-title v-if="fetched">
                    No hay datos
                </v-list-item-title>
            </v-list-item>
        </template>
    </v-autocomplete>

</template>

<script>
    export default {
        name: "EntityField",
        data: () => ({
            isLoading: false,
            fetched: false,
            search: null,
            open: false,
        }),
        computed:{
            items() {
                return this.$store.getters[`${this.namespace}/list`]
            },
            identifierValue() {
                if(typeof this.value === 'object' && this.value !== null) {
                    this.fetch();
                    return this.value[this.identifier]
                }
                return this.value;
            }
        },
        mounted() {
            const autocomplete = this.$refs.autocomplete;
            autocomplete.activateMenu = (orig => () => {
                orig.call(autocomplete);
                this.open = autocomplete.isMenuActive;
            })(autocomplete.activateMenu)
            autocomplete.selectItem = (orig => item => {
                orig.call(autocomplete, item);
                this.open = autocomplete.isMenuActive;
            })(autocomplete.selectItem)
        },
        methods: {
            fetch() {
                if (this.items.length > 0 || this.isLoading) {
                    return;
                }
                this.isLoading = true;
                this.$store.dispatch(`${this.namespace}/fetchAll`, {pagination: false})
                    .finally(() => {
                        this.fetched = true;
                        this.isLoading = false;
                    })
            },
            menuArrow() {
                const autocomplete = this.$refs.autocomplete;

                if (autocomplete.isMenuActive) {
                    this.$refs.autocomplete.isMenuActive = false;
                    autocomplete.blur();
                } else {
                    if (this.items.length === 0 && !this.isLoading) {
                        this.fetch();
                    }
                    this.$refs.autocomplete.isMenuActive = true;
                    debugger;
                    autocomplete.focus();

                }
            },
            updateValue: function (value) {
                this.$emit('input', value)
            },
            blur() {
                if(document.hasFocus()) {
                    this.open = false
                }
            }
        },
        props: {
            errorMessages: [String, Array],
            identifier: {
                type: String,
                default: '@id'
            },
            itemText: String,
            label: String,
            namespace: {
                type: String,
                required: true
            },
            placeholder: {
                type: String,
                default: 'Seleccione...'
            },
            returnObject: {
                type: Boolean,
                default: false
            },
            value: null,
        },
        watch: {
            search(val) {
                this.fetch()
            },
            open(state) {
                console.log('open', state)
                this.$store.state.overflow = state;
            }
        },

    }
</script>

<style scoped>

</style>