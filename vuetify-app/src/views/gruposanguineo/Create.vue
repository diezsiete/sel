<template>
  <div>
    <Toolbar :handle-submit="onSendForm" :handle-reset="resetForm"></Toolbar>
    <GrupoSanguineoForm ref="createForm" :values="item" :errors="violations" />
    <Loading :visible="isLoading" />
  </div>
</template>

<script>
import { mapActions } from 'vuex';
import { createHelpers } from 'vuex-map-fields';
import GrupoSanguineoForm from '../../components/gruposanguineo/Form';
import Loading from '../../components/Loading';
import Toolbar from '../../components/Toolbar';
import CreateMixin from '../../mixins/CreateMixin';

const servicePrefix = 'GrupoSanguineo';

const { mapFields } = createHelpers({
  getterType: 'gruposanguineo/getField',
  mutationType: 'gruposanguineo/updateField'
});

export default {
  name: 'GrupoSanguineoCreate',
  servicePrefix,
  mixins: [CreateMixin],
  components: {
    Loading,
    Toolbar,
    GrupoSanguineoForm
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
    ...mapActions('gruposanguineo', ['create', 'reset'])
  }
};
</script>
