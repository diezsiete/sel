<template>
  <div>
    <h1>Create</h1>
    <Toolbar :handle-submit="onSendForm" :handle-reset="resetForm"></Toolbar>
    <EstudioForm ref="createForm" :values="item" :errors="violations" />
    <Loading :visible="isLoading" />
  </div>
</template>

<script>
import { mapActions } from 'vuex';
import { createHelpers } from 'vuex-map-fields';
import EstudioForm from '../../components/estudio/Form';
import Loading from '../../components/Loading';
import Toolbar from '../../components/Toolbar';
import CreateMixin from '../../mixins/CreateMixin';

const servicePrefix = 'Estudio';

const { mapFields } = createHelpers({
  getterType: 'estudio/getField',
  mutationType: 'estudio/updateField'
});

export default {
  name: 'EstudioCreate',
  servicePrefix,
  mixins: [CreateMixin],
  components: {
    Loading,
    Toolbar,
    EstudioForm
  },
  data() {
    return {
      item: {
        codigo:{},
        instituto: {}
      }
    };
  },
  computed: {
    ...mapFields(['error', 'isLoading', 'created', 'violations'])
  },
  methods: {
    ...mapActions('estudio', ['create', 'reset'])
  }
};
</script>
