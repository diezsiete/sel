<template>
    <v-form>
        <v-container>
            <v-row>
                <v-col cols="12" sm="6" md="3">
                    <!-- @blur="$v.primerNombre.$touch()" -->
                    <v-text-field label="Primer nombre" v-model="nombre" :error-messages="nombreErrors">
                    </v-text-field>
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
    import cvComponent from "../helpers/cv-component";
    import dialogEntityComponent from "../helpers/dialog-entity-component";

    export default {
        name: "Estudio",
        mixins: [
            cvComponent([
                'nombre'
            ]),
            dialogEntityComponent
        ],
        props: [
            'index'
        ]
    }
</script>

<style scoped>

</style>