<template>
    <v-container>
            <referencia-table
                    v-if="showTable"
                    v-model="selected"
                    :items="items"
                    :options.sync="options"
                    :loading="isLoading"
                    :total-items="totalItems"
                    :edit-handler="item => editHandler(item)"
                    :delete-handler="item => deleteHandler(item)"
            ></referencia-table>
            <div v-else>
                <v-stepper v-model="requiredReferenciaStep" vertical v-if="useStepper">
                    <template v-for="(requiredTipo, n) in requiredTipos">
                        <v-stepper-step :complete="requiredReferenciaStep > n + 1" :step="n + 1">
                            Referencia {{ requiredTipo.nombre }}
                        </v-stepper-step>
                        <v-stepper-content :step="n + 1">
                            <referencia-form ref="createForm" :values="requiredItems[n]" :relations-return-object="true" :entity="childName"
                                             tipo-disabled>
                            </referencia-form>
                        </v-stepper-content>
                    </template>
                </v-stepper>
                <div v-else>
                    <h1 class="mb-0">{{ typeof item['@id'] === 'undefined' ? 'Agregar' : 'Editar' }} Referencia </h1>
                    <referencia-form ref="updateForm" :values="item" :relations-return-object="true" :entity="childName">
                    </referencia-form>
                </div>
            </div>
            <slot></slot>
    </v-container>
</template>

<script>
    import RegistroCvChildMixin from "@mixins/cv/RegistroCvChildMixin";
    import ReferenciaTable from '@components/entity/cv/referencia/Table';
    import ReferenciaForm from '@components/entity/cv/referencia/Form';

    export default {
        name: "ReferenciaRegistro",
        mixins: [RegistroCvChildMixin],
        created() {
            this.fillRequiredItems();
        },
        components: {
            ReferenciaTable,
            ReferenciaForm
        },
        computed: {
            referenciasMissing() {
                return this.requiredTipos.filter(requiredTipo => !this.items.filter(
                    item => item.tipo && item.tipo.id === requiredTipo.id).length
                );
            }
        },
        data: () => ({
            showOne: true,
            showTwo: false,
            step: 4,
            childKey: 'referencias',
            childName: 'referencia',
            requiredReferenciaStep: 1,
            requiredTipos: [
                {
                    '@id': '/api/referencia-tipo/1',
                    '@type': 'ReferenciaTipo',
                    'id': 1,
                    'nombre': 'PERSONAL'
                },
                {
                    '@id': '/api/referencia-tipo/2',
                    '@type': 'ReferenciaTipo',
                    'id': 2,
                    'nombre': 'FAMILIAR'
                },
                {
                    '@id': '/api/referencia-tipo/3',
                    '@type': 'ReferenciaTipo',
                    'id': 3,
                    'nombre': 'LABORAL'
                }
            ],
            requiredItems: [],
            useStepper: true
        }),
        methods: {
            validate() {
                if(this.referenciasMissing.length > 0) {
                    this.requiredReferenciaStep = this.referenciasMissing[0].id;
                    this.useStepper = true;
                } else {
                    this.useStepper = false;
                    this.fillRequiredItems();
                }
                return !this.useStepper;
            },
            fillRequiredItems(){
                this.requiredItems = [];
                this.requiredTipos.forEach(tipo => this.requiredItems.push({tipo}));
            },
            getRefForm() {
                return this.useStepper
                    ? this.$refs.createForm[this.requiredReferenciaStep - 1]
                    : this.$refs.updateForm;
            }
        }
    }
</script>
<style lang="scss" scoped>
    .v-stepper--vertical {
        box-shadow: none;
        .v-stepper__step{
            padding-left: 0;
        }
        .v-stepper__content{
            margin-left: 12px;
        }
    }
</style>