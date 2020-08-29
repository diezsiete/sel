<template>
    <div>
        <cv-form ref="createForm" :values="item" :errors="violations"/>
        <Toolbar :handle-submit="onSendForm" :handle-cancel="cancel"></Toolbar>
        <Loading :visible="isLoading"/>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';
    import {createHelpers} from 'vuex-map-fields';
    import CvForm from '@components/entity/cv/cv/Form';
    import Loading from '@components/Loading';
    import Toolbar from '@components/Toolbar';
    import CreateMixin from '@mixins/CreateMixin';

    const servicePrefix = 'Cv';

    const {mapFields} = createHelpers({
        getterType: 'cv/getField',
        mutationType: 'cv/updateField'
    });

    export default {
        name: 'CvCreate',
        servicePrefix,
        mixins: [CreateMixin],
        components: {
            Loading,
            Toolbar,
            CvForm
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
            ...mapActions('cv', ['create', 'reset'])
        }
    };
</script>
