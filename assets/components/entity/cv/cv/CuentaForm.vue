<template>
    <v-form>
        <!--<v-container fluid>
            <v-row>
                <v-col cols="12"><h1 class="mb-0">Hoja de vida</h1></v-col>
            </v-row>
            <v-row>
                <v-col cols="12">
                    <file-field label="Cargar hoja de vida" outlined dense></file-field>
                </v-col>
            </v-row>
        </v-container>-->
        <v-list three-line subheader>
            <v-subheader>Hoja de vida</v-subheader>
            <v-list-item>
                <v-list-item-content>
                    <file-field
                        accept-image accept-pdf accept-word
                        dense
                        label="Cargar hoja de vida"
                        outlined
                        ref="file"
                        show-size
                        v-model="item.adjunto"
                        v-alidation :size="1"
                    />
                </v-list-item-content>
            </v-list-item>
        </v-list>
        <v-divider></v-divider>
        <v-list three-line subheader>
            <v-subheader>Datos de la cuenta</v-subheader>
            <v-list-item>
                <v-list-item-content>
                    <password-field v-model="item.usuario.password.first" label="Contraseña" new-password
                                    v-alidation required />
                    <password-field v-model="item.usuario.password.second" label="Confirmar contraseña" new-password
                                    v-alidation same-as="first"/>
                </v-list-item-content>
            </v-list-item>
            <v-list-item>
                <v-list-item-action>
                    <check-field v-model="item.usuario.aceptoTerminosEn"
                                 @errors="value => aceptoTerminosEnErrors = value"
                                 v-alidation required="Debe aceptar los terminos para finalizar">
                    </check-field>
                </v-list-item-action>
                <v-list-item-content class="required">
                    <v-list-item-title><label>Acepto los terminos y condiciones</label></v-list-item-title>
                    <v-list-item-subtitle>
                        Con el diligenciamiento y envío del presente formulario declaro que autorizo el tratamiento de
                        mis Datos Personales por parte de PTA S.A.S, de acuerdo con las finalidades establecidas en su
                        <v-input :error-messages="aceptoTerminosEnErrors"></v-input>
                    </v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-form>
</template>

<script>

import FormMixin from "@mixins/FormMixin";
import FileField from "@components/field/FileField";
import CheckField from "@components/field/CheckField";
import PasswordField from "@components/field/PasswordField";
import {mapState} from "vuex";

export default {
    name: "RegistroForm",
    mixins: [FormMixin],
    components: {
        CheckField,
        FileField,
        PasswordField
    },
    computed: {
        ...mapState({
            item: state => state.item
        }),
    },
    data: () => ({
        aceptoTerminosEnErrors: [],
        file: null,
    })
}
</script>