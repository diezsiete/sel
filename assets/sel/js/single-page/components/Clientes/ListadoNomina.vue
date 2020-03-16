<template>
    <v-card id="listado-nomina">
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
                item-key="nombre"
                loading
                loading-text="Cargando... Por favor espere"
                show-expand
                :single-expand="singleExpand"
                :expanded.sync="expanded"
                @item-expanded="onItemExpanded"
                disable-sort
        >
            <template v-slot:item.empleado="{item, value}">
                <span class="empleado-nombre">{{item.nombre}}</span><br>
                <span class="empleado">{{item.identificacion}}</span>
            </template>

            <template v-slot:item.devengo="{value}">
                <div v-if="Array.isArray(value)" class="concepto">
                    <div v-for="concepto in value">
                        <div class="nombre">{{concepto.nombre}}</div>
                        <div class="valor">{{concepto.valor}}</div>
                    </div>
                </div>
                <span v-else>{{value}}</span>
            </template>
            <template v-slot:item.deduccion="{value}">
                <div v-if="Array.isArray(value)" class="concepto">
                    <div v-for="concepto in value">
                        <div class="nombre">{{concepto.nombre}}</div>
                        <div class="valor">{{concepto.valor}}</div>
                    </div>
                </div>
                <span v-else>{{value}}</span>
            </template>
            <template v-slot:item.netos="{value}">
                <div v-if="Array.isArray(value)" class="concepto">
                    <div v-for="concepto in value">
                        <div class="nombre">{{concepto.nombre}}</div>
                        <div class="valor">{{concepto.valor}}</div>
                    </div>
                </div>
                <span v-else>{{value}}</span>
            </template>
            <template v-slot:item.aportes="{value}">
                <div v-if="Array.isArray(value)">
                    <div v-for="concepto in value" class="concepto">
                        <div class="nombre">{{concepto.nombre}}</div>
                        <div class="valor">{{concepto.valor}}</div>
                    </div>
                </div>
                <span v-else>{{value}}</span>
            </template>
            <template v-slot:item.bases="{value}">
                <div v-if="Array.isArray(value)">
                    <div v-for="concepto in value" class="concepto">
                        <div class="nombre">{{concepto.nombre}}</div>
                        <div class="valor">{{concepto.valor}}</div>
                    </div>
                </div>
                <span v-else>{{value}}</span>
            </template>
            <template v-slot:item.provisionesParafiscales="{value}">
                <div v-if="Array.isArray(value)">
                    <div v-for="concepto in value" class="concepto">
                        <div class="nombre">{{concepto.nombre}}</div>
                        <div class="valor">{{concepto.valor}}</div>
                    </div>
                </div>
                <span v-else>{{value}}</span>
            </template>

            <template v-slot:expanded-item="{ item, headers }">
                <td><strong>Totales</strong></td>
                <td>{{ totales[item.identificacion] ? totales[item.identificacion].devengo  : '' }}</td>
                <td>{{ totales[item.identificacion] ? totales[item.identificacion].deduccion : '' }}</td>
                <td>{{ totales[item.identificacion] ? totales[item.identificacion].netos : '' }}</td>
                <td>{{ totales[item.identificacion] ? totales[item.identificacion].aportes : '' }}</td>
                <td>{{ totales[item.identificacion] ? totales[item.identificacion].bases : '' }}</td>
                <td>{{ totales[item.identificacion] ? totales[item.identificacion].provisionesParafiscales : '' }}</td>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
    import store from "@/store/clientes-store";
    import {mapState} from "vuex";

    export default {
        name: "ListadoNomina",
        store,
        data () {
            return {
                expanded: [],
                singleExpand: false,
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
            }
        },
        computed: mapState({
            //columns: state => state.columns,
            rows: state => state.rows,
            totales: state => state.totales
        }),
        methods: {
            async onItemExpanded({item, value}) {
                if(value) {
                    await this.$store.dispatch('modifyResumenWithDetalle', item);
                }
            },
        },
        async mounted() {
            //this.$store.dispatch('obtainConceptoColumns')
            await this.$store.dispatch('obtainResumenes');
            //const dt = this.$refs.dataTable;
        }
    }
</script>

<style lang="scss"  type="text/scss">
    #listado-nomina {
        /*.v-progress-linear__indeterminate .long{*/
        /*    background-color: #0ae4ff;*/
        /*}*/

        th {
            text-align: center;
        }
        .empleado-nombre{
            font-weight: bold;
        }
        .empleado {
            font-size: .9em;
        }

        tr td:not(:first-child) {
            text-align: right;
            padding-right: 0;
        }

        tbody tr:not(.v-data-table__expanded__content) td {
            vertical-align: top;

            .concepto {
                .nombre {
                    font-size: .8em;
                    /*background-color: rebeccapurple;*/
                    float: left;
                    width: 40%;
                    clear: both;
                    text-align: left;
                    line-height: normal;
                    padding: 5px 0;
                }
                .valor {
                    /*background-color: #0ae4ff;*/
                    float: right;
                    width: 60%;
                    white-space: nowrap;
                }
            }
        }
    }

</style>