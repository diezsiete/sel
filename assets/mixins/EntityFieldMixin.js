export default {
    computed: {
        identifierValue() {
            if(typeof this.value === 'object' && this.value !== null) {
                return this.value[this.identifier]
            }
            return this.value;
        }
    },
    data: () => ({
        isLoading: false,
        fetched: false,
        search: null,
    }),
    methods: {
        fetch() {
            if (this.items.length > 0 || this.isLoading) {
                return;
            }
            this.isLoading = true;
            this.getPage()
                .finally(() => {
                    this.fetched = true;
                    this.isLoading = false;
                })
        },
        updateValue: function (value) {
            this.$emit('input', value)
        },
    },
    props: {
        value: null,
        identifier: {
            type: String,
            default: '@id'
        }
    },
    watch: {
        search(val) {
            this.fetch()
        },
    },
}