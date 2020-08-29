<template>
  <div>
    <Toolbar :handle-submit="onSendForm" :handle-reset="resetForm"></Toolbar>
    <FactorRhForm ref="createForm" :values="item" :errors="violations" />
    <Loading :visible="isLoading" />
  </div>
</template>

<script>
import { mapActions } from 'vuex';
import { createHelpers } from 'vuex-map-fields';
import FactorRhForm from '../../components/factorrh/Form';
import Loading from '../../components/Loading';
import Toolbar from '../../components/Toolbar';
import CreateMixin from '../../mixins/CreateMixin';

const servicePrefix = 'FactorRh';

const { mapFields } = createHelpers({
  getterType: 'factorrh/getField',
  mutationType: 'factorrh/updateField'
});

export default {
  name: 'FactorRhCreate',
  servicePrefix,
  mixins: [CreateMixin],
  components: {
    Loading,
    Toolbar,
    FactorRhForm
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
    ...mapActions('factorrh', ['create', 'reset'])
  }
};
</script>
