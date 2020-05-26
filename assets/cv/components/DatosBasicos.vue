<template>
    <v-form>
        <v-container>
            <v-row>
                <v-col cols="12" sm="6" md="3">
                    <!-- @blur="$v.primerNombre.$touch()" -->
                    <v-text-field label="Primer nombre" v-model="primerNombre" :error-messages="primerNombreErrors">
                    </v-text-field>
                </v-col>
                <v-col cols="12" sm="6" md="3">
                    <v-text-field label="Segundo nombre" v-model="segundoNombre" :error-messages="segundoNombreErrors">
                    </v-text-field>
                </v-col>
                <v-col cols="12" sm="6" md="3">
                    <v-text-field label="Primer apellido" v-model="primerApellido" :error-messages="primerApellidoErrors">
                    </v-text-field>
                </v-col>
                <v-col cols="12" sm="6" md="3">
                    <v-text-field label="Segundo apellido" v-model="segundoApellido" :error-messages="segundoApellidoErrors">
                    </v-text-field>
                </v-col>

            </v-row>
        </v-container>
    </v-form>
</template>

<script>
    import cvComponent from "../helpers/cv-component";

    export default {
        name: "DatosBasicos",
        mixins: [cvComponent([
            'primerNombre', 'segundoNombre', 'primerApellido', 'segundoApellido'
        ])],
    }
</script>

<style scoped>

</style>