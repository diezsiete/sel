<template>
    <div>
        <template v-if="valid">
            <TableEntity :component="component"></TableEntity>
        </template>
        <template v-else>
            <h1>Ingrese {{ name }}</h1>
            <component v-bind:is="component"></component>
        </template>
    </div>
</template>

<script>
    import Estudio from "./Estudio";
    import TableEntity from "./TableEntity"
    import { mapState } from 'vuex';

    export default {
        name: "RegistroTableEntity",
        components: {
            Estudio,
            TableEntity
        },
        computed: {
            valid () {
                return true;
                return this.$store.state[this.component].valid
            }
        },
        props: [
            'component',
            'name'
        ]
    }
</script>

<style scoped>

</style>