<template>
    <v-container grid-list-xl fluid>
        <v-layout row wrap>
            <v-flex sm12>
                <h1>Familiares</h1>
                <p class="lead">Ingrese en el cuadro familiares, especialmente aquellos que tiene a cargo.</p>
                <v-alert type="info" outlined>
                    <strong>Atención!</strong> Si tiene hijos, por favor especifique la información de ellos en el cuadro.
                </v-alert>
            </v-flex>
            <v-flex lg12>
                <v-data-table
                        :value="selected"
                        :headers="headers"
                        :items="items"
                        :items-per-page="options.itemsPerPage"
                        :options="options"
                        :loading="loading"
                        :server-items-length="totalItems"
                        :loading-text="loadingText"
                        class="elevation-1"
                        :item-key="itemKey"
                        @update:options="onUpdateOptions"
                        @input="onSelected"
                        hide-default-footer
                >
                    <template slot="item.nombre" slot-scope="{ item }">
                        {{ item.nombre }} {{ item.primerApellido }}
                    </template>
                    <action-cell
                            slot="item.action"
                            slot-scope="props"
                            v-bind:[handleEdit]="() => editHandler(props.item)"
                            v-bind:[handleDelete]="() => deleteHandler(props.item)"
                    ></action-cell>
                </v-data-table>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import TableMixin from "@mixins/TableMixin";
    export default {
        name: "FamiliarTable",
        servicePrefix: 'Familiar',
        mixins:[TableMixin],
        data() {
            return {
                headers: [
                    {text: 'Nombre', value: 'nombre'},
                    {text: 'Parentesco', value: 'parentesco.nombre'},
                    {text: 'Ocupación', value: 'ocupacion.nombre'},
                    {text: 'Nivel académico', value: 'nivelAcademico.nombre'},
                    {
                        text: 'Acciones',
                        value: 'action',
                        sortable: false
                    }
                ],
            };
        },
    }
</script>