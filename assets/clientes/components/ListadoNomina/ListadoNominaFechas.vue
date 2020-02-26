<template>
    <v-card>
        <v-card-title>
            Nominas
        </v-card-title>
        <v-data-table
                :headers="headers"
                :items="nominas"
                :search="search"
                class="elevation-1"
                item-key="fecha"
                loading loading-text="Cargando... Por favor espere"
        >
            <template v-slot:item.action="{ item }">
                <v-btn small outlined color="indigo" :to="{
                    name: 'ListadoNominaResumen',
                    params: { convenio: 'ALMLAN', fecha: item.fecha}
                }">
                    <v-icon>navigate_next</v-icon>
                </v-btn>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
    import { mapState } from 'vuex'

    export default {
        name: "ListadoNominaFechas",
        computed: mapState('listadoNomina', {
            //columns: state => state.columns,
            nominas: state => state.nominas,
        }),
        data () {
            return {
                search: '',
                headers: [
                    { text: 'Codigo', value: 'codigo' },
                    { text: 'Nombre', value: 'nombre' },
                    { text: 'Fecha', value: 'fecha' },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
            }
        },
        mounted() {
            this.$store.dispatch('listadoNomina/requestNominas');
        }
    }
</script>

<style scoped>

</style>