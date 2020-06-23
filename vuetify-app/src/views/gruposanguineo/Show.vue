<template>
  <div>
    <Toolbar :handle-delete="del">
      <template slot="left">
        <v-toolbar-title v-if="item">{{
          `${$options.servicePrefix} ${item['@id']}`
        }}</v-toolbar-title>
      </template>
    </Toolbar>

    <br />

    <div v-if="item" class="table-gruposanguineo-show">
      <v-simple-table>
        <template slot="default">
          <thead>
            <tr>
              <th>Field</th>
              <th>Value</th>

              <th>Field</th>
              <th>Value</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>{{ $t('nombre') }}</strong></td>
              <td>
                                    {{ item['nombre'] }}
              </td>
            
              <td></td>
            </tr>
            
          </tbody>
        </template>
      </v-simple-table>
    </div>

    <Loading :visible="isLoading" />
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import { mapFields } from 'vuex-map-fields';
import Loading from '../../components/Loading';
import ShowMixin from '../../mixins/ShowMixin';
import Toolbar from '../../components/Toolbar';

const servicePrefix = 'GrupoSanguineo';

export default {
  name: 'GrupoSanguineoShow',
  servicePrefix,
  components: {
      Loading,
      Toolbar
  },
  mixins: [ShowMixin],
  computed: {
    ...mapFields('gruposanguineo', {
      isLoading: 'isLoading'
    }),
    ...mapGetters('gruposanguineo', ['find'])
  },
  methods: {
    ...mapActions('gruposanguineo', {
      deleteItem: 'del',
      reset: 'reset',
      retrieve: 'load'
    })
  }
};
</script>
