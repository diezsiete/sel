<template>
    <div>
        <h1 class="mb-0">Datos básicos</h1>
        <cv-form
                ref="updateForm"
                v-if="item"
                :values="item"
                :errors="violations"
        />
        <toolbar :handle-submit="onSendForm"/>
        <Loading :visible="isLoading || deleteLoading"/>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
    import {mapFields} from 'vuex-map-fields';
    import CvForm from '@components/entity/cv/cv/Form.vue';
    import Loading from '@components/Loading';
    import Toolbar from '@components/Toolbar';
    import UpdateMixin from '@mixins/UpdateMixin';

    const servicePrefix = 'Cv';

    export default {
        name: 'CvUpdate',
        servicePrefix,
        mixins: [UpdateMixin],
        components: {
            Loading,
            Toolbar,
            CvForm
        },
        computed: {
            ...mapFields('cv', {
                deleteLoading: 'isLoading',
                isLoading: 'isLoading',
                error: 'error',
                updated: 'updated',
                violations: 'violations'
            }),
            ...mapGetters('cv', ['find']),
            retrieved() {
                return this.find(this.$store.state.cvIri);
            }
        },
        methods: {
            ...mapActions('cv', {
                deleteItem: 'del',
                retrieve: 'load',
                update: 'update',
            }),
            retrieveEntity() {
                 this.retrieve(this.$store.state.cvIri);
            },
            onUpdated() {
                this.showMessage('Registro actualizado exitosamente');
            },
        }
    };
</script>
