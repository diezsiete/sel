<template>
    <v-text-field
        v-bind="$attrs"
        v-on="$listeners"
        :autocomplete="!newPassword ? false : 'new-password'"
        :class="classes"
        dense
        :error-messages="errors"
        outlined
        :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
        :type="show ? 'text' : 'password'"
        @click:append="show = !show"
    ></v-text-field>
</template>

<script>
    import AlidationFieldMixin from "@mixins/alidation/AlidationFieldMixin";
    import FieldMixin from "@mixins/field/FieldMixin";
    export default {
        name: "PasswordField",
        mixins: [AlidationFieldMixin, FieldMixin],
        data:() => ({
            show: false
        }),
        props: {
            newPassword: Boolean
        }
    }
</script>