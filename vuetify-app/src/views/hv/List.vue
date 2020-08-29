<template>
  <div class="hv-list">
    <Toolbar :handle-add="addHandler" />

    <v-container grid-list-xl fluid>
      <v-layout row wrap>
        <v-flex sm12>
          <h1>Hv List</h1>
        </v-flex>
        <v-flex lg12>
          <DataFilter :handle-filter="onSendFilter" :handle-reset="resetFilter">
            <HvFilterForm
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
                <template slot="item.nacimiento" slot-scope="{ item }">
                  {{ formatDateTime(item['nacimiento'], 'long') }}
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
import HvFilterForm from '../../components/hv/Filter';
import DataFilter from '../../components/DataFilter';
import Toolbar from '../../components/Toolbar';

export default {
  name: 'HvList',
  servicePrefix: 'Hv',
  mixins: [ListMixin],
  components: {
    Toolbar,
    ActionCell,
    HvFilterForm,
    DataFilter
  },
  data() {
    return {
      headers: [
        { text: 'nacPais', value: 'nacPais' },
        { text: 'nacDpto', value: 'nacDpto' },
        { text: 'nacCiudad', value: 'nacCiudad' },
        { text: 'identPais', value: 'identPais' },
        { text: 'identDpto', value: 'identDpto' },
        { text: 'identCiudad', value: 'identCiudad' },
        { text: 'genero', value: 'genero' },
        { text: 'estadoCivil', value: 'estadoCivil' },
        { text: 'resiPais', value: 'resiPais' },
        { text: 'resiDpto', value: 'resiDpto' },
        { text: 'resiCiudad', value: 'resiCiudad' },
        { text: 'barrio', value: 'barrio' },
        { text: 'direccion', value: 'direccion' },
        { text: 'grupoSanguineo', value: 'grupoSanguineo' },
        { text: 'factorRh', value: 'factorRh' },
        { text: 'nacionalidad', value: 'nacionalidad' },
        { text: 'nacimiento', value: 'nacimiento' },
        { text: 'nivelAcademico', value: 'nivelAcademico' },
        { text: 'identificacion', value: 'identificacion' },
        { text: 'primerNombre', value: 'primerNombre' },
        { text: 'segundoNombre', value: 'segundoNombre' },
        { text: 'primerApellido', value: 'primerApellido' },
        { text: 'segundoApellido', value: 'segundoApellido' },
        { text: 'email', value: 'email' },
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
    ...mapGetters('hv', {
      items: 'list'
    }),
    ...mapFields('hv', {
      deletedItem: 'deleted',
      error: 'error',
      isLoading: 'isLoading',
      resetList: 'resetList',
      totalItems: 'totalItems',
      view: 'view'
    })
  },
  methods: {
    ...mapActions('hv', {
      getPage: 'fetchAll',
      deleteItem: 'del'
    })
  }
};
</script>
