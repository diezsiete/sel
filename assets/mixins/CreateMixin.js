import NotificationMixin from './NotificationMixin';
import {formatDateTime} from '@utils/dates';

export default {
    mixins: [NotificationMixin],
    methods: {
        formatDateTime,
        onCreated(item) {
            this.showMessage(`Registro creado exitosamente`);
            this.$router.push({
                name: `${this.$options.servicePrefix}List`
            });
        },
        onSendForm() {
            const createForm = this.$refs.createForm;
            createForm.$v.$touch();
            if (!createForm.$v.$invalid) {
                this.create(createForm.$v.item.$model);
            }
        },
        resetForm() {
            this.$refs.createForm.$v.$reset();
            this.item = {};
        },
        cancel() {
            this.$router.push({
                name: `${this.$options.servicePrefix}List`
            });
        }
    },
    watch: {
        created(created) {
            if (!created) {
                return;
            }
            this.onCreated(created);
        },

        error(message) {
            message && this.showError(message);
        }
    }
};
