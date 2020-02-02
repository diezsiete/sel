<template>
    <div class="text">
        <div
            contenteditable
            @input="handleInput"
            @blur="blurChanges"
            @keydown.enter="enterChanges"
            v-text="archivoSelectedOriginalFilename"
            ref="fileInput">
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        name: "OriginalFilenameEditable",
        props: ['archivo'],
        computed: mapState([
            'archivoSelectedOriginalFilename'
        ]),
        data: function () {
            return {
                originalFilenameEdit: "",
                enter: false
            }
        },
        methods: {
            handleInput: function (e) {
                this.originalFilenameEdit = e.target.innerHTML.replace(/(?:^(?:&nbsp;)+)|(?:(?:&nbsp;)+$)/g, '');
            },
            async blurChanges(e) {
                if(this.enter) {
                    e.target.innerHTML = await this.$store.dispatch('endOriginalFilenameEdition', this.originalFilenameEdit);
                    this.enter = false;
                } else {
                    e.target.innerHTML = this.archivoSelectedOriginalFilename;
                }
            },
            async enterChanges(e) {
                this.enter = true;
                this.$el.querySelector('.text > div').blur();
            }
        },
        mounted() {
            this.originalFilenameEdit = this.archivo.originalFilename;
        },
        watch: {
            archivoSelectedOriginalFilename(value) {
                this.originalFilenameEdit = value;
            }
        }
    }
</script>

<style scoped>
    .text {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>