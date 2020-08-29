<template>
  <div>
    <Toolbar :handle-submit="onSendForm" :handle-reset="resetForm"></Toolbar>
    <EstudioInstitutoForm ref="createForm" :values="item" :errors="violations" />
    <Loading :visible="isLoading" />
  </div>
</template>

<script>
import { mapActions } from 'vuex';
import { createHelpers } from 'vuex-map-fields';
import EstudioInstitutoForm from '../../components/estudioinstituto/Form';
import Loading from '../../components/Loading';
import Toolbar from '../../components/Toolbar';
import CreateMixin from '../../mixins/CreateMixin';

const servicePrefix = 'EstudioInstituto';

const { mapFields } = createHelpers({
  getterType: 'estudioinstituto/getField',
  mutationType: 'estudioinstituto/updateField'
});

export default {
  name: 'EstudioInstitutoCreate',
  servicePrefix,
  mixins: [CreateMixin],
  components: {
    Loading,
    Toolbar,
    EstudioInstitutoForm
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
    ...mapActions('estudioinstituto', ['create', 'reset'])
  }
};
</script>
