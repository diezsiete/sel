<template>
    <v-container grid-list-xl fluid>
        <v-layout row wrap>
            <v-flex sm12>
                <h1 class="mb-0">Crear estudio</h1>
                <EstudioForm ref="createForm" :values="item" :errors="violations"/>
                <Toolbar :handle-submit="onSendForm" :handle-cancel="cancel"></Toolbar>
                <Loading :visible="isLoading"/>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import {mapActions} from 'vuex';
    import {createHelpers} from 'vuex-map-fields';
    import EstudioForm from '@components/entity/cv/estudio/Form';
    import Loading from '@components/Loading';
    import Toolbar from '@components/Toolbar';
    import CreateMixin from '@mixins/CreateMixin';

    const servicePrefix = 'Estudio';

    const {mapFields} = createHelpers({
        getterType: 'estudio/getField',
        mutationType: 'estudio/updateField'
    });

    export default {
        name: 'EstudioCreate',
        servicePrefix,
        mixins: [CreateMixin],
        components: {
            Loading,
            Toolbar,
            EstudioForm
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
            create(values) {
                values.hv = this.$store.state.cvIri;
                return this.$store.dispatch('estudio/create', values)
            },
            ...mapActions('estudio', ['reset'])
        }
    };
</script>
