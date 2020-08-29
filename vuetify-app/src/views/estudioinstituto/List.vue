<template>
  <div class="estudioinstituto-list">
    <Toolbar :handle-add="addHandler" />

    <v-container grid-list-xl fluid>
      <v-layout row wrap>
        <v-flex sm12>
          <h1>EstudioInstituto List</h1>
        </v-flex>
        <v-flex lg12>
          <DataFilter :handle-filter="onSendFilter" :handle-reset="resetFilter">
            <EstudioInstitutoFilterForm
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
import EstudioInstitutoFilterForm from '../../components/estudioinstituto/Filter';
import DataFilter from '../../components/DataFilter';
import Toolbar from '../../components/Toolbar';

export default {
  name: 'EstudioInstitutoList',
  servicePrefix: 'EstudioInstituto',
  mixins: [ListMixin],
  components: {
    Toolbar,
    ActionCell,
    EstudioInstitutoFilterForm,
    DataFilter
  },
  data() {
    return {
      headers: [
        { text: 'id', value: 'id' },
        { text: 'nombre', value: 'nombre' },
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
    ...mapGetters('estudioinstituto', {
      items: 'list'
    }),
    ...mapFields('estudioinstituto', {
      deletedItem: 'deleted',
      error: 'error',
      isLoading: 'isLoading',
      resetList: 'resetList',
      totalItems: 'totalItems',
      view: 'view'
    })
  },
  methods: {
    ...mapActions('estudioinstituto', {
      getPage: 'fetchAll',
      deleteItem: 'del'
    })
  }
};
</script>
