<template>
    <v-data-table
            :headers="headers"
            :items="estudios"
            sort-by="calories"
            class="elevation-1"
    >
        <template v-slot:top>
            <v-toolbar flat color="white">
                <v-toolbar-title>Estudios</v-toolbar-title>
                <v-divider
                        class="mx-4"
                        inset
                        vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="500px">
                    <template v-slot:activator="{ on }">
                        <v-btn color="primary" dark class="mb-2" v-on="on">New Item</v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">{{ formTitle }}</span>
                        </v-card-title>

                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.name" label="Dessert name"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.calories" label="Calories"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.fat" label="Fat (g)"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.carbs" label="Carbs (g)"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.protein" label="Protein (g)"></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                            <v-btn color="blue darken-1" text @click="save">Save</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
            <v-icon
                    small
                    class="mr-2"
                    @click="editItem(item)"
            >
                mdi-pencil
            </v-icon>
            <v-icon
                    small
                    @click="deleteItem(item)"
            >
                mdi-delete
            </v-icon>
        </template>
        <template v-slot:no-data>
            <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
    </v-data-table>
</template>

<script>
    export default {
        name: "Estudios",
        data: () => ({
            dialog: false,
            headers: [
                {
                    text: 'Codigo',
                    align: 'start',
                    sortable: false,
                    value: 'codigo',
                },
                { text: 'Título', value: 'nombre' },
                { text: 'Acciones', value: 'actions', sortable: false },
            ],
            estudios: [],
            editedIndex: -1,
            editedItem: {
                codigo: '',
                nombre: '',
            },
            defaultItem: {
                codigo: '',
                nombre: 0,
            },
        }),

        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'Nuevo estudio' : 'Editar estudio'
            },
        },

        watch: {
            dialog (val) {
                val || this.close()
            },
        },

        created () {
            this.initialize()
        },

        methods: {
            initialize () {
                this.estudios = [
                    {
                        codigo: 'A',
                        nombre: 'Frozen Yogurt',
                    },
                    {
                        codigo: 'B',
                        nombre: 'Ice cream sandwich',
                    },
                ]
            },

            editItem (item) {
                this.editedIndex = this.estudios.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.dialog = true
            },

            deleteItem (item) {
                const index = this.estudios.indexOf(item);
                confirm('Esta seguro que desea eliminar?') && this.estudios.splice(index, 1)
            },

            close () {
                this.dialog = false
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            },

            save () {
                if (this.editedIndex > -1) {
                    Object.assign(this.estudios[this.editedIndex], this.editedItem)
                } else {
                    this.estudios.push(this.editedItem)
                }
                this.close()
            },
        },
    }
</script>

<style scoped>

</style>