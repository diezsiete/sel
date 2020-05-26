import { mapState } from "vuex";
import { EventBus } from "@/cv/helpers/event-bus";

function computed(...fields){
    const computed = {
        ...mapState({
            currentComponent: state => state.currentComponent,
            validationMessages: state => state.validationMessages,
            fields () {
                return Object.keys(this.$store.state[this.$options.name].cv)
            }
        })
    };
    for(const field of fields) {
        computed[field] = {
            get() {
                return this.$store.state[this.$options.name].cv[field].value
            },
            set(value) {
                this.assign(this.$options.name, field, value)
            }
        };
        computed[field+"Errors"] = {
            get() {
                return this.$store.state[this.$options.name].cv[field].error
            },
        }
    }
    return computed
}

export default function(fields) {
    return {
        computed: {
            ...computed(...fields),
        },
        created() {
            EventBus.$on('registroValidateBeforeNextStep', currentComponent => {
                if(this.$options.name === currentComponent && this.validateAll(currentComponent)) {
                    EventBus.$emit('registroNextStep');
                }
            });
        },
        methods: {
            assign(component, field, value) {
                this.$store.commit('UPDATE_CV', {module: component, field, value});
                this.validate(component, field)
            },
            validate(component, field){
                let valid = true;
                if(typeof this.$v[field] !== 'undefined') {
                    this.$v[field].$touch();
                    this.$store.commit('CLEAN_ERROR', {module: component, field});
                    if (this.$v[field].$dirty) {
                        for (const key in this.$v[field]) {
                            if (key.charAt(0) !== '$' && !this.$v[field][key]) {
                                this.$store.commit('PUSH_ERROR', {
                                    module: component, field, message: this.validationMessages[key]
                                });
                                valid = false;
                            }
                        }
                    }
                }
                return valid
            },
            validateAll(component) {
                let ok = true;
                for(let field of this.fields) {
                    if(!this.validate(component, field)) {
                        ok = false;
                    }
                }
                return ok;
            }
        },
        validations() {
            const validations = {};
            for(const field of this.fields) {
                const fieldValidations = this.$store.state[this.$options.name].cv[field].validations;
                if(typeof fieldValidations !== 'undefined') {
                    validations[field] = fieldValidations
                }
            }
            return validations;
        }
    }
}
