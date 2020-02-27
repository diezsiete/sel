<template>
    <v-card id="listado-nomina">
        <v-data-table
                :headers="headers"
                :items="resumenes"
                :items-per-page="5"
                :class="{
                    expanded: expanded.length > 0,
                    'elevation-1': true
                }"
                :search="search"
                item-key="fecha"
                :loading="isLoading"
                loading-text="Cargando... Por favor espere"
                show-expand
                :single-expand="singleExpand"
                :expanded.sync="expanded"
                @item-expanded="onItemExpanded"
                disable-sort
        >
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
                <td>{{ totales[item.fecha] ? totales[item.fecha].devengo  : '' }}</td>
                <td>{{ totales[item.fecha] ? totales[item.fecha].deduccion : '' }}</td>
                <td>{{ totales[item.fecha] ? totales[item.fecha].netos : '' }}</td>
                <td>{{ totales[item.fecha] ? totales[item.fecha].aportes : '' }}</td>
                <td>{{ totales[item.fecha] ? totales[item.fecha].bases : '' }}</td>
                <td>{{ totales[item.fecha] ? totales[item.fecha].provisionesParafiscales : '' }}</td>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "ListadoNominaEmpleado",
        data () {
            return {
                expanded: [],
                singleExpand: false,
                search: '',
                headers: [
                    { value: 'fecha', text: 'Fecha'},
                    { value: 'devengo', text: 'Devengo'},
                    { value: 'deduccion', text: 'Deduccion' },
                    { value: 'netos', text: 'Netos' },
                    { value: 'aportes', text: 'Aportes empleador' },
                    { value: 'bases', text: 'Bases' },
                    { value: 'provisionesParafiscales', text: 'Provisiones/Parafiscales'},
                    { text: '', value: 'data-table-expand' },
                ],
                isLoading: true
            }
        },
        computed: mapState('listadoNomina/empleado', {
            fecha: state => state.fecha,
            resumenes: state => state.resumenes,
            totales: state => state.totales
        }),
        methods: {
            async onItemExpanded({item, value}) {
                this.isLoading = true;
                if(value) {
                    await this.$store.dispatch('listadoNomina/empleado/modifyResumenWithDetalle', item);
                } else {
                    await this.$store.dispatch('listadoNomina/empleado/modifyDetalleWithResumen', item)
                }
                this.isLoading = false;
            },
        },
        async mounted() {
            await this.$store.dispatch('listadoNomina/empleado/requestResumenesEmpleado');
            this.isLoading = false;
        }
    }
</script>

<style lang="scss"  type="text/scss">
    #listado-nomina {
        th:not(:first-child) {
            text-align: right;
        }

        .expanded th:not(:first-child) {
            text-align: center;
        }

        .empleado-nombre{
            font-weight: bold;
        }
        .empleado {
            font-size: .9em;
        }

        tr td:not(:first-child):not(:last-child) {
            text-align: right !important;

        }

        tbody tr.v-data-table__expanded.v-data-table__expanded__row td {
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