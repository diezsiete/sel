<template>
    <v-toolbar class="my-md-auto" elevation="0">
        <v-btn text v-if="showPrev" @click="handle('prev')">Anterior</v-btn>
        <v-spacer/>
        <div>
            <v-btn color="primary" v-if="showSave" @click="handle('save')" class="float-right ml-sm-2" >
                Guardar
            </v-btn>
            <v-btn color="warning" v-if="showCancel" @click="handle('cancel')" class="float-right" >
                Cancelar
            </v-btn>
            <v-btn color="primary" v-if="showNext" @click="handle('next')" class="float-right ml-sm-2" >
                Siguiente
            </v-btn>
            <v-btn color="primary" v-if="showAdd" @click="handle('add')" class="float-right" >
                {{ addText }}
            </v-btn>

            <!--<v-btn
                    v-if="handleCancel"
                    :loading="isLoading"
                    color="warning"
                    @click="cancel"
            >
                Cancelar
            </v-btn>
            <v-btn
                    v-if="handleDelete"
                    color="error"
                    @click="confirmDelete = true"
            >
                Borrar
            </v-btn>
            <v-btn
                    v-if="handleSubmit"
                    :loading="isLoading"
                    color="primary"
                    @click="submitItem"
            >
                Guardar
            </v-btn>
            <v-btn
                    v-if="handleReset"
                    color="primary"
                    class="ml-sm-2"
                    @click="resetItem"
            >
                Reiniciar
            </v-btn>
            <v-btn
                    v-if="handleAdd"
                    color="primary"
                    @click="addItem"
            >
                Agregar
            </v-btn>-->
        </div>
        <ConfirmDelete
                v-if="handleDelete"
                :visible="confirmDelete"
                :handle-delete="handleDelete"
                @close="confirmDelete = false"
        />
    </v-toolbar>
</template>

<script>
    import ConfirmDelete from '@components/ConfirmDelete';
    import { mapState } from 'vuex';

    export default {
        name: 'RegistroToolbar',
        components: {
            ConfirmDelete
        },
        computed: {
            ...mapState({
                showNext: state => state.registroToolbar.next,
                showPrev: state => state.registroToolbar.prev,
                showAdd: state => state.registroToolbar.add,
                showSave: state => state.registroToolbar.save,
                showCancel: state => state.registroToolbar.cancel,
                addText: state => state.registroToolbar.addText
            })
        },
        data() {
            return {
                confirmDelete: false
            };
        },
        props: {
            add: Function,
            cancel: Function,
            next: Function,
            prev: Function,
            save: Function,
            handleDelete: {
                type: Function,
                required: false
            },
            /*handleSubmit: {
                type: Function,
                required: false
            },
            handleReset: {
                type: Function,
                required: false
            },
            handleAdd: {
                type: Function,
                required: false
            },
            handleCancel: {
                type: Function,
                required: false
            },*/
            title: {
                type: String,
                required: false
            },
            isLoading: {
                type: Boolean,
                required: false,
                default: () => false
            }
        },
        methods: {
            handle(handleType) {
                if(this[handleType]) {
                    this[handleType]();
                }
            },
            /*addItem() {
                if (this.handleAdd) {
                    this.handleAdd();
                }
            },
            submitItem() {
                if (this.handleSubmit) {
                    this.handleSubmit();
                }
            },
            resetItem() {
                if (this.handleReset) {
                    this.handleReset();
                }
            },
            cancel() {
                if(this.handleCancel) {
                    this.handleCancel();
                }
            }*/
        }
    };
</script>
