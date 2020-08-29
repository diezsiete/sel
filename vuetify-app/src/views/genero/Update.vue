<template>
  <div>
    <Toolbar
      :handle-submit="onSendForm"
      :handle-reset="resetForm"
      :handle-delete="del"
    />
    <GeneroForm
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
import GeneroForm from '../../components/genero/Form.vue';
import Loading from '../../components/Loading';
import Toolbar from '../../components/Toolbar';
import UpdateMixin from '../../mixins/UpdateMixin';

const servicePrefix = 'Genero';

export default {
  name: 'GeneroUpdate',
  servicePrefix,
  mixins: [UpdateMixin],
  components: {
    Loading,
    Toolbar,
    GeneroForm
  },

  computed: {
    ...mapFields('genero', {
      deleteLoading: 'isLoading',
      isLoading: 'isLoading',
      error: 'error',
      updated: 'updated',
      violations: 'violations'
    }),
    ...mapGetters('genero', ['find'])

  },

  methods: {
    ...mapActions('genero', {
      deleteItem: 'del',
      retrieve: 'load',
      update: 'update',
    })
  }
};
</script>
