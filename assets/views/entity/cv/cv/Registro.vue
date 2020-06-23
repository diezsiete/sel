<template>
    <div>
        <h1 class="mb-0">Datos básicos</h1>
        <cv-form ref="createForm" :values="item" />
        <slot></slot>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import CvForm from "@components/entity/cv/cv/Form";

    export default {
        name: "RegistroCv",
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
            }
        },
        methods: {
            validate() {
                const form = this.$refs.createForm;
                form.$v.$touch();
                if (!form.$v.$invalid) {
                    this.$store.dispatch('create', form.$v.item.$model);
                    return true;
                }
                return false;
            }
        },
        watch: {
            active(active) {
                if(active) {
                    this.$store.commit('SET_TOOLBAR', {add: false, cancel: false, next: true, prev: false, save: false});
                }
            },
        }
    }
</script>

<style scoped>

</style>