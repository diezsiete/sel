<template>
  <v-expansion-panels v-model="filtersExpanded">
    <v-expansion-panel>
      <v-expansion-panel-header>
        Filtros

        <template slot="actions">
          <v-icon large>mdi-filter-variant</v-icon>
        </template>
      </v-expansion-panel-header>
      <v-expansion-panel-content>
        <slot name="filter"></slot>

        <v-btn color="primary" @click="handleFilter">Filtro</v-btn>
        <v-btn color="primary" class="ml-2" text @click="handleReset">Reiniciar</v-btn>
      </v-expansion-panel-content>
    </v-expansion-panel>
  </v-expansion-panels>
</template>

<script>
export default {
  name: 'DataFilter',
  props: {
    handleReset: {
      type: Function,
      required: true
    },
    handleFilter: {
      type: Function,
      required: true
    }
  },
  data() {
    return {
      filtersExpanded: false
    };
  }
};
</script>
