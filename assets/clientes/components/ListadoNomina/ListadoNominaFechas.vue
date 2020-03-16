<template>
    <v-card>
        <v-card-title>
            Nominas <v-list-item-subtitle>{{convenio.nombre}}</v-list-item-subtitle>
        </v-card-title>
        <v-data-table
                :headers="headers"
                :items="nominas"
                :search="search"
                class="elevation-1"
                item-key="fecha"
                :loading="isLoading"
                loading-text="Cargando... Por favor espere"
        >
            <template v-slot:item.action="{ item }">
                <v-btn small outlined color="indigo" :to="{
                    name: 'ListadoNominaResumen',
                    params: { convenio: convenio.codigo, fecha: item.fecha}
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
        computed: {
            ...mapState(['convenio']),
            ...mapState('listadoNomina', {
                //columns: state => state.columns,
                nominas: state => state.nominas
            }),
        },
        data () {
            return {
                isLoading: true,
                search: '',
                headers: [
                    { text: 'Codigo', value: 'codigo' },
                    { text: 'Nombre', value: 'nombre' },
                    { text: 'Fecha', value: 'fecha' },
                    { text: 'Detalle', value: 'action', sortable: false },
                ],
            }
        },
        async mounted() {
            await this.$store.dispatch('listadoNomina/requestNominas');
            this.isLoading = false;
        }
    }
</script>

<style scoped>

</style>