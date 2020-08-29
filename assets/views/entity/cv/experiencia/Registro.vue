<template>
    <v-container grid-list-xl fluid>
        <v-layout row >
            <experiencia-table
                    v-if="showTable"
                    v-model="selected"
                    :items="items"
                    :options.sync="options"
                    :loading="isLoading"
                    :total-items="totalItems"
                    :edit-handler="item => editHandler(item)"
                    :delete-handler="item => deleteHandler(item)"
            ></experiencia-table>
            <div v-else>
                <h1 class="mb-0">Agregar Experiencia laboral</h1>
                <experiencia-form ref="createForm" :values="item" :relations-return-object="true" :entity="childName"></experiencia-form>
            </div>
            <slot></slot>
        </v-layout>
    </v-container>
</template>

<script>
    import RegistroCvChildMixin from "@mixins/cv/RegistroCvChildMixin";
    import ExperienciaTable from '@components/entity/cv/experiencia/Table';
    import ExperienciaForm from '@components/entity/cv/experiencia/Form';

    export default {
        name: "ExperienciaRegistro",
        mixins: [RegistroCvChildMixin],
        components: {
            ExperienciaTable,
            ExperienciaForm
        },
        data: () => ({
            step: 3,
            childKey: 'experiencias',
            childName: 'experiencia'
        }),
    }
</script>