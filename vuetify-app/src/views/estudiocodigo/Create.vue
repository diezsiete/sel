<template>
  <div>
    <Toolbar :handle-submit="onSendForm" :handle-reset="resetForm"></Toolbar>
    <EstudioCodigoForm ref="createForm" :values="item" :errors="violations" />
    <Loading :visible="isLoading" />
  </div>
</template>

<script>
import { mapActions } from 'vuex';
import { createHelpers } from 'vuex-map-fields';
import EstudioCodigoForm from '../../components/estudiocodigo/Form';
import Loading from '../../components/Loading';
import Toolbar from '../../components/Toolbar';
import CreateMixin from '../../mixins/CreateMixin';

const servicePrefix = 'EstudioCodigo';

const { mapFields } = createHelpers({
  getterType: 'estudiocodigo/getField',
  mutationType: 'estudiocodigo/updateField'
});

export default {
  name: 'EstudioCodigoCreate',
  servicePrefix,
  mixins: [CreateMixin],
  components: {
    Loading,
    Toolbar,
    EstudioCodigoForm
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
    ...mapActions('estudiocodigo', ['create', 'reset'])
  }
};
</script>
