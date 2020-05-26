export default {
    methods: {
        assign(component, field, value) {
            this.$store.commit('UPDATE_CV', {module: component, field, value, index: this.index});
            this.validate(component, field)
        },
    },
    props: {
        index: {
            type: Number,
            default: 0
        },
    }
}