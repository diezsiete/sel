<template>
    <div id="root">
        <div class="content">
            <Header :project-name="'kendo'" />
            <router-view></router-view>
        </div>
        <div class="footer">
            <Footer />
        </div>
    </div>
</template>

<script>
    import Header from './components/Header.vue'
    import Footer from './components/Footer.vue'

    export default {
        name: 'app',
        props: ["convenio", "isAdmin"],
        components: {
            Header,
            Footer
        },
        beforeMount() {
            const convenio = JSON.parse(this.convenio);
            const isAdmin = !!this.isAdmin;
            this.$store.dispatch('bootstrap', {convenio, isAdmin})
        }
    }
</script>

<style lang="scss">
    /*@import './assets/styles/app.scss';*/
</style>
