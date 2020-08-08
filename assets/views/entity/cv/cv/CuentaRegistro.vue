<template>
    <div>
        <cuenta-form ref="createForm" :values="item" />
        <slot></slot>
    </div>
</template>

<script>
    import CuentaForm from "@components/entity/cv/cv/CuentaForm";
    import {mapState} from "vuex";
    import {mapFields} from "vuex-map-fields";

    import fos from '@router/fos'
    import axios from "axios";
    import {ENTRYPOINT} from '@/config/entrypoint'

    const http = axios.create({
        headers: {
            "Content-type": "application/json"
        }
    });

    export default {
        name: "CuentaRegistro",
        components: {
            CuentaForm
        },
        computed: {
            ...mapFields(['isLoading']),
            ...mapState({
                item: state => state.item
            }),
        },
        methods: {
            async validate() {
                const form = this.$refs.createForm;
                const ok = await form.validateAsync();
                if (ok) {
                    const file = form.$refs.file;
                    if(file.value) {
                        this.upload(file.value)
                    }
                    // console.log(this.$store.state.item)
                    // await this.$store.dispatch('create', form.$v.item.$model);
                    return false;
                }
                // form.goTo();
                return false;
            },
            upload(file) {
                let formData = new FormData();

                formData.append("file", file);

                return http.post(ENTRYPOINT + '/adjuntos', formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    },
                });
            }
        }
    }
</script>