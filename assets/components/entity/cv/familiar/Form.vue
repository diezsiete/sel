<template>
    <v-form autocomplete="off">
        <v-container fluid>
            <v-row>
                <v-col cols="12" md="4">
                    <text-field label="Nombres" v-model="item.nombre"
                                v-alidation required>
                    </text-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <text-field label="Primer apellido" v-model="item.primerApellido"
                                v-alidation required>
                    </text-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <text-field label="Segundo apellido" v-model="item.segundoApellido">
                    </text-field>
                </v-col>
                <v-col cols="12" sm="6">
                    <text-field label="Identificación" v-model="item.identificacion">
                    </text-field>
                </v-col>
                <v-col cols="12" sm="6">
                    <entity-field v-model="item.identificacionTipo" label="Tipo de identificación" namespace="identificacionTipo"
                                  :return-object="relationsReturnObject">
                    </entity-field>
                </v-col>

                <v-col cols="12" sm="6" md="4">
                    <date-field v-model="item.nacimiento" label="Fecha de nacimiento" v-alidation required>
                    </date-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <entity-field v-model="item.parentesco" label="Parentesco" namespace="parentesco"
                                  :return-object="relationsReturnObject"
                                  v-alidation required>
                    </entity-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <entity-field v-model="item.ocupacion" label="Ocupación" namespace="ocupacion"
                                  :return-object="relationsReturnObject"
                                  v-alidation required>
                    </entity-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <entity-field v-model="item.nivelAcademico" label="Nivel academico" namespace="nivelAcademico"
                                  :return-object="relationsReturnObject"
                                  v-alidation required>
                    </entity-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <entity-field v-model="item.genero" label="Genero" namespace="genero"
                                  :return-object="relationsReturnObject"
                                  v-alidation required>
                    </entity-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <entity-field v-model="item.estadoCivil" label="Estado civil" namespace="estadoCivil"
                                  :return-object="relationsReturnObject"
                                  v-alidation required>
                    </entity-field>
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
        name: "FamiliarForm",
        mixins: [AlidationFormMixin, FormMixin],
        components:{
            DateField
        }
    }
</script>