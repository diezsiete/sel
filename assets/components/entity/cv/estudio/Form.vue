<template>
    <v-form>
        <v-container fluid>
            <v-row>
                <v-col cols="12">
                    <entity-field v-model="values.codigo"
                                  :error-messages="codigoErrors"
                                  item-text="nombre"
                                  label="Area de estudio"
                                  namespace="estudioCodigo"
                                  :return-object="relationsReturnObject"
                    >
                    </entity-field>
                </v-col>
                <v-col cols="12">
                    <v-text-field label="Título del estudio" v-model="values.nombre" :error-messages="nombreErrors">
                    </v-text-field>
                </v-col>
                <v-col cols="12">
                    <entity-field v-model="values.instituto"
                                  :error-messages="institutoErrors"
                                  item-text="nombre"
                                  label="Institución"
                                  namespace="estudioInstituto"
                                  :return-object="relationsReturnObject"
                    >
                    </entity-field>
                </v-col>
                <v-col cols="12">
                    <date-field v-model="values.fin" label="Fecha de finalización"></date-field>
                    <!--<v-menu
                            attach
                            v-model="menu2"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            min-width="290px">
                        <template v-slot:activator="{ on, attrs }">
                            &lt;!&ndash;prepend-icon="event"&ndash;&gt;
                            <v-text-field
                                    v-model="values.fin"
                                    label="Fecha de finalización"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                            ></v-text-field>
                        </template>
                        <v-date-picker v-model="values.fin" @input="menu2 = false"></v-date-picker>
                    </v-menu>-->
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
    import has from 'lodash/has';
    import {validationMixin} from 'vuelidate';
    import {required, minLength} from 'vuelidate/lib/validators';
    import {mapActions} from 'vuex';
    import {mapFields} from 'vuex-map-fields';
    import EntityField from "@components/field/EntityField";
    import DateField from "@components/field/DateField";

    export default {
        name: 'EstudioForm',
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
            },
            relationsReturnObject : {
                type: Boolean,
                default: false
            }
        },
        mounted() {
        },
        data() {
            return {
                menu2: false,
            };
        },
        components: {
            EntityField,
            DateField
        },
        computed: {
            item() {
                return this.initialValues || this.values;
            },
            violations() {
                return this.errors || {};
            },
            codigoErrors () {
                const errors = [];
                if (!this.$v.item.codigo.$dirty) return errors;
                !this.$v.item.codigo.required && errors.push('Area de estudio es requerida');
                return errors
            },
            nombreErrors () {
                const errors = [];
                if (!this.$v.item.nombre.$dirty) return errors;
                !this.$v.item.nombre.required && errors.push('Título es requerido');
                !this.$v.item.nombre.minLength && errors.push('Título debe ser al menos 4 caracteres');
                return errors
            },
            institutoErrors () {
                const errors = [];
                if (!this.$v.item.instituto.$dirty) return errors;
                !this.$v.item.instituto.required && errors.push('Seleccione el instituto, si no encuentra su institución seleccion la opcion "NO APLICA"');
                return errors
            },
        },
        methods: {},
        validations: {
            item: {
                codigo: {
                    required,
                },
                nombre: {
                    required,
                    minLength: minLength(4)
                },
                instituto: {
                    required,
                },
            }
        }
    };
</script>
