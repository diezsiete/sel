<template>
    <div class="text-center">
        <v-sheet color="orange lighten-2">NADA</v-sheet>
    </div>
</template>

<script>
    export default {
        name: "ArchivosBrowser",

        computed: {
            empleado() {
                return this.$store.state.empleado;
            }
            // empleadoNombre() {
            //     console.log("ArchivosBrowser.computed.empleadoNombre");
            //     return this.$store.state.empleado
            //         ? `${this.$store.state.empleado.nombreCompleto}  - ${this.$store.state.empleado.identificacion}`
            //         : "Seleccione un empleado"
            // }
        },
        methods: {
            loadFiles() {
                console.log("archivosBrowser.loadFIels workin: " + this.$store.state.empleado.identificacion)
            }
        }
    }
</script>

<style scoped>

</style>