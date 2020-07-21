<template>
    <v-container grid-list-xl fluid>
        <v-layout row >
            <familiar-table
                    v-if="showTable"
                    v-model="selected"
                    :items="items"
                    :options.sync="options"
                    :loading="isLoading"
                    :total-items="totalItems"
                    :edit-handler="item => editHandler(item)"
                    :delete-handler="item => deleteHandler(item)"
            ></familiar-table>
            <div v-else>
                <h1 class="mb-0">Agregar familiar</h1>
                <familiar-form ref="createForm" :values="item" :relations-return-object="true" :entity="childName"></familiar-form>
            </div>
            <slot></slot>
        </v-layout>
    </v-container>
</template>

<script>
    import RegistroCvChildMixin from "@mixins/cv/RegistroCvChildMixin";
    import FamiliarTable from "@components/entity/cv/familiar/Table";
    import FamiliarForm from "@components/entity/cv/familiar/Form";

    export default {
        name: "FamiliarRegistro",
        mixins: [RegistroCvChildMixin],
        components: {
            FamiliarForm,
            FamiliarTable
        },
        data: () => ({
            step: 5,
            childKey: 'familiares',
            childName: 'familiar'
        }),
        methods: {
            validate() {
                return true
            },
        }
    }
</script>