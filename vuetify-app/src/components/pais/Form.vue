<template>
  <v-form>
    <v-container fluid>
    </v-container>
  </v-form>
</template>

<script>
import has from 'lodash/has';
import { validationMixin } from 'vuelidate';
import { required } from 'vuelidate/lib/validators';
import { mapActions } from 'vuex';
import { mapFields } from 'vuex-map-fields';

export default {
  name: 'PaisForm',
  mixins: [validationMixin],
  props: {
    values: {
      type: Object,
      required: true
    },

    errors: {
      type: Object,
      default: () => {}
    },

    initialValues: {
      type: Object,
      default: () => {}
    }
  },
  mounted() {
  },
  data() {
    return {
    };
  },
  computed: {

    // eslint-disable-next-line
    item() {
      return this.initialValues || this.values;
    },


    violations() {
      return this.errors || {};
    }
  },
  methods: {
  },
  validations: {
    item: {
    }
  }
};
</script>
