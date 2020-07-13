<template>
    <v-container grid-list-xl fluid>
        <v-layout row wrap>
            <v-flex sm12>
                <h1>Experiencia laboral</h1>
                <p class="lead">Ingrese en el cuadro la experiencia laboral que tenga.</p>
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
        name: "ExperienciaTable",
        mixins: [TableMixin],
        data: () =>  ({
            headers: [
                {text: 'Nombre de la empresa', value: 'empresa'},
                {text: 'Cargo', value: 'cargo'},
                {text: 'Area de experiencia', value: 'area.nombre'},
                {text: 'Duración', value: 'duracion.nombre'},
                {
                    text: 'Acciones',
                    value: 'action',
                    sortable: false
                }
            ],
        }),
    }
</script>