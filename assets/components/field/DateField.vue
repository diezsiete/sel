<template>
    <v-menu
            attach
            v-model="menu"
            :close-on-content-click="false"
            transition="scale-transition"
            min-width="290px">
        <template v-slot:activator="{ on, attrs }">
            <!--prepend-icon="event"-->
            <v-text-field
                    :value="formattedDate"
                    :label="label"
                    readonly
                    v-bind="attrs"
                    v-on="on"
            ></v-text-field>
        </template>
        <v-date-picker :value="formattedDate" @input="updateValue"></v-date-picker>
    </v-menu>
</template>

<script>
    import { formatDateTime } from '@utils/dates';

    export default {
        name: "DateField",
        computed:{
            formattedDate() {
                return this.formatDateTime(this.value);
                return this.value;
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

<style scoped>

</style>