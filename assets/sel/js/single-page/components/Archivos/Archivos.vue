<template>
    <v-app>

        <v-card
            color="grey lighten-4"
            flat
            height="200px"
            tile
        >
            <v-toolbar flat>
                <v-toolbar-title>{{ usuarioNombre }}</v-toolbar-title>

                <v-spacer></v-spacer>
                <usuario-search v-on:usuario-select="onUsuarioSelect"></usuario-search>

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

                    </v-col>
                </v-row>
            </v-container>

        </v-card>

    </v-app>
</template>

<script>
    import usuarioSearch from '@/components/UsuarioSearch'

    export default {
        name: "Archivos",
        components: {
            usuarioSearch
        },
        data() {
            return {
                usuarioNombre: 'Seleccione un usuario'
            }
        },
        methods: {
            onUsuarioSelect(usuario) {
                this.usuarioNombre = `${usuario.nombreCompleto}  - ${usuario.identificacion}`
            }
        }
    }
</script>

<style scoped>

</style>