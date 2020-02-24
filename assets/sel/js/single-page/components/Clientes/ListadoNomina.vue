<template>
    <div class="container-fluid">
        <div class='row my-4'>
            <div class='col-12'>
                <Grid
                      :data-items="rows"
                      :columns="columns">
                    <grid-no-records>
                        Cargando información
                    </grid-no-records>
                </Grid>
            </div>
        </div>
    </div>
</template>
<script>
    import { Grid, GridNoRecords } from '@progress/kendo-vue-grid'
    import store from "@/store/clientes-store";
    import { mapState } from 'vuex';

    export default {
        name: "ListadoNomina",
        store,
        components: {
            Grid,
            GridNoRecords
        },
        computed: mapState({
            columns: state => state.columns,
            rows: state => state.rows
        }),
        mounted() {
            //this.$store.dispatch('obtainConceptoColumns')
            this.$store.dispatch('obtainResumenes');
        }
    }
</script>
