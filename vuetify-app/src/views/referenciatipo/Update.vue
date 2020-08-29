<template>
  <div>
    <Toolbar
      :handle-submit="onSendForm"
      :handle-reset="resetForm"
      :handle-delete="del"
    />
    <ReferenciaTipoForm
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
import ReferenciaTipoForm from '../../components/referenciatipo/Form.vue';
import Loading from '../../components/Loading';
import Toolbar from '../../components/Toolbar';
import UpdateMixin from '../../mixins/UpdateMixin';

const servicePrefix = 'ReferenciaTipo';

export default {
  name: 'ReferenciaTipoUpdate',
  servicePrefix,
  mixins: [UpdateMixin],
  components: {
    Loading,
    Toolbar,
    ReferenciaTipoForm
  },

  computed: {
    ...mapFields('referenciatipo', {
      deleteLoading: 'isLoading',
      isLoading: 'isLoading',
      error: 'error',
      updated: 'updated',
      violations: 'violations'
    }),
    ...mapGetters('referenciatipo', ['find'])

  },

  methods: {
    ...mapActions('referenciatipo', {
      deleteItem: 'del',
      retrieve: 'load',
      update: 'update',
    })
  }
};
</script>
