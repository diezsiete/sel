<template>
  <div>
    <Toolbar :handle-submit="onSendForm" :handle-reset="resetForm"></Toolbar>
    <ExperienciaDuracionForm ref="createForm" :values="item" :errors="violations" />
    <Loading :visible="isLoading" />
  </div>
</template>

<script>
import { mapActions } from 'vuex';
import { createHelpers } from 'vuex-map-fields';
import ExperienciaDuracionForm from '../../components/experienciaduracion/Form';
import Loading from '../../components/Loading';
import Toolbar from '../../components/Toolbar';
import CreateMixin from '../../mixins/CreateMixin';

const servicePrefix = 'ExperienciaDuracion';

const { mapFields } = createHelpers({
  getterType: 'experienciaduracion/getField',
  mutationType: 'experienciaduracion/updateField'
});

export default {
  name: 'ExperienciaDuracionCreate',
  servicePrefix,
  mixins: [CreateMixin],
  components: {
    Loading,
    Toolbar,
    ExperienciaDuracionForm
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
    ...mapActions('experienciaduracion', ['create', 'reset'])
  }
};
</script>
