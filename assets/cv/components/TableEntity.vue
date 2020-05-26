<template>
    <v-data-table :headers="headers" :items="entities" sort-by="calories" class="elevation-1" :loading="loading"
                  loading-text="Cargando... Por favor espere">
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
            entities: [],
            editedIndex: -1,
            editedItem: null,
            defaultItem: null,
            loading: true
        }),

        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'Nuevo estudio' : 'Editar estudio'
            },
            headers () {
                return [...Object.keys(this.$store.state[this.component].cv).map(value => ({
                    text: this.$store.state[this.component].cv[value].label,
                    align: 'start',
                    sortable: false,
                    value
                })), {text: 'Acciones', value: 'actions', sortable: false}];
            }
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
                if(typeof this.$store.state.registro !== 'undefined') {
                    this.$store.dispatch('buildEmptyTableEntities');
                } else {
                    this.$store.dispatch('fetchTableEntities');
                }

                setTimeout(() => {
                    this.entities = [
                        {
                            nombre: 'Frozen Yogurt',
                        },
                        {
                            nombre: 'Ice cream sandwich',
                        },
                    ]
                    this.loading = false
                }, 5000)
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
        props: [
            'component'
        ]
    }
</script>

<style scoped>

</style>