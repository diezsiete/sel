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

    <div v-if="item" class="table-hv-show">
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
              <td><strong>{{ $t('nacPais') }}</strong></td>
              <td>
                                    {{ item['nacPais'].name }}
              </td>
            
              <td><strong>{{ $t('nacDpto') }}</strong></td>
              <td>
                                    {{ item['nacDpto'].name }}
              </td>
            </tr>
            
            <tr>
              <td><strong>{{ $t('nacCiudad') }}</strong></td>
              <td>
                                    {{ item['nacCiudad'].name }}
              </td>
            
              <td><strong>{{ $t('identPais') }}</strong></td>
              <td>
                                    {{ item['identPais'].name }}
              </td>
            </tr>
            
            <tr>
              <td><strong>{{ $t('identDpto') }}</strong></td>
              <td>
                                    {{ item['identDpto'].name }}
              </td>
            
              <td><strong>{{ $t('identCiudad') }}</strong></td>
              <td>
                                    {{ item['identCiudad'].name }}
              </td>
            </tr>
            
            <tr>
              <td><strong>{{ $t('genero') }}</strong></td>
              <td>
                                    {{ item['genero'].name }}
              </td>
            
              <td><strong>{{ $t('estadoCivil') }}</strong></td>
              <td>
                                    {{ item['estadoCivil'].name }}
              </td>
            </tr>
            
            <tr>
              <td><strong>{{ $t('resiPais') }}</strong></td>
              <td>
                                    {{ item['resiPais'].name }}
              </td>
            
              <td><strong>{{ $t('resiDpto') }}</strong></td>
              <td>
                                    {{ item['resiDpto'].name }}
              </td>
            </tr>
            
            <tr>
              <td><strong>{{ $t('resiCiudad') }}</strong></td>
              <td>
                                    {{ item['resiCiudad'].name }}
              </td>
            
              <td><strong>{{ $t('barrio') }}</strong></td>
              <td>
                                    {{ item['barrio'] }}
              </td>
            </tr>
            
            <tr>
              <td><strong>{{ $t('direccion') }}</strong></td>
              <td>
                                    {{ item['direccion'] }}
              </td>
            
              <td><strong>{{ $t('grupoSanguineo') }}</strong></td>
              <td>
                                    {{ item['grupoSanguineo'].name }}
              </td>
            </tr>
            
            <tr>
              <td><strong>{{ $t('factorRh') }}</strong></td>
              <td>
                                    {{ item['factorRh'].name }}
              </td>
            
              <td><strong>{{ $t('nacionalidad') }}</strong></td>
              <td>
                                    {{ item['nacionalidad'].name }}
              </td>
            </tr>
            
            <tr>
              <td><strong>{{ $t('nacimiento') }}</strong></td>
              <td>
                {{ formatDateTime(item['nacimiento'], 'long') }}              </td>
            
              <td><strong>{{ $t('nivelAcademico') }}</strong></td>
              <td>
                                    {{ item['nivelAcademico'].name }}
              </td>
            </tr>
            
            <tr>
              <td><strong>{{ $t('identificacion') }}</strong></td>
              <td>
                                    {{ item['identificacion'] }}
              </td>
            
              <td><strong>{{ $t('primerNombre') }}</strong></td>
              <td>
                                    {{ item['primerNombre'] }}
              </td>
            </tr>
            
            <tr>
              <td><strong>{{ $t('segundoNombre') }}</strong></td>
              <td>
                                    {{ item['segundoNombre'] }}
              </td>
            
              <td><strong>{{ $t('primerApellido') }}</strong></td>
              <td>
                                    {{ item['primerApellido'] }}
              </td>
            </tr>
            
            <tr>
              <td><strong>{{ $t('segundoApellido') }}</strong></td>
              <td>
                                    {{ item['segundoApellido'] }}
              </td>
            
              <td><strong>{{ $t('email') }}</strong></td>
              <td>
                                    {{ item['email'] }}
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

const servicePrefix = 'Hv';

export default {
  name: 'HvShow',
  servicePrefix,
  components: {
      Loading,
      Toolbar
  },
  mixins: [ShowMixin],
  computed: {
    ...mapFields('hv', {
      isLoading: 'isLoading'
    }),
    ...mapGetters('hv', ['find'])
  },
  methods: {
    ...mapActions('hv', {
      deleteItem: 'del',
      reset: 'reset',
      retrieve: 'load'
    })
  }
};
</script>
