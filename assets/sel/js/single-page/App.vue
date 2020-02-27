<template>
    <div class="override">
        <v-app>
            <v-tabs>
                <v-tab to="/sel/admin/archivo">Archivos</v-tab>
                <v-tab to="/sel/admin/archivo/enviar">Enviar</v-tab>
            </v-tabs>
            <div class="loader-container">
                <router-view></router-view>
                <div class="text-center loader" v-if="loading">
                    <v-progress-circular
                            :indeterminate="true"
                            :rotate="0"
                            :size="32"
                            :width="4"
                    ></v-progress-circular>
                </div>
            </div>
        </v-app>
    </div>
</template>

<script>
    import store from "@/store/store";
    import { mapState } from 'vuex';
    export default {
        store,
        name: 'app',
        computed: mapState({
            loading: state=> state.loading
        })
    }
</script>

<style lang="scss">
    #single-page {
        position: relative;
        z-index: 2;
    }
    .override {
        .v-application--wrap {
            min-height: auto;
        }
    }
    .v-tabs {
        flex: none;
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
            z-index: 1;
            .v-progress-circular{
                margin: 90px;
            }
        }
    }
</style>
