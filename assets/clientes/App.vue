<template>
    <v-app>
        <div class="content">
            <Header :project-name="'kendo'" />
            <router-view></router-view>
        </div>
    </v-app>
</template>

<script>
    import Header from './components/Header.vue'

    export default {
        name: 'app',
        props: ["convenio", "isAdmin"],
        components: {
            Header
        },
        beforeMount() {
            const convenio = JSON.parse(this.convenio);
            const isAdmin = !!this.isAdmin;
            this.$store.dispatch('bootstrap', {convenio, isAdmin})
        }
    }
</script>

<style lang="scss">
    #app{
        background-color: #ecedf0;
    }
</style>
