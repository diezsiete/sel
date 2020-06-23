<template>
    <v-container grid-list-xl fluid>
        <v-layout row wrap>
            <!--<v-flex sm12>
                <h1>Estudios</h1>
                <p class="lead">Agregue en el cuadro los estudios que ha realizado, o que se encuentra actualmente cursando.</p>
            </v-flex>
            <v-flex lg12>
                <v-data-table
                        v-model="selected"
                        :headers="headers"
                        :items="items"
                        :items-per-page.sync="options.itemsPerPage"
                        :loading="isLoading"
                        loading-text="Loading..."
                        :options.sync="options"
                        :server-items-length="totalItems"
                        class="elevation-1"
                        item-key="@id"
                        show-select
                        @update:options="onUpdateOptions">
                    &lt;!&ndash;:handle-show="() => showHandler(props.item)"&ndash;&gt;
                    <ActionCell
                            slot="item.action"
                            slot-scope="props"
                            :handle-edit="() => editHandler(props.item)"
                            :handle-delete="() => deleteHandler(props.item)"
                    ></ActionCell>
                </v-data-table>
            </v-flex>-->
            <estudio-table
                    v-model="selected"
                    :items="items"
                    :options.sync="options"
                    :loading="isLoading"
                    :total-items="totalItems"
                    @update:options="onUpdateOptions"
                    :edit-handler="item => editHandler(item)"
                    :delete-handler="item => deleteHandler(item)"
            ></estudio-table>
            <Toolbar :handle-add="addHandler"/>
        </v-layout>
    </v-container>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
    import {mapFields} from 'vuex-map-fields';
    import ListMixin from '@mixins/ListMixin';
    /*import ActionCell from '@components/ActionCell';*/
    import Toolbar from '@components/Toolbar';
    import EstudioTable from '@components/entity/cv/estudio/Table'

    export default {
        name: 'EstudioList',
        servicePrefix: 'Estudio',
        mixins: [ListMixin],
        components: {
            Toolbar,
            EstudioTable
            /*ActionCell,*/
        },
        data() {
            return {
                /*headers: [
                    {text: 'Codigo', value: 'codigo.nombre'},
                    {text: 'Título', value: 'nombre'},
                    {text: 'Institución', value: 'instituto.nombre'},
                    {
                        text: 'Actions',
                        value: 'action',
                        sortable: false
                    }
                ],*/
                selected: []
            };
        },
        computed: {
            ...mapGetters('estudio', {
                items: 'list'
            }),
            ...mapFields('estudio', {
                deletedItem: 'deleted',
                error: 'error',
                isLoading: 'isLoading',
                resetList: 'resetList',
                totalItems: 'totalItems',
                view: 'view'
            })
        },
        methods: {
            getPage(params) {
                params.hv = this.$store.state.cvIri;
                return this.$store.dispatch('estudio/fetchAll', params)
            },
            ...mapActions('estudio', {
                deleteItem: 'del'
            })
        }
    };
</script>
