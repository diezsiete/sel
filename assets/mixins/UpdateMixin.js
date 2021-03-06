import NotificationMixin from './NotificationMixin';
import {formatDateTime} from '@utils/dates';

export default {
    mixins: [NotificationMixin],
    data() {
        return {
            item: {}
        };
    },
    created() {
        this.retrieveEntity();
    },
    beforeDestroy() {
        this.reset();
    },
    computed: {
        retrieved() {
            return this.find(decodeURIComponent(this.$route.params.id));
        }
    },
    methods: {
        del() {
            this.deleteItem(this.retrieved).then(() => {
                this.showMessage(`Elemento eliminado exitosamente`);
                this.$router
                    .push({name: `${this.$options.servicePrefix}List`})
                    .catch(() => {
                    });
            });
        },
        formatDateTime,
        reset() {
            this.$refs.updateForm.$v.$reset();
            //this.createReset();
        },

        onSendForm() {
            const updateForm = this.$refs.updateForm;
            updateForm.$v.$touch();

            if (!updateForm.$v.$invalid) {
                this.update(updateForm.$v.item.$model);
            }
        },
        onUpdated() {
            this.showMessage('Registro actualizado exitosamente');
            this.$router.push({
                name: `${this.$options.servicePrefix}List`
            });
        },

        resetForm() {
            this.$refs.updateForm.$v.$reset();
            this.item = {...this.retrieved};
        },
        cancel() {
            this.$router.push({
                name: `${this.$options.servicePrefix}List`
            });
        },
        retrieveEntity() {
            this.retrieve(decodeURIComponent(this.$route.params.id));
        }
    },
    watch: {
        deleted(deleted) {
            if (!deleted) {
                return;
            }
            this.$router
                .push({name: `${this.$options.servicePrefix}List`})
                .catch(() => {
                });
        },

        error(message) {
            message && this.showError(message);
        },

        deleteError(message) {
            message && this.showError(message);
        },

        updated(val) {
            if(val) {
                this.onUpdated(val)
            }
        },

        retrieved(val) {
            this.item = {...val};
        }
    }
};
