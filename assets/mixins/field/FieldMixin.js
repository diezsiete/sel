export default {
    computed: {
        classes() {
            return [
                this.required && 'required',
            ]
        },
        edit() {
            return this.formState === 'edit'
        }
    },
    props: {
        formState: {
            type: String,
            default: 'edit'
        }
    }
}