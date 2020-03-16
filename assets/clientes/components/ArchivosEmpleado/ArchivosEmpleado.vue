<template>
    <v-card
        color="grey lighten-4"
        flat
        tile>
        <v-container class="card-content">

            <v-row no-gutters v-if="!!empleado && archivos.length > 0">
                <v-col :cols="!!archivoSelected ? 8 : 12">
                    <archivos-browser
                        id="archivos-browser"
                        ref="archivosBrowser"
                        clickable="#toolbar-upload-file"
                        v-bind:class="{disabled: !empleado}"
                    ></archivos-browser>
                </v-col>
                <v-col v-if="!!archivoSelected">
                    <archivo-detail></archivo-detail>
                </v-col>
            </v-row>
        </v-container>
    </v-card>
</template>

<script>
    import archivosBrowser from './ArchivosBrowser'
    import archivoDetail from './ArchivoDetail'
    import { mapState } from 'vuex';

    export default {
        name: "ArchivosEmpleado",
        components: {
            archivosBrowser,
            archivoDetail
        },
        computed: {
            ...mapState({
                empleado: state => state.empleado
            }),
            ...mapState('archivosEmpleado', {
                archivos: state => state.archivos,
                archivoSelected: state => state.archivoSelected
            })
        },
        methods: {

        },
        mounted() {
            this.$store.dispatch('archivosEmpleado/fetchArchivos')
        }
    }
</script>

<style lang="scss"  type="text/scss">
    .v-toolbar {
        position:relative;
        z-index: 2;
    }
    .card-content {
        position:relative;
        z-index: 1;
    }
    #archivos-browser {
        position: relative;
    }
    #archivos-browser.disabled {
        display: none;
    }

    .dropzone-custom-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .subtitle {
        color: #314b5f;
    }
    .main-alert {
        margin-bottom: 0;
    }

</style>