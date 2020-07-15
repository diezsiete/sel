<template>
    <v-app :class="{ overflow: overflow }">
        <!-- Sizes your content based upon application components -->
        <v-main>
            <!-- Provides the application the proper gutter -->
            <v-container fluid>
                <v-stepper v-model="currentStep" :id="id">
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
                                <registro-toolbar :add="add" :cancel="cancel" :next="next" :save="save" :prev="prev"></registro-toolbar>
                            </estudio-registro>
                        </v-stepper-content>
                        <v-stepper-content step="3">
                            <experiencia-registro ref="ExperienciaRegistro">
                                <registro-toolbar :add="add" :cancel="cancel" :next="next" :save="save" :prev="prev"></registro-toolbar>
                            </experiencia-registro>
                        </v-stepper-content>
                        <v-stepper-content step="4">
                            <referencia-registro ref="ReferenciaRegistro">
                                <registro-toolbar :add="add" :cancel="cancel" :next="next" :save="save" :prev="prev"></registro-toolbar>
                            </referencia-registro>
                        </v-stepper-content>

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
    import ExperienciaRegistro from '@views/entity/cv/experiencia/Registro';
    import ReferenciaRegistro from '@views/entity/cv/referencia/Registro';
    import RegistroToolbar from "@components/cv/RegistroToolbar";

    export default {
        name: 'AppRegistro',
        components: {
            CvRegistro,
            EstudioRegistro,
            ExperienciaRegistro,
            ReferenciaRegistro,
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
        data: () => ({
            id: 'registro'
        }),
        methods: {
            async next () {
                const currentComponent = this.$refs[this.$store.getters.currentComponent];
                if(await currentComponent.validate()) {
                    await this.$store.dispatch('currentStepAugment');
                    await this.$vuetify.goTo(`#${this.id}`)
                }
            },
            add () {
                this.$refs[this.$store.getters.currentComponent].add()
            },
            cancel () {
                this.$refs[this.$store.getters.currentComponent].cancel()
            },
            save() {
                this.$refs[this.$store.getters.currentComponent].save()
            },
            async prev() {
                await this.$store.dispatch('currentStepDecrease');
                await this.$vuetify.goTo(`#${this.id}`);
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
