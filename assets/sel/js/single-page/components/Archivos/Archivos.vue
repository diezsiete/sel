<template>
    <v-card
        color="grey lighten-4"
        flat
        tile>
        <v-toolbar flat>
            <v-toolbar-title>{{ empleadoNombre }}</v-toolbar-title>
            <v-spacer></v-spacer>
<!--            <v-btn class="ma-2" large outlined color="success" id="toolbar-send-email" v-if="empleado && archivos.length > 0">-->
<!--                <v-icon left>mdi-email</v-icon> Correo-->
<!--            </v-btn>-->

            <v-btn class="ma-2" large outlined color="success" id="toolbar-upload-file" :disabled="!empleado">
                <v-icon left>mdi-arrow-up-bold-box-outline</v-icon> Cargar archivos
            </v-btn>
            <usuario-search placeholder="Buscar empleado" v-on:usuario-selected="onUsuarioSelected">
            </usuario-search>

        </v-toolbar>
        <v-alert :type="alert.type" dense border="left" class="main-alert" v-if="alert" dismissible>
            {{ alert.message }}
        </v-alert>
        <v-container class="card-content">
                <file-uploader
                        :url="uploadUrl"
                        clickable="#toolbar-upload-file"
                        ref="fileUploader"
                        :enabled="!!empleado"
                        :min-height-auto="archivos.length > 0">
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
                    <div class="dropzone-custom-content" v-else>
                        <div v-if="!!empleado">
                            <v-icon>mdi-arrow-up-bold-box-outline</v-icon>
                            <div class="subtitle">
                                Arrastre aqui para cargar archivos, <br>
                                o seleccione el boton "Cargar archivos"
                            </div>
                        </div>
                    </div>
                </file-uploader>
        </v-container>

    </v-card>
</template>

<script>
    import usuarioSearch from '@/components/UsuarioSearch'
    import fileUploader from "./ArchivoUploader";
    import archivosBrowser from './ArchivosBrowser'
    import archivoDetail from './ArchivoDetail'
    import { mapState } from 'vuex';

    export default {
        name: "Archivos",
        components: {
            usuarioSearch,
            archivosBrowser,
            archivoDetail,
            fileUploader
        },
        computed: mapState({
            empleado: state => state.empleado,
            archivos: state => state.archivos,
            archivoSelected: state => state.archivoSelected,
            empleadoNombre(state) {
                return state.empleado
                    ? `${state.empleado.nombreCompleto}  - ${state.empleado.identificacion}`
                    : "Seleccione un empleado"
            },
            uploadUrl: 'archivoCreateUrl',
            alert: state => state.alert
        }),
        methods: {
            async onUsuarioSelected(result) {
                (await this.$store.dispatch('setEmpleado', result)).dispatch('fetchArchivos');
            }
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