<template>
    <v-card id="listado-nomina">
        <v-card-title>
            Nomina {{ fecha }}
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
                :items="resumenes"
                :items-per-page="5"
                :disable-pagination="disablePagination"
                :class="{
                    expanded: expanded.length > 0,
                    'elevation-1': true
                }"
                :search="search"
                hide-default-footer
                item-key="nombre"
                :loading="isLoading"
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
    import {mapState} from "vuex";

    export default {
        name: "ListadoNomina",
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
                isLoading: true
            }
        },
        computed: mapState('listadoNomina', {
            fecha: state => state.fecha,
            resumenes: state => state.resumenes,
            totales: state => state.totales
        }),
        methods: {
            async onItemExpanded({item, value}) {
                this.isLoading = true;
                if(value) {
                    await this.$store.dispatch('listadoNomina/modifyResumenWithDetalle', item);
                } else {
                    await this.$store.dispatch('listadoNomina/modifyDetalleWithResumen', item)
                }
                this.isLoading = false;
            },
        },
        async mounted() {
            await this.$store.dispatch('listadoNomina/requestResumenes');
            this.isLoading = false;
            //const dt = this.$refs.dataTable;
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