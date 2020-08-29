<template>
    <v-form autocomplete="off">
        <v-container fluid>
            <v-row>
                <v-col cols="12">
                    <entity-field v-model="item.codigo" label="Area de estudio" namespace="estudioCodigo"
                                  :return-object="relationsReturnObject"
                                  v-alidation required>
                    </entity-field>
                </v-col>
                <v-col cols="12">
                    <text-field label="Título del estudio" v-model="item.nombre"
                                v-alidation required :min-length="4">
                    </text-field>
                </v-col>
                <v-col cols="12">
                    <entity-field v-model="item.instituto" label="Institución" namespace="estudioInstituto"
                                  :return-object="relationsReturnObject"
                                  v-alidation required="Seleccione el instituto, si no encuentra su institución seleccion la opcion 'NO APLICA'"
                    >
                    </entity-field>
                </v-col>
                <v-col cols="12">
                    <date-field v-model="item.fin" label="Fecha de finalización"></date-field>
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
    import AlidationFormMixin from "@mixins/alidation/AlidationFormMixin";
    import FormMixin from "@mixins/FormMixin";
    import DateField from "@components/field/DateField";

    export default {
        name: 'EstudioForm',
        mixins: [AlidationFormMixin, FormMixin],
        components: {
            DateField
        },
    };
</script>
