<template>
  <div>
    <Toolbar :handle-submit="onSendForm" :handle-reset="resetForm"></Toolbar>
    <ReferenciaTipoForm ref="createForm" :values="item" :errors="violations" />
    <Loading :visible="isLoading" />
  </div>
</template>

<script>
import { mapActions } from 'vuex';
import { createHelpers } from 'vuex-map-fields';
import ReferenciaTipoForm from '../../components/referenciatipo/Form';
import Loading from '../../components/Loading';
import Toolbar from '../../components/Toolbar';
import CreateMixin from '../../mixins/CreateMixin';

const servicePrefix = 'ReferenciaTipo';

const { mapFields } = createHelpers({
  getterType: 'referenciatipo/getField',
  mutationType: 'referenciatipo/updateField'
});

export default {
  name: 'ReferenciaTipoCreate',
  servicePrefix,
  mixins: [CreateMixin],
  components: {
    Loading,
    Toolbar,
    ReferenciaTipoForm
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
    ...mapActions('referenciatipo', ['create', 'reset'])
  }
};
</script>
