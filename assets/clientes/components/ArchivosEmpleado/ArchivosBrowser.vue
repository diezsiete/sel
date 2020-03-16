<template>
    <v-simple-table v-if="empleado">
        <template v-slot:default>
            <thead>
                <tr v-if="!archivosChecked.length">
                    <th>
                        <v-checkbox v-on:change="onCheckboxAllChange"></v-checkbox>
                    </th>
                    <th class="text-left">Nombre</th>
                    <th class="text-left">Tamaño</th>
                    <th class="text-left">Tipo</th>
                    <th class="text-left">Ultimo modificado</th>
                </tr>
                <tr class="indigo darken-1 checked-menu" v-if="archivosChecked.length">
                    <th class="text-left white--text">
                        <v-btn class="close-header" text v-on:click="clearArchivosChecked">
                            <v-icon class="white--text">close</v-icon>
                        </v-btn>
                    </th>
                    <td class="text-left white--text" colspan="4">
                        <span class="cantidad">{{ archivosChecked.length }} seleccionados</span>
                        <span class="separator">|</span>
                        <v-btn small class="api-call" @click="descargarArchivosSeleccionados">
                            Descargar
                        </v-btn>
                    </td>
                </tr>
            </thead>
            <tbody>
            <tr v-for="archivo in archivos" :key="archivo.id" v-on:click="selectArchivo(archivo)" class="archivo">
                <td>
                    <v-checkbox :value="archivo.id" v-model="archivosChecked" v-on:click.native.stop="checkArchivo(archivo)">
                    </v-checkbox>
                </td>
                <td class="text-left">{{ archivo.originalFilename}}</td>
                <td class="text-left">{{ archivo.size }}</td>
                <td class="text-left">{{ archivo.mimeType }}</td>
                <td class="text-left">{{ archivo.updatedAt }}</td>
            </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
    import { mapState } from 'vuex';
    import Router from "@/../router";
    import axios from 'axios';

    export default {
        name: "ArchivosBrowser",
        props: {
            clickable: {
                default: true
            }
        },
        data() {
            return {
                checkboxAll: false,
                archivosChecked: [],
            }
        },
        computed: {
            ...mapState({
                empleado: state => state.empleado,
            }),
            ...mapState('archivosEmpleado', {
                archivos: state => state.archivos,
                archivoSelected: state => state.archivoSelected
            })
        },
        methods: {
            clearArchivosChecked() {
                this.archivosChecked = [];
            },
            onCheckboxAllChange(checked) {
                if(checked) {
                    this.archivos.map(archivo => this.archivosChecked.push(archivo.id))
                } else {
                    this.archivosChecked = [];
                }
            },
            selectArchivo(archivo) {
                this.$store.dispatch('archivosEmpleado/toggleArchivo', archivo)
            },
            checkArchivo(archivo) {
                if(this.archivoSelected && this.archivoSelected.id !== archivo.id ) {
                    this.$store.dispatch('archivosEmpleado/toggleArchivo', archivo)
                }
            },
            async descargarArchivosSeleccionados() {
                await this.$store.dispatch('enableLoading');
                const response = await axios.post(Router.generate('sel_admin_api_archivo_download', {usuario: this.empleado.usuario.id}), {
                    archivos: this.archivosChecked
                });
                await this.$store.dispatch('disableLoading');
                if(response.data.url) {
                    window.open(response.data.url, '_blank');
                }
            }
        }
    }
</script>

<style scoped lang="scss"  type="text/scss">
    .v-btn.close-header {
        min-width: auto;
        padding:0;
        margin: 15px 7px 15px 0;
    }
    .checked-menu{
        .cantidad {
            margin-right: 10px;
        }
        .v-btn.api-call{
            margin-left: 10px;
        }
    }
    tr.archivo {
        cursor: pointer;
    }
</style>