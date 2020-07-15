import {mapState} from "vuex";

export default {
    computed: {
        items() {
            return this.$store.state.item[this.childKey]
        },
        totalItems() {
            return this.$store.state.totals[this.childKey]
        },
        active() {
            return this.step === this.$store.state.currentStep;
        },
        ...mapState({
            isLoading: state => state.isLoading,
        }),
    },
    data: () => ({
        item: {},
        options: {
            sortBy: [],
            descending: false,
            page: 1,
            itemsPerPage: 15
        },
        selected: [],
        showTable: true,
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
            const form = this.getRefForm();
            if (form.validate()) {
                const item = form.$v.item.$model;
                if(typeof item['@id'] === 'undefined') {
                    this.$store.commit('PUSH', {prop: this.childKey, item});
                } else {
                    this.$store.commit('SPLICE', {prop: this.childKey, start: item['@id'], item});
                }
                this.setShowTable();
            } else {
                form.goTo();
            }
        },
        deleteHandler(item) {
            this.$store.commit('SPLICE', {prop: this.childKey, start: item['@id']});
            if(!this.validate()) {
                this.showTable = false;
                this.$store.commit('SET_TOOLBAR', {
                    add: false, cancel: false, next: false, prev: true, save: true
                });
            }
        },
        cancel() {
            this.setShowTable();
        },
        setShowTable() {
            this.showTable = this.validate();
            if(this.showTable) {
                this.$store.commit('SET_TOOLBAR', {
                    add: true, cancel: false, next: true, prev: true, save: false, addText: 'Agregar ' + this.childName
                });
            }
        },
        getRefForm() {
            return this.$refs.createForm;
        }
    },
    watch: {
        active: { handler(active) {
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
        }, immediate: true},
        showTable(showTable) {
            if(showTable) {
                this.item = {};
            }
        }
    }
}