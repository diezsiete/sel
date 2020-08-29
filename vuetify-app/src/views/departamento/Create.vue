<template>
  <div>
    <Toolbar :handle-submit="onSendForm" :handle-reset="resetForm"></Toolbar>
    <DepartamentoForm ref="createForm" :values="item" :errors="violations" />
    <Loading :visible="isLoading" />
  </div>
</template>

<script>
import { mapActions } from 'vuex';
import { createHelpers } from 'vuex-map-fields';
import DepartamentoForm from '../../components/departamento/Form';
import Loading from '../../components/Loading';
import Toolbar from '../../components/Toolbar';
import CreateMixin from '../../mixins/CreateMixin';

const servicePrefix = 'Departamento';

const { mapFields } = createHelpers({
  getterType: 'departamento/getField',
  mutationType: 'departamento/updateField'
});

export default {
  name: 'DepartamentoCreate',
  servicePrefix,
  mixins: [CreateMixin],
  components: {
    Loading,
    Toolbar,
    DepartamentoForm
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
    ...mapActions('departamento', ['create', 'reset'])
  }
};
</script>
