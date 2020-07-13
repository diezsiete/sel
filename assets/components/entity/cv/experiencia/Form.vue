<template>
    <v-form autocomplete="off">
        <v-container fluid>
            <v-row>
                <v-col cols="12" sm="6">
                    <text-field label="Nombre de la empresa" v-model="item.empresa"
                                v-alidation required>
                    </text-field>
                </v-col>
                <v-col cols="12" sm="6">
                    <text-field label="Nombre del cargo" v-model="item.cargo"
                                v-alidation required>
                    </text-field>
                </v-col>
            <!--</v-row>
            <v-row>-->
                <v-col cols="12" sm="6">
                    <entity-field v-model="item.area" label="Area del cargo" namespace="area"
                                  :return-object="relationsReturnObject"
                                  v-alidation required>
                    </entity-field>
                </v-col>
                <v-col cols="12" sm="6">
                    <entity-field v-model="item.duracion" label="Duración" namespace="experienciaDuracion"
                                  :return-object="relationsReturnObject"
                                  v-alidation required>
                    </entity-field>
                </v-col>
            <!--</v-row>
            <v-row>-->
                <v-col cols="12">
                    <text-area-field label="Descripción" v-model="item.descripcion"
                                     v-alidation required>
                    </text-area-field>
                </v-col>
            <!--</v-row>
            <v-row>-->
                <v-col cols="12">
                    <text-area-field label="Logros obtenidos" v-model="item.logrosObtenidos">
                    </text-area-field>
                </v-col>
            <!--</v-row>
            <v-row>-->
                <v-col cols="12">
                    <text-area-field label="Motivo del retiro" v-model="item.motivoRetiro">
                    </text-area-field>
                </v-col>
            <!--</v-row>
            <v-row>-->
                <v-col cols="12" sm="6">
                    <text-field v-model="item.jefeInmediato" label="Nombre jefe inmediato"
                                v-alidation required>
                    </text-field>
                </v-col>
                <v-col cols="12" sm="6">
                    <text-field v-model="item.telefonoJefe" label="Teléfono contacto jefe"
                                v-alidation required>
                    </text-field>
                </v-col>
            <!--</v-row>
            <v-row>-->
                <v-col cols="12" sm="12" md="4">
                    <text-field v-model="item.salarioBasico" label="Salario básico"
                                v-alidation required>
                    </text-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <date-field v-model="item.fechaIngreso" label="Fecha ingreso"
                                v-alidation required>
                    </date-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <date-field v-model="item.fechaRetiro" label="Fecha retiro">
                    </date-field>
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
    import AlidationFormMixin from "@mixins/alidation/AlidationFormMixin";
    import FormMixin from "@mixins/FormMixin";
    import TextAreaField from "@components/field/TextAreaField";
    import DateField from "@components/field/DateField";

    export default {
        name: "ExperienciaForm",
        mixins: [AlidationFormMixin, FormMixin],
        components:{
            TextAreaField,
            DateField
        }
    }
</script>