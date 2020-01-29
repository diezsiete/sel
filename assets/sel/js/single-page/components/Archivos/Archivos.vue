<template>
    <v-app>

        <v-card
            color="grey lighten-4"
            flat
            height="200px"
            tile
        >
            <v-toolbar flat>
                <v-toolbar-title>{{ empleadoNombre }}</v-toolbar-title>

                <v-spacer></v-spacer>
                <usuario-search placeholder="Buscar empleado"></usuario-search>

                <!--<v-btn icon>
                    <v-icon>mdi-magnify</v-icon>
                </v-btn>
                <v-btn icon>
                    <v-icon>mdi-heart</v-icon>
                </v-btn>

                <v-btn icon>
                    <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>-->
            </v-toolbar>

            <v-container>
                <v-row justify="space-between">
                    <v-col cols="auto">
                        <archivos-browser ref="archivosBrowser"></archivos-browser>
                    </v-col>
                </v-row>
            </v-container>

        </v-card>

    </v-app>
</template>

<script>
    import usuarioSearch from '@/components/UsuarioSearch'
    import archivosBrowser from './ArchivosBrowser'
    import store from "@/store/store";
    import { mapState } from 'vuex';

    export default {
        name: "Archivos",
        store,
        components: {
            usuarioSearch,
            archivosBrowser
        },
        computed: mapState({
            // empleado: function() {
            //     return store.state.empleado
            // },
            empleadoNombre(state) {
                if(state.empleado) {
                    this.$refs.archivosBrowser.loadFiles();
                    return `${state.empleado.nombreCompleto}  - ${state.empleado.identificacion}`
                } else {
                    return "Seleccione un empleado"
                }
            }
        })
    }
</script>

<style scoped>

</style>