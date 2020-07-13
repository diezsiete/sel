<template>
    <v-form>
        <v-container fluid>
            <v-row>
                <v-col cols="12"><h1 class="mb-0">Datos básicos</h1></v-col>
            </v-row>
            <v-row>
                <v-col cols="12" sm="6" md="3">
                    <text-field label="Primer nombre" v-model="item.primerNombre"
                                  v-alidation required>
                    </text-field>
                </v-col>
                <v-col cols="12" sm="6" md="3">
                    <text-field label="Segundo nombre" v-model="item.segundoNombre">
                    </text-field>
                </v-col>
                <v-col cols="12" sm="6" md="3">
                    <text-field label="Primer apellido" v-model="item.primerApellido"
                                  v-alidation required>
                    </text-field>
                </v-col>
                <v-col cols="12" sm="6" md="3">
                    <text-field label="Segundo apellido" v-model="item.segundoApellido">
                    </text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" md="4">
                    <text-field label="Identificación" v-model="item.identificacion"
                                v-alidation required :fetch-unique="{value: 'usuario', message: 'Identificación ya registrada'}">
                    </text-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <entity-field v-model="item.identificacionTipo"
                                  label="Tipo de identificacion"
                                  namespace="identificacionTipo" v-alidation required>
                    </entity-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <ciudad-field v-model="item.identCiudad" label="Lugar de la identificación" v-alidation required>
                    </ciudad-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" md="4">
                    <date-field v-model="item.nacimiento" label="Fecha de nacimiento"
                                v-alidation required>
                    </date-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <ciudad-field v-model="item.nacCiudad" label="Lugar de nacimiento"
                                  v-alidation required>
                    </ciudad-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <entity-field v-model="item.nacionalidad" label="Nacionalidad" namespace="nacionalidad"
                                  v-alidation required>
                    </entity-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" sm="6" md="3">
                    <entity-field v-model="item.genero" label="Genero" namespace="genero"
                                  v-alidation required>
                    </entity-field>
                </v-col>
                <v-col cols="12" sm="6" md="3">
                    <entity-field v-model="item.estadoCivil" label="Estado civil" namespace="estadoCivil"
                                  v-alidation required>
                    </entity-field>
                </v-col>
                <v-col cols="12" sm="6" md="3">
                    <entity-field v-model="item.grupoSanguineo" label="Grupo sanguineo" namespace="grupoSanguineo"
                                  v-alidation required>
                    </entity-field>
                </v-col>
                <v-col cols="12" sm="6" md="3">
                    <entity-field v-model="item.factorRh" label="Factor RH" namespace="factorRh"
                                  v-alidation required>
                    </entity-field>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12"><h1 class="mb-0">Vivienda y contacto</h1></v-col>
            </v-row>
            <v-row>
                <v-col cols="12" sm="6">
                    <text-field label="Email" v-model="item.email"
                                v-alidation required>
                    </text-field>
                </v-col>
                <v-col cols="12" sm="6">
                    <text-field label="Email alternativo" v-model="item.emailAlt">
                    </text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" sm="12" md="4">
                    <ciudad-field v-model="item.resiCiudad" label="Ciudad donde vive"
                                  v-alidation required>
                    </ciudad-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <text-field label="Barrio" v-model="item.barrio"
                                v-alidation required>
                    </text-field>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <text-field label="Dirección" v-model="item.direccion"
                                v-alidation required>
                    </text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" sm="6">
                    <text-field label="Teléfono" v-model="item.telefono"
                                v-alidation required>
                    </text-field>
                </v-col>
                <v-col cols="12" sm="6">
                    <text-field label="Celular" v-model="item.celular">
                    </text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12"><h1 class="mb-0">Formación</h1></v-col>
            </v-row>
            <v-row>
                <v-col cols="12">
                    <entity-field v-model="item.nivelAcademico" label="Nivel académico" namespace="nivelAcademico"
                                  v-alidation required>
                    </entity-field>
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
    import FormMixin from "@mixins/FormMixin";
    import EntityField from "@components/field/EntityField";
    import CiudadField from "@components/field/CiudadField";
    import DateField from "@components/field/DateField";

    export default {
        name: 'CvForm',
        mixins: [FormMixin],
        components: {
            EntityField,
            CiudadField,
            DateField
        }
    };
</script>
