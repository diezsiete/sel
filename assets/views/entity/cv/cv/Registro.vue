<template>
    <div>
        <cv-form ref="createForm" :values="item" />
        <slot></slot>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import CvForm from "@components/entity/cv/cv/Form";

    export default {
        name: "RegistroCv",
        mounted(){
            this.isMounted = true;
        },
        components: {
            CvForm
        },
        computed: {
            ...mapState({
                item: state => state.item
            }),
            active() {
                return this.step === this.$store.state.currentStep;
            }
        },
        data() {
            return {
                step: 1,
                isMounted: false
            }
        },
        methods: {
            async validate() {
                const form = this.$refs.createForm;
                const ok = await form.validateAsync();
                if (ok) {
                    await this.$store.dispatch('create', form.$v.item.$model);
                    return true;
                }
                form.goTo();
                return false;
            }
        },
        watch: {
            active(active) {
                if(active) {
                    this.$store.commit('SET_TOOLBAR', {add: false, cancel: false, next: true, prev: false, save: false});
                }
            }
        }
    }
</script>