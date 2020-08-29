<template>
  <div>
    <Toolbar
      :handle-submit="onSendForm"
      :handle-reset="resetForm"
      :handle-delete="del"
    />
    <ExperienciaDuracionForm
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
import ExperienciaDuracionForm from '../../components/experienciaduracion/Form.vue';
import Loading from '../../components/Loading';
import Toolbar from '../../components/Toolbar';
import UpdateMixin from '../../mixins/UpdateMixin';

const servicePrefix = 'ExperienciaDuracion';

export default {
  name: 'ExperienciaDuracionUpdate',
  servicePrefix,
  mixins: [UpdateMixin],
  components: {
    Loading,
    Toolbar,
    ExperienciaDuracionForm
  },

  computed: {
    ...mapFields('experienciaduracion', {
      deleteLoading: 'isLoading',
      isLoading: 'isLoading',
      error: 'error',
      updated: 'updated',
      violations: 'violations'
    }),
    ...mapGetters('experienciaduracion', ['find'])

  },

  methods: {
    ...mapActions('experienciaduracion', {
      deleteItem: 'del',
      retrieve: 'load',
      update: 'update',
    })
  }
};
</script>
