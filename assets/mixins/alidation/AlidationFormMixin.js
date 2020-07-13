import Vue from 'vue';

export default {
    data: () => ({
        alidations: {},
    }),
    directives: {
        alidation: {
            // directive definition
            bind: function (el, binding, vnode) {
                if(vnode.data.model) {
                    const modelExpression = vnode.data.model.expression
                    const x = vnode.componentInstance.$v;

                    modelExpression.split('.').reduce((o, expression, idx, splice) => {
                        if (!o[expression]) {
                            Vue.set(o, expression, idx === splice.length - 1 ? x : {})
                        }
                        return o[expression];
                    }, vnode.context.alidations);
                    vnode.componentInstance.entity = vnode.context.entity
                    vnode.componentInstance.$v = vnode.context.$v
                }
            }
        }
    },
    methods: {
        validPromise () {
            // based on https://github.com/vuelidate/vuelidate/issues/179#issuecomment-326202539
            return new Promise(resolve => {
                const unwatch = this.$watch(() => !this.$v.$pending, (isNotPending) => {
                    if (isNotPending) {
                        let ok = !this.$v.$invalid;
                        try {
                            unwatch()
                        } catch (e) {
                            if(!e instanceof ReferenceError){
                                ok = false
                            }
                        }
                        resolve(ok)
                    }
                }, {immediate: true})
            });
        },
        async validateAsync() {
            this.$v.$touch();
            return this.validPromise();
        },
        validate() {
            this.$v.$touch();
            return !this.$v.$invalid;
        },
        goTo() {
            this.$store.getters[`${this.entity}/alidation/modelExpressions`].every(modelExpression => {
                if(modelExpression.split('.').reduce((o, i) => o[i], this.$v).$error) {
                    this.$vuetify.goTo('#'+this.$store.getters[`${this.entity}/alidation/id`](modelExpression), {offset: 100});
                    return false;
                }
                return true;
            });
        }
    },
    validations() {
        return this.alidations;
    },
    props: {
        //el nombre de la entidad que representa, se relaciona con el modulo en el store para guardar alidations
        entity: {
            type: String
        }
    }
}
