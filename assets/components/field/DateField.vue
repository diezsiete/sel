<template>
    <v-menu
            attach
            v-if="edit"
            v-model="menu"
            :close-on-content-click="false"
            transition="scale-transition"
            min-width="290px">
        <template v-slot:activator="{ on, attrs }">
            <!--prepend-icon="event"-->
            <v-text-field
                    dense
                    :class="classes"
                    :error-messages="errors"
                    :value="formattedDate"
                    :label="label"
                    outlined
                    readonly
                    v-bind="attrs"
                    v-on="on"
            ></v-text-field>
        </template>
        <v-date-picker :value="formattedDate" @input="updateValue"></v-date-picker>
    </v-menu>
    <v-input v-else :label="label">
        <p>
            {{ formattedDate }}
        </p>
    </v-input>
</template>

<script>
    import { formatDateTime } from '@utils/dates';
    import AlidationFieldMixin from "@mixins/alidation/AlidationFieldMixin";
    import FieldMixin from "@mixins/field/FieldMixin";

    export default {
        name: "DateField",
        mixins: [AlidationFieldMixin, FieldMixin],
        computed:{
            formattedDate() {
                return this.formatDateTime(this.value);
            }
        },
        data() {
            return {
                menu: false,
            };
        },
        methods: {
            formatDateTime,
            updateValue: function (value) {
                this.menu = false;
                this.$emit('input', value)
            },
        },
        props: {
            value: null,
            label: String,
        },
        watch: {
            menu(state) {
                this.$store.state.overflow = state;
            }
        }
    }
</script>