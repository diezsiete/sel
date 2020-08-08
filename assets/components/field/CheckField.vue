<template>
    <v-checkbox :class="classes"
                :value="value" :error-messages="errors" @change="updateValue">
        <template v-slot:label>
            <slot></slot>
        </template>
    </v-checkbox>
</template>

<script>
    import AlidationFieldMixin from "@mixins/alidation/AlidationFieldMixin";
    import FieldMixin from "@mixins/field/FieldMixin";

    export default {
        name: "CheckField",
        mixins: [AlidationFieldMixin, FieldMixin],
        methods: {
            updateValue(value) {
                this.$emit('input', value)
            }
        },
        props: {
            value: null
        },
        watch: {
            errors(value){
                if(value) {
                    this.$emit('errors', value)
                }
            }
        }
    }
</script>