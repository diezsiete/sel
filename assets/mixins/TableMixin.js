import ActionCell from '@components/ActionCell';

export default {
    components: {
        ActionCell,
    },
    computed: {
        handleEdit() {
            return !!this.editHandler ? 'handle-edit' : null
        },
        handleDelete() {
            return !!this.deleteHandler ? 'handle-delete' : null
        }
    },

    methods: {
        onUpdateOptions(props) {
            this.$emit('update:options', props)
        },
        onSelected(value) {
            this.$emit('input', value)
        }
    },
    props: {
        items:  {
            required: true,
            type: Array
        },
        selected: Array,
        options: Object,
        loading: Boolean,
        loadingText: {
            type: String,
            default: 'Loading...'
        },
        totalItems: Number,
        itemKey: {
            type: String,
            default: "@id"
        },
        editHandler: Function,
        deleteHandler: Function
    }
};
