<template>
    <v-card
        color="grey lighten-4"
        flat
        tile>
        <v-toolbar flat>
            <v-toolbar-title>{{ convenioNombre }}</v-toolbar-title>
            <v-spacer></v-spacer>

            <v-btn class="ma-2" large outlined color="success" id="toolbar-send-email" @click="enviarCorreo"
                   v-if="convenio && ownersIdsSelected.length > 0">
                <v-icon left>mdi-email</v-icon> Enviar
            </v-btn>

            <convenio-search placeholder="Buscar convenio" v-on:convenio-selected="onConvenioSelected">
            </convenio-search>

        </v-toolbar>
        <v-alert :type="alert.type" dense border="left" class="main-alert" v-if="alert" dismissible>
            {{ alert.message }}
        </v-alert>
        <v-container class="card-content">
            <v-row no-gutters v-if="!!convenio && owners.length > 0">
                <v-col :cols="!!ownerSelected ? 8 : 12">
                    <archivo-owners-browser
                        id="archivos-browser"
                        ref="archivosBrowser"
                        clickable="#toolbar-upload-file"
                    ></archivo-owners-browser>
                </v-col>

                <v-col v-if="!!ownerSelected">
                    <archivo-owner-detail></archivo-owner-detail>
                </v-col>
            </v-row>
        </v-container>
    </v-card>
</template>

<script>
    import convenioSearch from '@/components/ConvenioSearch'
    import archivoOwnersBrowser from './ArchivoOwnersBrowser'
    import archivoOwnerDetail from './ArchivoOwnerDetail'
    import store from "@/store/store";
    import { mapState } from 'vuex';
    import Router from "@/../router";
    import axios from 'axios';

    export default {
        name: "Enviar",
        store,
        components: {
            convenioSearch,
            archivoOwnersBrowser,
            archivoOwnerDetail,
        },
        computed: mapState('enviar', {
            convenio: state => state.convenio,
            alert: state => state.alert,
            owners: state => state.owners,
            ownersIdsSelected: state => state.ownersIdsSelected,
            ownerSelected: state => state.ownerSelected,
            convenioNombre(state) {
                return state.convenio
                    ? `${state.convenio.nombre}  - ${state.convenio.codigo}`
                    : "Seleccione convenio"
            }
        }),
        methods: {
            async onConvenioSelected(result) {
                await this.$store.dispatch('enviar/setConvenio', result);
                await this.$store.dispatch('enviar/fetchOwners')
            },
            async enviarCorreo() {
                this.$store.dispatch('enableLoading');
                console.log(this.ownersIdsSelected);
                await axios.post(Router.generate('sel_admin_api_archivo_enviar_correo'), {
                    owners: this.ownersIdsSelected
                });
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
    .loader-container {
        position: relative;
        margin: 0;
        padding: 0;

        .loader {
            position: absolute;
            top: 0;
            width: 100%;
            height: 100%;
            background: #00000033;
            padding: inherit;

            .v-progress-circular{
                margin: 90px;
            }
        }
    }


    .subtitle {
        color: #314b5f;
    }
    .main-alert {
        margin-bottom: 0;
    }

</style>