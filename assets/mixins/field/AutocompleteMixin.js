import FieldMixin from "@mixins/field/FieldMixin";
import AlidationFieldMixin from "@mixins/alidation/AlidationFieldMixin";

export default {
    mixins: [AlidationFieldMixin, FieldMixin],
    mounted() {
        const autocomplete = this.$refs.autocomplete;
        autocomplete.activateMenu = (orig => () => {
            orig.call(autocomplete);
            this.open = autocomplete.isMenuActive;
        })(autocomplete.activateMenu);
        autocomplete.selectItem = (orig => item => {
            orig.call(autocomplete, item);
            this.open = autocomplete.isMenuActive;
        })(autocomplete.selectItem)
    },
    data: () => ({
        isLoading: false,
        fetched: false,
        search: null,
        open: false,
    }),
    methods: {
        blur() {
            if (document.hasFocus()) {
                this.open = false
            }
        }
    },
    props: {
        identifier: {
            type: String,
            default: '@id'
        },
        itemText: {
            type: String,
            default: 'nombre'
        },
        label: String,
        placeholder: {
            type: String,
            default: 'Seleccione...'
        },
        returnObject: {
            type: Boolean,
            default: false
        },
        value: null,
    },
    watch: {
        open(state) {
            this.$store.state.overflow = state;
        }
    }
}