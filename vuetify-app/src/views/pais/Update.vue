<template>
  <div>
    <Toolbar
      :handle-submit="onSendForm"
      :handle-reset="resetForm"
      :handle-delete="del"
    />
    <PaisForm
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
import PaisForm from '../../components/pais/Form.vue';
import Loading from '../../components/Loading';
import Toolbar from '../../components/Toolbar';
import UpdateMixin from '../../mixins/UpdateMixin';

const servicePrefix = 'Pais';

export default {
  name: 'PaisUpdate',
  servicePrefix,
  mixins: [UpdateMixin],
  components: {
    Loading,
    Toolbar,
    PaisForm
  },

  computed: {
    ...mapFields('pais', {
      deleteLoading: 'isLoading',
      isLoading: 'isLoading',
      error: 'error',
      updated: 'updated',
      violations: 'violations'
    }),
    ...mapGetters('pais', ['find'])

  },

  methods: {
    ...mapActions('pais', {
      deleteItem: 'del',
      retrieve: 'load',
      update: 'update',
    })
  }
};
</script>
