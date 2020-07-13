import validators from "@plugins/validators";

export default {
    mounted() {
        if(this.alidationReady) {
            this.$store.commit('alidation/ADD', {modelExpression: this.modelExpression, id: this.id})
        }
    },
    computed: {
        errors() {
            const errors = [];
            if(this.alidationReady) {
                if(!this.vuelidate.$dirty || this.vuelidate.$pending) return errors;
                this.fieldValidations.forEach(vali => !this.vuelidate[vali.validationKey] && errors.push(vali.message))
            }
            return errors;
        },
        $v: {
            get: function() {
                const vali = {};
                this.fieldValidations.forEach(fieldValidation => vali[fieldValidation.validationKey] = fieldValidation.validator);
                return vali;
            },
            set: function ($v) {
                this.vuelidate = this.modelExpression.split('.').reduce((o, i) => o[i], $v);
                this.alidationReady = true
            }
        },
        fieldValidations() {
            const fieldValidations = [];
            for(const key in validators) {
                if(this[key]) {
                    fieldValidations.push({
                        validationKey: validators[key].validationKey || key,
                        validator: validators[key].isCallable
                            ? validators[key].validator.call(this, typeof this[key] === 'object' ? this[key].value : this[key])
                            : validators[key].validator,
                        message: this.getValidationMessage(key, validators[key].message)
                    })
                }
            }
            return fieldValidations
        },
        modelExpression() {
            return this.$vnode.data.model.expression;
        },
        modelName() {
            return this.modelExpression.split('.').reduce((o, i) => i, '')
        },
        id() {
            return this.modelName
        }
    },
    data: () => ({
        alidationReady: false,
        vuelidate: null,
    }),
    methods: {
        getValidationMessage(prop, validatorsMessage) {
            let message = validatorsMessage;
            if(typeof this[prop] === 'string' && prop === 'required') {
                message = this[prop]
            }
            else if(typeof this[prop] === 'object' && this[prop].message) {
                message = this[prop].message
            }
            if(/%/.test(message)) {
                message = message.replace('%', typeof this[prop] === 'object' ? this[prop].value : this[prop])
            }
            return message
        }
    },
    props: {
        required: {
            type: [Boolean, String],
            default: false
        },
        requiredIf: {
            type: [Function, Object]
        },
        minLength: {
            type: [Number, Object]
        },
        fetchUnique: {
            type: [String, Object]
        }
    }
}
