<template>
    <v-form>
        <v-container fluid>
            <v-row>
                <v-col cols="12" sm="6" md="3">
                    <!-- @blur="$v.primerNombre.$touch()" -->
                    <v-text-field label="Primer nombre" v-model="item.primerNombre"
                                  :error-messages="primerNombreErrors">
                    </v-text-field>
                </v-col>
                <v-col cols="12" sm="6" md="3">
                    <v-text-field label="Segundo nombre" v-model="item.segundoNombre">
                    </v-text-field>
                </v-col>
                <v-col cols="12" sm="6" md="3">
                    <v-text-field label="Primer apellido" v-model="item.primerApellido"
                                  :error-messages="primerApellidoErrors">
                    </v-text-field>
                </v-col>
                <v-col cols="12" sm="6" md="3">
                    <v-text-field label="Segundo apellido" v-model="item.segundoApellido">
                    </v-text-field>
                </v-col>

            </v-row>
        </v-container>
    </v-form>
</template>

<script>
    import has from 'lodash/has';
    import {validationMixin} from 'vuelidate';
    import {minLength, required} from 'vuelidate/lib/validators';
    import {mapActions} from 'vuex';
    import {mapFields} from 'vuex-map-fields';

    export default {
        name: 'CvForm',
        mixins: [validationMixin],
        props: {
            values: {
                type: Object,
                required: true
            },

            errors: {
                type: Object,
                default: () => {
                }
            },

            initialValues: {
                type: Object,
                default: () => {
                }
            }
        },
        mounted() {
        },
        data() {
            return {};
        },
        computed: {
            item() {
                return this.initialValues || this.values;
            },
            violations() {
                return this.errors || {};
            },
            primerNombreErrors() {
                const errors = [];
                if (!this.$v.item.primerNombre.$dirty) return errors;
                !this.$v.item.primerNombre.required && errors.push('Campo requerido');
                return errors
            },
            primerApellidoErrors() {
                const errors = [];
                if (!this.$v.item.primerApellido.$dirty) return errors;
                !this.$v.item.primerApellido.required && errors.push('Campo requerido');
                return errors
            },
        },
        methods: {},
        validations: {
            item: {
                primerNombre: {
                    required,
                },
                primerApellido: {
                    required,
                },
            }
        }
    };
</script>
