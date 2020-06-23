import Vue from 'vue';
import VueRouter from 'vue-router';
import areaRoutes from './area';
import ciudadRoutes from './ciudad';
import departamentoRoutes from './departamento';
import estadoCivilRoutes from './estadocivil';
import estudioRoutes from './estudio';
import estudioCodigoRoutes from './estudiocodigo';
import estudioInstitutoRoutes from './estudioinstituto';
import experienciaDuracionRoutes from './experienciaduracion';
import factorRhRoutes from './factorrh';
import generoRoutes from './genero';
import grupoSanguineoRoutes from './gruposanguineo';
import hvRoutes from './hv';
import identificacionTipoRoutes from './identificaciontipo';
import nacionalidadRoutes from './nacionalidad';
import nivelAcademicoRoutes from './nivelacademico';
import paisRoutes from './pais';
import referenciaTipoRoutes from './referenciatipo';

Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    routes: [
        areaRoutes,
        ciudadRoutes,
        departamentoRoutes,
        estadoCivilRoutes,
        estudioRoutes,
        estudioCodigoRoutes,
        estudioInstitutoRoutes,
        experienciaDuracionRoutes,
        factorRhRoutes,
        generoRoutes,
        grupoSanguineoRoutes,
        hvRoutes,
        identificacionTipoRoutes,
        nacionalidadRoutes,
        nivelAcademicoRoutes,
        paisRoutes,
        referenciaTipoRoutes
    ]
});