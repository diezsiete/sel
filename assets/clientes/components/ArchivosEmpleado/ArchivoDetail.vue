<template>
    <div class="main-container">
        <div class="title">
            <h5>{{archivoSelectedOriginalFilename}}</h5>
            <v-btn text v-on:click="close">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </div>
        <div class="attributes">
            <div class="label">Nombre</div>
            <div class="value">
                <a :href="archivoVerUrl" target="_blank">
                    {{archivo.originalFilename}} <v-icon small>mdi-open-in-new</v-icon>
                </a>
            </div>
            <div class="label">Tamaño</div>
            <div class="value">
                {{ archivo.size }}
            </div>
            <div class="label">Tipo</div>
            <div class="value">
                {{ archivo.mimeType }}
            </div>
            <div class="label">Creado en</div>
            <div class="value">
                {{ archivo.createdAtFull }}
            </div>
            <div class="label">Actualizado en</div>
            <div class="value last">
                {{ archivo.updatedAtFull }}
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        name: "ArchivoDetail",
        computed: mapState('archivosEmpleado', {
            archivo: state => state.archivoSelected,
            archivoVerUrl: state => state.archivoVerUrl,
            archivoSelectedOriginalFilename: state => state.archivoSelectedOriginalFilename
        }),
        methods: {
            close() {
                this.$store.dispatch('unselectArchivo')
            }
        },
        props: {
            enabled: {
                type: Boolean,
                default: true
            }
        }
    }
</script>

<style scoped lang="scss"  type="text/scss">
    .main-container {
        border-left: 2px solid #e5e5e5;
        color: black;
        .title {
            align-items: center;
            display: flex;
            flex-direction: row;
            padding: 16px 16px 0 24px;
            .v-btn {
                padding: 0;
                min-width: 0;
                width: 40px;
                height: 40px;
                flex-shrink: 0;
                line-height: 40px;
                border-radius: 50%;
            }
        }

        .attributes {
            padding: 24px;

            .label {
                color: rgba(0, 0, 0, 0.38);
            }

            .value {
                margin-bottom: 16px;
                overflow: hidden;
                text-overflow: ellipsis;

                a .v-icon {
                    color: inherit !important;
                }
            }
            .value.last{
                margin-bottom: 0;
            }
        }
    }

</style>