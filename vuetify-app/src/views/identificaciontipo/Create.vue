<template>
  <div>
    <Toolbar :handle-submit="onSendForm" :handle-reset="resetForm"></Toolbar>
    <IdentificacionTipoForm ref="createForm" :values="item" :errors="violations" />
    <Loading :visible="isLoading" />
  </div>
</template>

<script>
import { mapActions } from 'vuex';
import { createHelpers } from 'vuex-map-fields';
import IdentificacionTipoForm from '../../components/identificaciontipo/Form';
import Loading from '../../components/Loading';
import Toolbar from '../../components/Toolbar';
import CreateMixin from '../../mixins/CreateMixin';

const servicePrefix = 'IdentificacionTipo';

const { mapFields } = createHelpers({
  getterType: 'identificaciontipo/getField',
  mutationType: 'identificaciontipo/updateField'
});

export default {
  name: 'IdentificacionTipoCreate',
  servicePrefix,
  mixins: [CreateMixin],
  components: {
    Loading,
    Toolbar,
    IdentificacionTipoForm
  },
  data() {
    return {
      item: {}
    };
  },
  computed: {
    ...mapFields(['error', 'isLoading', 'created', 'violations'])
  },
  methods: {
    ...mapActions('identificaciontipo', ['create', 'reset'])
  }
};
</script>
