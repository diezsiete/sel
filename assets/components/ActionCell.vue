<template>
    <div class="action-cell-container">
        <v-row justify="space-around">
            <v-icon v-if="handleShow" small class="mr-2" @click="handleShow">mdi-eye</v-icon>
            <v-tooltip top v-if="handleEdit">
                <template v-slot:activator="{ on }">
                    <v-icon small class="mr-2 pedo" @click="handleEdit" v-on="on">mdi-pencil</v-icon>
                </template>
                <span>Editar</span>
            </v-tooltip>
            <v-tooltip top v-if="handleDelete">
                <template v-slot:activator="{ on }">
                    <v-icon small class="mr-2" @click="confirmDelete = true" v-on="on">mdi-delete</v-icon>
                </template>
                <span>Eliminar</span>
            </v-tooltip>
        </v-row>
        <ConfirmDelete
                v-if="handleDelete"
                :visible="confirmDelete"
                :handle-delete="handleDelete"
                @close="confirmDelete = false"
        />
    </div>
</template>

<script>
    import ConfirmDelete from './ConfirmDelete';

    export default {
        name: 'ActionCell',
        components: {
            ConfirmDelete
        },
        data() {
            return {
                confirmDelete: false
            };
        },
        props: {
            handleShow: {
                type: Function,
                required: false
            },
            handleEdit: {
                type: Function,
                required: false
            },
            handleDelete: {
                type: Function,
                required: false
            }
        }
    };
</script>
<style>
</style>
