<template>
    <v-toolbar class="my-md-auto" elevation="0">
        <slot name="left"></slot>
        <v-spacer/>
        <div>
            <v-btn
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
            </v-btn>
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
    import ConfirmDelete from './ConfirmDelete';

    export default {
        name: 'Toolbar',
        components: {
            ConfirmDelete
        },
        data() {
            return {
                confirmDelete: false
            };
        },
        props: {
            handleSubmit: {
                type: Function,
                required: false
            },
            handleReset: {
                type: Function,
                required: false
            },
            handleDelete: {
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
            },
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
            addItem() {
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
            }
        }
    };
</script>
