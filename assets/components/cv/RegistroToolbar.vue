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
        </div>
    </v-toolbar>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        name: 'RegistroToolbar',
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
        props: {
            add: Function,
            cancel: Function,
            next: Function,
            prev: Function,
            save: Function,
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
        }
    };
</script>
