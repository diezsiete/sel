<template>
    <v-container grid-list-xl fluid>
        <v-layout row wrap>
            <v-flex sm12>
                <h1>Estudios</h1>
                <p class="lead">Agregue en el cuadro los estudios que ha realizado, o que se encuentra actualmente cursando.</p>
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
                    <!--:handle-show="() => showHandler(props.item)"-->
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
        name: 'EstudioTable',
        servicePrefix: 'Estudio',
        mixins:[TableMixin],
        data() {
            return {
                headers: [
                    {text: 'Codigo', value: 'codigo.nombre'},
                    {text: 'Título', value: 'nombre'},
                    {text: 'Institución', value: 'instituto.nombre'},
                    {
                        text: 'Acciones',
                        value: 'action',
                        sortable: false
                    }
                ],
            };
        },
    };
</script>
