import AlidationFormMixin from "@mixins/alidation/AlidationFormMixin";
import TextField from "@components/field/TextField";
import EntityField from "@components/field/EntityField";

export default {
    mixins: [AlidationFormMixin],
    components: {
        TextField,
        EntityField
    },
    computed: {
        item() {
            return this.initialValues || this.values;
        },
        violations() {
            return this.errors || {};
        },
    },
    props: {
        values: {
            type: Object,
            required: true
        },
        errors: {
            type: Object,
            default: () => {
            }
        },
        initialValues: {
            type: Object,
            default: () => {
            }
        },
        // si el formulario se muestra como tal, o es solo para visualizar la info ya ingresada
        state: {
            type: String,
            default: 'edit',
            validator: value => ['edit', 'view'].indexOf(value) !== -1
        },
        relationsReturnObject : {
            type: Boolean,
            default: false
        }
    },
}
