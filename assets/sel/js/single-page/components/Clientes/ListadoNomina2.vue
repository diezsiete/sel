<template>
    <v-card>
        <v-card-title>
            Nutrition
            <v-spacer></v-spacer>
            <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Buscar"
                    single-line
                    hide-details
            ></v-text-field>
        </v-card-title>
        <v-data-table
                ref="dataTable"
                :headers="headers"
                :items="rows"
                :items-per-page="5"
                :disable-pagination="disablePagination"
                class="elevation-1"
                :search="search"
                hide-default-footer
                item-key="empleado"
                loading
                loading-text="Cargando... Por favor espere"
                show-expand
                :single-expand="singleExpand"
                :expanded.sync="expanded"
                @item-expanded="onItemExpanded"
        >
            <template v-slot:expanded-item="{ headers }">
                <td :colspan="headers.length">Peek-a-boo!</td>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
    import store from "@/store/clientes-store";
    import {mapState} from "vuex";

    export default {
        name: "ListadoNomina2",
        store,
        data () {
            return {
                expanded: [],
                singleExpand: true,
                search: '',
                disablePagination: true,
                headers: [
                    { value: 'empleado', text: 'Empleado'},
                    { value: 'devengo', text: 'Devengo'},
                    { value: 'deduccion', text: 'Deduccion' },
                    { value: 'netos', text: 'Netos' },
                    { value: 'aportes', text: 'Aportes empleador' },
                    { value: 'bases', text: 'Bases' },
                    { value: 'provisionesParafiscales', text: 'Provisiones/Parafiscales'},
                    { text: '', value: 'data-table-expand' },
                ],
                headers2: [
                    {
                        text: 'Dessert (100g serving)',
                        align: 'left',
                        sortable: false,
                        value: 'name',
                    },
                    { text: 'Calories', value: 'calories' },
                    { text: 'Fat (g)', value: 'fat' },
                    { text: 'Carbs (g)', value: 'carbs' },
                    { text: 'Protein (g)', value: 'protein' },
                    { text: 'Iron (%)', value: 'iron' },
                    { text: '', value: 'data-table-expand' },
                ],
                desserts: [
                    {
                        name: 'Frozen Yogurt',
                        calories: 159,
                        fat: 6.0,
                        carbs: 24,
                        protein: 4.0,
                        iron: '1%',
                    },
                    {
                        name: 'Ice cream sandwich',
                        calories: 237,
                        fat: 9.0,
                        carbs: 37,
                        protein: 4.3,
                        iron: '1%',
                    },
                    {
                        name: 'Eclair',
                        calories: 262,
                        fat: 16.0,
                        carbs: 23,
                        protein: 6.0,
                        iron: '7%',
                    },
                    {
                        name: 'Cupcake',
                        calories: 305,
                        fat: 3.7,
                        carbs: 67,
                        protein: 4.3,
                        iron: '8%',
                    },
                    {
                        name: 'Gingerbread',
                        calories: 356,
                        fat: 16.0,
                        carbs: 49,
                        protein: 3.9,
                        iron: '16%',
                    },
                    {
                        name: 'Jelly bean',
                        calories: 375,
                        fat: 0.0,
                        carbs: 94,
                        protein: 0.0,
                        iron: '0%',
                    },
                    {
                        name: 'Lollipop',
                        calories: 392,
                        fat: 0.2,
                        carbs: 98,
                        protein: 0,
                        iron: '2%',
                    },
                    {
                        name: 'Honeycomb',
                        calories: 408,
                        fat: 3.2,
                        carbs: 87,
                        protein: 6.5,
                        iron: '45%',
                    },
                    {
                        name: 'Donut',
                        calories: 452,
                        fat: 25.0,
                        carbs: 51,
                        protein: 4.9,
                        iron: '22%',
                    },
                    {
                        name: 'KitKat',
                        calories: 518,
                        fat: 26.0,
                        carbs: 65,
                        protein: 7,
                        iron: '6%',
                    },
                ],
            }
        },
        computed: mapState({
            //columns: state => state.columns,
            rows: state => state.rows
        }),
        methods: {
            async onItemExpanded({item, value}) {
                if(value) {
                    console.log(this.expanded);
                }
            }
        },
        async mounted() {
            //this.$store.dispatch('obtainConceptoColumns')
            await this.$store.dispatch('obtainResumenes');
            //const dt = this.$refs.dataTable;
        }
    }
</script>

<style scoped>

</style>