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

    <div v-if="item" class="table-estudio-show">
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
              <td><strong>{{ $t('codigo') }}</strong></td>
              <td>
                                    {{ item['codigo'].name }}
              </td>
            
              <td><strong>{{ $t('nombre') }}</strong></td>
              <td>
                                    {{ item['nombre'] }}
              </td>
            </tr>
            
            <tr>
              <td><strong>{{ $t('instituto') }}</strong></td>
              <td>
                                    {{ item['instituto'].name }}
              </td>
            
              <td><strong>{{ $t('fin') }}</strong></td>
              <td>
                {{ formatDateTime(item['fin'], 'long') }}              </td>
            </tr>
            
            <tr>
              <td><strong>{{ $t('institutoNombreAlt') }}</strong></td>
              <td>
                                    {{ item['institutoNombreAlt'] }}
              </td>
            
              <td><strong>{{ $t('anoEstudio') }}</strong></td>
              <td>
                {{ $n(item['anoEstudio']) }}              </td>
            </tr>
            
            <tr>
              <td><strong>{{ $t('horasEstudio') }}</strong></td>
              <td>
                {{ $n(item['horasEstudio']) }}              </td>
            
              <td><strong>{{ $t('graduado') }}</strong></td>
              <td>
                                    {{ item['graduado'] }}
              </td>
            </tr>
            
            <tr>
              <td><strong>{{ $t('semestresAprobados') }}</strong></td>
              <td>
                {{ $n(item['semestresAprobados']) }}              </td>
            
              <td><strong>{{ $t('cancelo') }}</strong></td>
              <td>
                                    {{ item['cancelo'] }}
              </td>
            </tr>
            
            <tr>
              <td><strong>{{ $t('numeroTarjeta') }}</strong></td>
              <td>
                                    {{ item['numeroTarjeta'] }}
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

const servicePrefix = 'Estudio';

export default {
  name: 'EstudioShow',
  servicePrefix,
  components: {
      Loading,
      Toolbar
  },
  mixins: [ShowMixin],
  computed: {
    ...mapFields('estudio', {
      isLoading: 'isLoading'
    }),
    ...mapGetters('estudio', ['find'])
  },
  methods: {
    ...mapActions('estudio', {
      deleteItem: 'del',
      reset: 'reset',
      retrieve: 'load'
    })
  }
};
</script>
