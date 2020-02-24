<template>
    <div class="container-fluid">
        <div class='row my-4'>
            <div class='col-12'>
                <Grid :style="{height: '280px'}"
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
    import Router from "@/router";
    import axios from 'axios';

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
        data: function () {
            return {
                items: [],
                // columns: [
                //     { field: 'ProductID'},
                //     { field: 'ProductName', title: 'Product Name' },
                //     { field: 'UnitPrice', title: 'Unit Price' }
                // ]
            };
        },
        methods: {
            createRandomData(count) {
                const productNames = ['Chai', 'Chang', 'Syrup', 'Apple', 'Orange', 'Banana', 'Lemon', 'Pineapple', 'Tea', 'Milk'];
                const unitPrices = [12.5, 10.1, 5.3, 7, 22.53, 16.22, 20, 50, 100, 120];

                return Array(count).fill({}).map((_, idx) => ({
                    ProductID: idx + 1,
                    ProductName: productNames[Math.floor(Math.random() * productNames.length)],
                    UnitPrice: unitPrices[Math.floor(Math.random() * unitPrices.length)]
                }));
            }
        },
        mounted() {
            //this.$store.dispatch('obtainConceptoColumns')
            this.$store.dispatch('obtainResumenes');
            //this.items = this.createRandomData(50);
        }
    }
</script>
