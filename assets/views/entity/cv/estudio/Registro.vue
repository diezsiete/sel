<template>
    <v-container grid-list-xl fluid>
        <v-layout row >
            <estudio-table
                    v-if="showTable"
                    v-model="selected"
                    :items="items"
                    :options.sync="options"
                    :loading="isLoading"
                    :total-items="totalItems"
                    :edit-handler="item => editHandler(item)"
                    :delete-handler="item => deleteHandler(item)"
            ></estudio-table>
            <div v-else>
                <h1 class="mb-0">Crear estudio</h1>
                <estudio-form ref="createForm" :values="item" :relations-return-object="true" :entity="childName"></estudio-form>
            </div>
            <slot></slot>
        </v-layout>
    </v-container>
</template>

<script>
    import RegistroCvChildMixin from "@mixins/cv/RegistroCvChildMixin";
    import EstudioTable from "@components/entity/cv/estudio/Table";
    import EstudioForm from "@components/entity/cv/estudio/Form";

    export default {
        name: "EstudioRegistro",
        mixins: [RegistroCvChildMixin],
        components: {
            EstudioTable,
            EstudioForm
        },
        data: () => ({
            step: 2,
            childKey: 'estudios',
            childName: 'estudio'
        }),
    }
</script>