import {mapState} from "vuex";

export default {
    computed: {
        items() {
            return this.$store.state.item[this.$options.childKey]
        },
        totalItems() {
            return this.$store.state.totals[this.$options.childKey]
        },
        active() {
            return this.step === this.$store.state.currentStep;
        },
        ...mapState({
            isLoading: state => state.isLoading,
        }),
    },
    data: () => ({
        selected: [],
        options: {
            sortBy: [],
            descending: false,
            page: 1,
            itemsPerPage: 15
        },
        showTable: true,
        item: {},
    }),
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
                    this.$store.commit('PUSH', {prop: this.$options.childKey, item});
                } else {
                    this.$store.commit('SPLICE', {prop: this.$options.childKey, start: item['@id'], item});
                }
                this.showTable = true;
                this.$store.commit('SET_TOOLBAR', {
                    add: true, cancel: false, next: true, prev: true, save: false, addText: 'Agregar ' + this.$options.childName
                });
            }
        },
        deleteHandler(item) {
            this.$store.commit('SPLICE', {prop: this.$options.childKey, start: item['@id']});
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
                add: true, cancel: false, next: true, prev: true, save: false, addText: 'Agregar ' + this.$options.childName
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