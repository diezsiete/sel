<template>
    <v-app>
        <div class="content">
            <router-view></router-view>
        </div>
    </v-app>
</template>

<script>
    import Header from './components/Header.vue'

    export default {
        name: 'app',
        props: ["convenio", "isAdmin", "empleado"],
        components: {
            Header
        },
        beforeMount() {
            const convenio = JSON.parse(this.convenio);
            const isAdmin = !!this.isAdmin;
            const empleado = this.empleado || null;
            this.$store.dispatch('bootstrap', {convenio, isAdmin, empleado})
        }
    }
</script>

<style lang="scss">
    #app{
        background-color: #ecedf0;
    }
</style>
