<template>
    <v-dialog v-model="show" persistent width="300">
        <v-card>
            <v-card-text>Esta seguro que desea eliminar este elemento?</v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="error darken-1" @click="handleDelete">
                    Borrar
                </v-btn>
                <v-btn color="secondary darken-1" text @click.stop="show = false">
                    Cancelar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: 'ConfirmDelete',
        props: {
            visible: {
                type: Boolean,
                required: true,
                default: () => false
            },
            handleDelete: {
                type: Function,
                required: true
            }
        },
        computed: {
            show: {
                get() {
                    return this.visible;
                },
                set(value) {
                    if (!value) {
                        this.$emit('close');
                    }
                }
            }
        }
    };
</script>
