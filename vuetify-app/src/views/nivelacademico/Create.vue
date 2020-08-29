<template>
  <div>
    <Toolbar :handle-submit="onSendForm" :handle-reset="resetForm"></Toolbar>
    <NivelAcademicoForm ref="createForm" :values="item" :errors="violations" />
    <Loading :visible="isLoading" />
  </div>
</template>

<script>
import { mapActions } from 'vuex';
import { createHelpers } from 'vuex-map-fields';
import NivelAcademicoForm from '../../components/nivelacademico/Form';
import Loading from '../../components/Loading';
import Toolbar from '../../components/Toolbar';
import CreateMixin from '../../mixins/CreateMixin';

const servicePrefix = 'NivelAcademico';

const { mapFields } = createHelpers({
  getterType: 'nivelacademico/getField',
  mutationType: 'nivelacademico/updateField'
});

export default {
  name: 'NivelAcademicoCreate',
  servicePrefix,
  mixins: [CreateMixin],
  components: {
    Loading,
    Toolbar,
    NivelAcademicoForm
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
    ...mapActions('nivelacademico', ['create', 'reset'])
  }
};
</script>
