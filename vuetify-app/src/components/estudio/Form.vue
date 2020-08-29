<template>
  <v-form>
    <v-container fluid>
      <v-row>
        <v-col cols="12">
          <h1>Estudio form</h1>
        </v-col>
        <v-col cols="12">
          <!-- @blur="$v.primerNombre.$touch()" -->
          <v-text-field label="Area de estudio" v-model="values.codigo.nombre">
          </v-text-field>
        </v-col>
        <v-col cols="12">
          <v-text-field label="Título" v-model="values.nombre" >
          </v-text-field>
        </v-col>
        <v-col cols="12">
          <v-text-field label="Institución" v-model="values.instituto.nombre">
          </v-text-field>
        </v-col>
      </v-row>
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
  name: 'EstudioForm',
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
