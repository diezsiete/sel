<template>
    <div>
        <EstudioForm
                ref="updateForm"
                v-if="item"
                :values="item"
                :errors="violations"
        />
        <Toolbar
                :handle-submit="onSendForm"
                :handle-cancel="cancel"
                :handle-delete="del"
        />
        <Loading :visible="isLoading || deleteLoading"/>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
    import {mapFields} from 'vuex-map-fields';
    import EstudioForm from '@components/entity/cv/estudio/Form.vue';
    import Loading from '@components/Loading';
    import Toolbar from '@components/Toolbar';
    import UpdateMixin from '@mixins/UpdateMixin';

    const servicePrefix = 'Estudio';

    export default {
        name: 'EstudioUpdate',
        servicePrefix,
        mixins: [UpdateMixin],
        components: {
            Loading,
            Toolbar,
            EstudioForm
        },

        computed: {
            ...mapFields('estudio', {
                deleteLoading: 'isLoading',
                isLoading: 'isLoading',
                error: 'error',
                updated: 'updated',
                violations: 'violations'
            }),
            ...mapGetters('estudio', ['find'])

        },

        methods: {
            ...mapActions('estudio', {
                deleteItem: 'del',
                retrieve: 'load',
                update: 'update',
            })
        }
    };
</script>
