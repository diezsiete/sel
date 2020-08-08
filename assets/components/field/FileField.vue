<template>
    <v-file-input
        :accept="accept"
        :class="classes"
        :value="value"
        :error-messages="errors"
        v-bind="$attrs"
        v-on="$listeners"
        @change="updateValue"
    ></v-file-input>
</template>

<script>
    import AlidationFieldMixin from "@mixins/alidation/AlidationFieldMixin";
    import FieldMixin from "@mixins/field/FieldMixin";
    export default {
        name: "FileField",
        mixins: [
            AlidationFieldMixin,
            FieldMixin
        ],
        computed: {
            accept(){
                let accept = [];
                if(this.acceptWord) {
                    accept.push('.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                }
                if(this.acceptImage) {
                    accept.push('image/*')
                }
                if(this.acceptPdf) {
                    accept.push('application/pdf')
                }
                return accept.length > 0 ? accept.join() : false
            }
        },
        methods: {
            updateValue(value) {
                this.$emit('input', value)
            }
        },
        props: {
            value: null,
            acceptImage: Boolean,
            acceptPdf: Boolean,
            acceptWord: Boolean
        }
    }
</script>