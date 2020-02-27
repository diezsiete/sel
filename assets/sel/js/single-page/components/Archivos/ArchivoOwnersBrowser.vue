<template>
    <v-simple-table v-if="convenio">
        <template v-slot:default>
            <thead>
                <tr>
                    <th>
                        <v-checkbox v-model="checkboxAll" v-on:change="onCheckboxAllChange"></v-checkbox>
                    </th>
                    <th class="text-left">Identificacion</th>
                    <th class="text-left">Nombre</th>
                </tr>
            </thead>
            <tbody>
            <tr v-for="owner in owners" :key="owner.id" class="archivo">
                <td>
                    <v-checkbox :value="owner.id" v-model="ownersChecked" v-on:click.native.stop="checkOwner(owner)">
                    </v-checkbox>
                </td>
                <td class="text-left">{{ owner.identificacion}}</td>
                <td class="text-left">{{ owner.nombreCompleto }}</td>
            </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "ArchivoOwnersBrowser",
        props: {
            clickable: {
                default: true
            }
        },
        computed: mapState('enviar', {
            convenio: state => state.convenio,
            owners: state => state.owners
        }),
        data() {
            return {
                checkboxAll: false,
                ownersChecked: []
            }
        },
        methods: {
            async onCheckboxAllChange(checked) {
                if(checked) {
                    this.ownersChecked = await this.$store.dispatch('enviar/selectAllOwners');
                    this.checkboxAll = true;
                } else {
                    await this.$store.dispatch('enviar/unselectAllOwners');
                    this.ownersChecked = [];
                    this.checkboxAll = false;
                }
            },
            async checkOwner(owner) {
                const added = await this.$store.dispatch('enviar/toggleOwnerId', owner.id);
                if(added) {
                    this.ownersChecked.push(owner.id)
                } else {
                    this.ownersChecked = this.ownersChecked.filter(ownerId => ownerId !== owner.id)
                    this.checkboxAll = false;
                }
            }
        }
    }
</script>

<style scoped>

</style>