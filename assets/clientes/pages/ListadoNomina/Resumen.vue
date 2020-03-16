<template>
    <listado-nomina></listado-nomina>
</template>

<script>
    import ListadoNomina from "@clientes/components/ListadoNomina/ListadoNomina";
    export default {
        name: "Resumen",
        components: {
            ListadoNomina
        },
        created () {
            // fetch the data when the view is created and the data is
            // already being observed
            this.fetchData()
        },
        watch: {
            // call again the method if the route changes
            '$route': 'fetchData'
        },
        methods: {
            fetchData() {
                this.$store.dispatch('listadoNomina/updateFecha', this.$route.params.fecha)
            }
        }
    }
</script>

<style scoped>

</style>