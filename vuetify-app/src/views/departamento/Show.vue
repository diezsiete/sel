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

    <div v-if="item" class="table-departamento-show">
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
            
              <td><strong>{{ $t('pais') }}</strong></td>
              <td>
                                    {{ item['pais'].name }}
              </td>
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

const servicePrefix = 'Departamento';

export default {
  name: 'DepartamentoShow',
  servicePrefix,
  components: {
      Loading,
      Toolbar
  },
  mixins: [ShowMixin],
  computed: {
    ...mapFields('departamento', {
      isLoading: 'isLoading'
    }),
    ...mapGetters('departamento', ['find'])
  },
  methods: {
    ...mapActions('departamento', {
      deleteItem: 'del',
      reset: 'reset',
      retrieve: 'load'
    })
  }
};
</script>
