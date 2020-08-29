import {required, requiredIf, minLength} from 'vuelidate/lib/validators';
import debounce from 'debounce-promise';

const fetchOperation = debounce((service, model, val) => new Promise(r =>
    service.find(`${service.endpoint}/one-by/${model}/${val}`).then(() => r(false)).catch(e => r(true))
), 200);

const validators = {
    required: {
        message: 'Campo requerido',
        validator: required
    },
    requiredIf: {
        message: 'Campo requerido',
        validationKey: 'required',
        validator: requiredIf,
        isCallable: true
    },
    minLength: {
        message: 'Valor debe ser al menos % caracteres',
        validator: minLength,
        isCallable: true
    },
    fetchUnique: {
        message: 'Valor no es unico',
        validator(storeModule) {
            return value => {
                if (!value) {
                    return true;
                }
                return fetchOperation(this.$store.getters[`${storeModule}/service`], this.modelName, value)
            }
        },
        isCallable: true
    }
};

export default validators;
