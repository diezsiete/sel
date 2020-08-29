<template>
    <v-form autocomplete="off">
        <v-container fluid>
            <v-row>
                <v-col cols="12" :hidden="tipoDisabled">
                    <entity-field v-model="item.tipo" label="Tipo de referencia" namespace="referenciaTipo"
                                  :return-object="relationsReturnObject" :hidden="tipoDisabled"
                                  v-alidation required>
                    </entity-field>
                </v-col>
                <v-col cols="12">
                    <text-field label="Nombre completo" v-model="item.nombre"
                                v-alidation required>
                    </text-field>
                </v-col>
                <v-col cols="12">
                    <text-field label="Ocupación" v-model="item.ocupacion"
                                v-alidation required>
                    </text-field>
                </v-col>
                <v-col cols="12">
                    <text-field label="Parentesco" v-model="item.parentesco"
                                v-alidation required>
                    </text-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <text-field label="Celular" v-model="item.celular"
                                v-alidation required>
                    </text-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <text-field label="Teléfono" v-model="item.telefono" ref="diarrea"
                                v-alidation required>
                    </text-field>
                </v-col>
                <v-col cols="12" md="4">
                    <text-field label="Dirección" v-model="item.direccion">
                    </text-field>
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
    import AlidationFormMixin from "@mixins/alidation/AlidationFormMixin";
    import FormMixin from "@mixins/FormMixin";
    export default {
        name: "ReferenciaForm",
        mixins:[AlidationFormMixin, FormMixin],
        props: {
            tipoDisabled: {
                type: Boolean,
                default: false
            }
        }
    }
</script>