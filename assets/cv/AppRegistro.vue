<template>
    <v-app>
        <!-- Sizes your content based upon application components -->
        <v-content>
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
                            <DatosBasicos></DatosBasicos>
                            <v-btn color="primary" @click="validateBeforeNextStep()" class="float-right" >
                                Siguiente
                            </v-btn>
                        </v-stepper-content>
                        <v-stepper-content step="2">
                            <RegistroTableEntity component="Estudio" name="Estudio"></RegistroTableEntity>
                            <v-btn color="primary" @click="validateBeforeNextStep()" class="float-right" >
                                Siguiente
                            </v-btn>
                            <v-btn text @click="prevStep()">Anterior</v-btn>
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
        </v-content>

        <v-footer app>
            <!-- -->
        </v-footer>
    </v-app>
</template>

<script>
    import { mapState } from 'vuex';
    import DatosBasicos from "./components/DatosBasicos";
    import RegistroTableEntity from "./components/RegistroTableEntity";
    import { EventBus } from './helpers/event-bus.js';

    export default {
        name: 'app',
        components: {
            DatosBasicos,
            RegistroTableEntity
        },
        computed: {
            currentStep: {
                get () {
                    return this.$store.state.registro.currentStep
                },
                set (value) {
                    this.$store.dispatch('setCurrentStep', value)
                }
            },
            ...mapState({
                steps: state => state.registro.steps,
                currentComponent: state => state.registro.currentComponent
            })
        },
        created() {
            EventBus.$on('registroNextStep', () => {
                this.nextStep()
            });
        },
        methods: {
            validateBeforeNextStep() {
                EventBus.$emit('registroValidateBeforeNextStep', this.currentComponent);
            },
            nextStep () {
                this.$store.dispatch('currentStepAugment')
            },
            prevStep () {
                this.$store.dispatch('currentStepDecrease')
            }
        }
    }
</script>

<style lang="scss">
    #app{
        background-color: #ecedf0;
    }
</style>
