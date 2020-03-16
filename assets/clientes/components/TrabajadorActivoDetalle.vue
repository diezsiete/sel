<template>
    <v-card>
        <v-tabs
            background-color="white"
            color="deep-purple accent-4">
            <v-tab>Nominas</v-tab>
            <v-tab>Archivos</v-tab>
            <v-tab>Seguridad social</v-tab>


            <v-tab-item key="Nominas">
                <v-card flat>
                    <listado-nomina-empleado></listado-nomina-empleado>
                </v-card>
            </v-tab-item>
            <v-tab-item key="City">
                <v-card flat>
                    <archivos-empleado></archivos-empleado>
                </v-card>
            </v-tab-item>
            <v-tab-item key="Seguridad social">
                <v-card flat>
                    <v-data-table item-key="name" class="elevation-1" loading loading-text="Loading... Please wait"></v-data-table>
                </v-card>
            </v-tab-item>

        </v-tabs>
    </v-card>
</template>

<script>
    import ListadoNominaEmpleado from "@clientes/components/ListadoNomina/ListadoNominaEmpleado";
    import ArchivosEmpleado from "@clientes/components/ArchivosEmpleado/ArchivosEmpleado";
    export default {
        name: "TrabajadorActivoDetalle",
        components: {
            ListadoNominaEmpleado,
            ArchivosEmpleado
        },
        data () {
            return {
                tab: null,
                items: [
                    { tab: 'One', content: 'Tab 1 Content' },
                    { tab: 'Two', content: 'Tab 2 Content' },
                    { tab: 'Three', content: 'Tab 3 Content' },
                    { tab: 'Four', content: 'Tab 4 Content' },
                    { tab: 'Five', content: 'Tab 5 Content' },
                    { tab: 'Six', content: 'Tab 6 Content' },
                    { tab: 'Seven', content: 'Tab 7 Content' },
                    { tab: 'Eight', content: 'Tab 8 Content' },
                    { tab: 'Nine', content: 'Tab 9 Content' },
                    { tab: 'Ten', content: 'Tab 10 Content' },
                ],
            }
        },
    }
</script>

<style scoped>

</style>