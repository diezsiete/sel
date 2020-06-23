<template>
    <v-app :class="{ overflow: overflow }">
        <!-- Sizes your content based upon application components -->
        <v-main>
            <!-- Provides the application the proper gutter -->
            <v-container fluid>
                <v-stepper v-model="currentStep" :alt-labels="true">
                    <v-stepper-header>
                        <template v-for="(item, n) in steps">
                            <v-stepper-step :key="`${n}-step`" :complete="currentStep > n + 1" :step="n + 1"
                                            :editable="item.editable" edit-icon="$complete">
                                {{ item.title }}
                            </v-stepper-step>
                            <v-divider v-if="n !== steps.length - 1" :key="n"></v-divider>
                        </template>
                    </v-stepper-header>

                    <v-stepper-items>
                        <v-stepper-content step="1">
                            <cv-registro ref="CvRegistro">
                                <registro-toolbar :next="next"></registro-toolbar>
                            </cv-registro>
                        </v-stepper-content>
                        <v-stepper-content step="2">
                            <estudio-registro ref="EstudioRegistro">
                                <registro-toolbar :add="add" :cancel="cancel" :next="next" :save="save"></registro-toolbar>
                            </estudio-registro>
                            <!--<v-btn color="primary" @click="validateBeforeNextStep()" class="float-right" >
                                Siguiente
                            </v-btn>
                            <v-btn text @click="prevStep()">Anterior</v-btn>-->
                        </v-stepper-content>
                        <!--<v-stepper-content v-for="(item, n) in steps" :key="`${n}-content`" :step="n + 1">
                            <component v-bind:is="currentComponent"></component>

                            <v-btn color="primary" @click="nextStep()" class="float-right" >
                                Siguiente
                            </v-btn>

                            <v-btn text v-if="n > 0" @click="prevStep()">Anterior</v-btn>
                        </v-stepper-content>-->
                    </v-stepper-items>

                </v-stepper>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
    import { mapState } from 'vuex';
    import CvRegistro from '@views/entity/cv/cv/Registro';
    import EstudioRegistro from '@views/entity/cv/estudio/Registro';
    import RegistroToolbar from "@components/cv/RegistroToolbar";

    export default {
        name: 'AppRegistro',
        components: {
            CvRegistro,
            EstudioRegistro,
            RegistroToolbar
        },
        computed: {
            currentStep: {
                get () {
                    return this.$store.state.currentStep
                },
                set (value) {
                    this.$store.dispatch('setCurrentStep', value)
                }
            },
            ...mapState({
                steps: state => state.steps,
                currentComponent: state => state.currentComponent,
                overflow: state => state.overflow
            })
        },
        created() {
            /*EventBus.$on('registroNextStep', () => {
                this.nextStep()
            });*/
        },
        methods: {
            next () {
                const currentComponent = this.$refs[this.$store.getters.currentComponent];
                if(currentComponent.validate()) {
                    this.$store.dispatch('currentStepAugment')
                }
                /*currentComponent.$v.$touch();
                if (!currentComponent.$v.$invalid) {
                    this.$store.dispatch('create', currentComponent.$v.item.$model);
                    this.$store.dispatch('currentStepAugment')
                }*/
            },
            add () {
                this.$refs[this.$store.getters.currentComponent].add()
            },
            cancel () {
                this.$refs[this.$store.getters.currentComponent].cancel()
            },
            save() {
                this.$refs[this.$store.getters.currentComponent].save()
            }
        }
    }
</script>

<style lang="scss">
    #app{
        background-color: #ecedf0;
    }
    .overflow {
        .v-stepper__wrapper, .v-stepper__items, .v-stepper{
            overflow: visible !important;
        }
    }
</style>
