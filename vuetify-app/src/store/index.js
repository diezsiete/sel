import Vue from 'vue';
import Vuex from 'vuex';
import makeCrudModule from './modules/crud';
import notifications from './modules/notifications';
import areaService from '../services/area';
import ciudadService from '../services/ciudad';
import departamentoService from '../services/departamento';
import estadoCivilService from '../services/estadocivil';
import estudioService from '../services/estudio';
import estudioCodigoService from '../services/estudiocodigo';
import estudioInstitutoService from '../services/estudioinstituto';
import experienciaDuracionService from '../services/experienciaduracion';
import factorRhService from '../services/factorrh';
import generoService from '../services/genero';
import grupoSanguineoService from '../services/gruposanguineo';
import hvService from '../services/hv';
import identificacionTipoService from '../services/identificaciontipo';
import nacionalidadService from '../services/nacionalidad';
import nivelAcademicoService from '../services/nivelacademico';
import paisService from '../services/pais';
import referenciaTipoService from '../services/referenciatipo';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        notifications,
        area: makeCrudModule({
            service: areaService
        }),
        ciudad: makeCrudModule({
            service: ciudadService
        }),
        departamento: makeCrudModule({
            service: departamentoService
        }),
        estadoCivil: makeCrudModule({
            service: estadoCivilService
        }),
        estudio: makeCrudModule({
            service: estudioService
        }),
        estudioCodigo: makeCrudModule({
            service: estudioCodigoService
        }),
        estudioInstituto: makeCrudModule({
            service: estudioInstitutoService
        }),
        experienciaDuracion: makeCrudModule({
            service: experienciaDuracionService
        }),
        factorRh: makeCrudModule({
            service: factorRhService
        }),
        genero: makeCrudModule({
            service: generoService
        }),
        grupoSanguineo: makeCrudModule({
            service: grupoSanguineoService
        }),
        hv: makeCrudModule({
            service: hvService
        }),
        identificacionTipo: makeCrudModule({
            service: identificacionTipoService
        }),
        nacionalidad: makeCrudModule({
            service: nacionalidadService
        }),
        nivelAcademico: makeCrudModule({
            service: nivelAcademicoService
        }),
        pais: makeCrudModule({
            service: paisService
        }),
        referenciaTipo: makeCrudModule({
            service: referenciaTipoService
        }),
    }
});