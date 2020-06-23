<template>
  <div class="estudio-list">
    <Toolbar :handle-add="addHandler" />

    <v-container grid-list-xl fluid>
      <v-layout row wrap>
        <v-flex sm12>
          <h1>Estudio List</h1>
        </v-flex>
        <v-flex lg12>
          <DataFilter :handle-filter="onSendFilter" :handle-reset="resetFilter">
            <EstudioFilterForm
              ref="filterForm"
              :values="filters"
              slot="filter"
            />
          </DataFilter>

          <br />

          <v-data-table
            v-model="selected"
            :headers="headers"
            :items="items"
            :items-per-page.sync="options.itemsPerPage"
            :loading="isLoading"
            :loading-text="$t('Loading...')"
            :options.sync="options"
            :server-items-length="totalItems"
            class="elevation-1"
            item-key="@id"
            show-select
            @update:options="onUpdateOptions"
          >
                <template slot="item.fin" slot-scope="{ item }">
                  {{ formatDateTime(item['fin'], 'long') }}
                </template>
                <template slot="item.anoEstudio" slot-scope="{ item }">
                  {{ $n(item['anoEstudio']) }}
                </template>
                <template slot="item.horasEstudio" slot-scope="{ item }">
                  {{ $n(item['horasEstudio']) }}
                </template>
                <template slot="item.semestresAprobados" slot-scope="{ item }">
                  {{ $n(item['semestresAprobados']) }}
                </template>

            <ActionCell
              slot="item.action"
              slot-scope="props"
              :handle-show="() => showHandler(props.item)"
              :handle-edit="() => editHandler(props.item)"
              :handle-delete="() => deleteHandler(props.item)"
            ></ActionCell>
          </v-data-table>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import { mapFields } from 'vuex-map-fields';
import ListMixin from '../../mixins/ListMixin';
import ActionCell from '../../components/ActionCell';
import EstudioFilterForm from '../../components/estudio/Filter';
import DataFilter from '../../components/DataFilter';
import Toolbar from '../../components/Toolbar';

export default {
  name: 'EstudioList',
  servicePrefix: 'Estudio',
  mixins: [ListMixin],
  components: {
    Toolbar,
    ActionCell,
    EstudioFilterForm,
    DataFilter
  },
  data() {
    return {
      headers: [
        { text: 'codigo', value: 'codigo' },
        { text: 'nombre', value: 'nombre' },
        { text: 'instituto', value: 'instituto' },
        { text: 'fin', value: 'fin' },
        { text: 'institutoNombreAlt', value: 'institutoNombreAlt' },
        { text: 'anoEstudio', value: 'anoEstudio' },
        { text: 'horasEstudio', value: 'horasEstudio' },
        { text: 'graduado', value: 'graduado' },
        { text: 'semestresAprobados', value: 'semestresAprobados' },
        { text: 'cancelo', value: 'cancelo' },
        { text: 'numeroTarjeta', value: 'numeroTarjeta' },
        {
          text: 'Actions',
          value: 'action',
          sortable: false
        }
      ],
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
    ...mapActions('estudio', {
      getPage: 'fetchAll',
      deleteItem: 'del'
    })
  }
};
</script>
