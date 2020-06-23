<template>
    <v-container grid-list-xl fluid>
        <v-layout row >
            <estudio-table
                    v-if="showTable"
                    v-model="selected"
                    :items="items"
                    :options.sync="options"
                    :loading="isLoading"
                    :total-items="totalItems"
                    :edit-handler="item => editHandler(item)"
                    :delete-handler="item => deleteHandler(item)"
            ></estudio-table>
            <div v-else>
                <h1 class="mb-0">Crear estudio</h1>
                <estudio-form ref="createForm" :values="item" :relations-return-object="true"></estudio-form>
            </div>
            <slot></slot>
        </v-layout>
    </v-container>
</template>

<script>
    import Toolbar from "@components/Toolbar";
    import EstudioTable from "@components/entity/cv/estudio/Table";
    import EstudioForm from "@components/entity/cv/estudio/Form";
    import {mapState} from "vuex";

    export default {
        name: "EstudioRegistro",
        servicePrefix: 'Estudio',
        components: {
            Toolbar,
            EstudioTable,
            EstudioForm
        },
        computed: {
            ...mapState({
                items: state => state.item.estudios.items,
                isLoading: state => state.isLoading,
                totalItems: state => state.item.estudios.total,
            }),
            active() {
                return this.step === this.$store.state.currentStep;
            }
        },
        data() {
            return {
                selected: [],
                options: {
                    sortBy: [],
                    descending: false,
                    page: 1,
                    itemsPerPage: 15
                },
                showTable: true,
                item: {},
                step: 2,
            };
        },
        methods: {
            editHandler(item) {
                this.showTable = false;
                this.item = item;
                this.$store.commit('SET_TOOLBAR', {
                    add: false, cancel: true, next: false, prev: false, save: true
                });
            },
            add() {
                this.$store.commit('SET_TOOLBAR', {
                    add: false, cancel: true, next: false, prev: false, save: true
                });
                this.showTable = false
            },
            validate() {
                return this.items.length > 0;
            },
            save() {
                const form = this.$refs.createForm;
                form.$v.$touch();
                if (!form.$v.$invalid) {
                    const item = form.$v.item.$model;
                    if(typeof item['@id'] === 'undefined') {
                        this.$store.commit('PUSH', {prop: 'estudios', item});
                    } else {
                        this.$store.commit('SPLICE', {prop: 'estudios', start: item['@id'], item});
                    }
                    this.showTable = true;
                    this.$store.commit('SET_TOOLBAR', {
                        add: true, cancel: false, next: true, prev: true, save: false, addText: 'Agregar estudio'
                    });
                }
            },
            deleteHandler(item) {
                this.$store.commit('SPLICE', {prop: 'estudios', start: item['@id']});
                if(!this.validate()) {
                    this.showTable = false;
                    this.$store.commit('SET_TOOLBAR', {
                        add: false, cancel: false, next: false, prev: true, save: true
                    });
                }
            },
            cancel() {
                this.showTable = true;
                this.$store.commit('SET_TOOLBAR', {
                    add: true, cancel: false, next: true, prev: true, save: false, addText: 'Agregar estudio'
                });
            }
        },
        watch: {
            active(active) {
                if(active) {
                    if(this.validate()) {
                        this.showTable = true;
                        this.$store.commit('SET_TOOLBAR', {
                            add: true, cancel: false, next: true, prev: true, save: false
                        });
                    } else {
                        this.showTable = false;
                        this.$store.commit('SET_TOOLBAR', {
                            add: false, cancel: this.items.length > 0, next: false, prev: this.items.length === 0, save: true
                        });
                    }
                }
            },
            showTable(showTable) {
                if(showTable) {
                    this.item = {};
                }
            }
        }
    }
</script>

<style scoped>

</style>