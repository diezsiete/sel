<template>
    <v-text-field
            v-if="edit"
            :class="classes"
            dense
            :error-messages="errors"
            :id="id"
            outlined
            v-bind="$attrs"
            v-on="$listeners"
    >
    </v-text-field>
    <v-input
            v-else
            v-bind="$attrs">
        <p>
            {{ $attrs.value }}
        </p>
    </v-input>
</template>

<script>
    import AlidationFieldMixin from "@mixins/alidation/AlidationFieldMixin";
    import FieldMixin from "@mixins/field/FieldMixin";
    export default {
        name: "TextField",
        mixins: [
            AlidationFieldMixin,
            FieldMixin
        ]
    }
</script>