<template>
  <v-container fluid>
    <v-row>
      <v-col cols="12" sm="6" md="6">
        <v-combobox
          v-model="item.pais"
          :items="paisSelectItems"
          :no-data-text="$t('No results')"
          :label="$t('pais')"
          item-text="name"
          item-value="@id"
          chips
        />
      </v-col>
    
      <v-col cols="12" sm="6" md="6">
        <v-combobox
          v-model="item.dpto"
          :items="dptoSelectItems"
          :no-data-text="$t('No results')"
          :label="$t('dpto')"
          item-text="name"
          item-value="@id"
          chips
        />
      </v-col>
    </v-row>
    
    <v-row>
      <v-col cols="12" sm="6" md="6">
        <v-text-field
          v-model="item.nombre"
          :label="$t('nombre')"
          type="text"
        />
      </v-col>
    
      <v-row cols="12"></v-row>
    </v-row>
    
  </v-container>
</template>

<script>
import { mapActions } from 'vuex';
import { mapFields } from 'vuex-map-fields';

export default {
  name: 'CiudadFilter',
  props: {
    values: {
      type: Object,
      required: true
    }
  },
  data() {
    return {};
  },
  mounted() {
    this.paisGetSelectItems();
    this.dptoGetSelectItems();
  },

  computed: {
    ...mapFields({
      paisSelectItems: 'pais/selectItems',
      dptoSelectItems: 'departamento/selectItems',
    }),
    // eslint-disable-next-line
    item() {
      return this.initialValues || this.values;
    }
  },
  methods: {
    ...mapActions({
      paisGetSelectItems: 'pais/fetchSelectItems',
      dptoGetSelectItems: 'departamento/fetchSelectItems',
    }),
  }
};
</script>
