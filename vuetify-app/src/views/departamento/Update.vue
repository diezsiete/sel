<template>
  <div>
    <Toolbar
      :handle-submit="onSendForm"
      :handle-reset="resetForm"
      :handle-delete="del"
    />
    <DepartamentoForm
      ref="updateForm"
      v-if="item"
      :values="item"
      :errors="violations"
    />
    <Loading :visible="isLoading || deleteLoading" />
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import { mapFields } from 'vuex-map-fields';
import DepartamentoForm from '../../components/departamento/Form.vue';
import Loading from '../../components/Loading';
import Toolbar from '../../components/Toolbar';
import UpdateMixin from '../../mixins/UpdateMixin';

const servicePrefix = 'Departamento';

export default {
  name: 'DepartamentoUpdate',
  servicePrefix,
  mixins: [UpdateMixin],
  components: {
    Loading,
    Toolbar,
    DepartamentoForm
  },

  computed: {
    ...mapFields('departamento', {
      deleteLoading: 'isLoading',
      isLoading: 'isLoading',
      error: 'error',
      updated: 'updated',
      violations: 'violations'
    }),
    ...mapGetters('departamento', ['find'])

  },

  methods: {
    ...mapActions('departamento', {
      deleteItem: 'del',
      retrieve: 'load',
      update: 'update',
    })
  }
};
</script>
