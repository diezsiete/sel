<template>
    <v-autocomplete
            v-if="edit"
            attach
            autocomplete="off"
            :class="classes"
            clearable
            :disabled="disabled"
            dense
            :error-messages="errors"
            hide-selected
            :items="items"
            :item-text="itemText"
            :item-value="identifier"
            :label="label"
            :loading="isLoading"
            outlined
            :placeholder="placeholder"
            ref="autocomplete"
            :return-object="returnObject"
            :search-input.sync="search"
            :value="identifierValue"
            @click="fetch"
            @input="updateValue"
            @blur="blur"
            @focus="open = true"
            v-on:click:append="menuArrow"
    >
        <template v-slot:no-data>
            <v-list-item>
                <v-list-item-title v-if="fetched">
                    No hay datos
                </v-list-item-title>
            </v-list-item>
        </template>
    </v-autocomplete>
    <v-input
            v-else
            :label="label">
        <p>
            {{ identifierValue && items.length ? items[0].nombre : '' }}
        </p>
    </v-input>

</template>

<script>
    import AutocompleteMixin from "@mixins/field/AutocompleteMixin";

    export default {
        name: "EntityField",
        mixins: [AutocompleteMixin],
        computed: {
            items() {
                return this.$store.getters[`${this.namespace}/list`]
            },
            identifierValue() {
                if (this.value) {
                    this.fetch();
                }
                return this.value;
            }
        },

        methods: {
            fetch() {
                if (this.items.length > 0 || this.isLoading) {
                    return;
                }
                this.isLoading = 'secondary';
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
                    autocomplete.focus();

                }
            },
            updateValue: function (value) {
                this.$emit('input', value)
            },
        },
        props: {
            namespace: {
                type: String,
                required: true
            },
            disabled: {
                type: Boolean,
                default: false
            }
        },
        watch: {
            search(val) {
                this.fetch()
            },
        },

    }
</script>