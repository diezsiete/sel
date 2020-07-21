export default {
    barrio: 'Marly',
    celular: '3202123926',
    direccion: 'Calle 50 13-43',
    email: 'guerrerojosedario@gmail.com',
    estadoCivil: '/api/estado-civil/1',
    factorRh: '/api/factor-rh/+',
    genero: '/api/genero/2',
    grupoSanguineo: '/api/grupo-sanguineo/A',
    identCiudad: '/api/ciudad/1023',
    identificacion: '101841066',
    identificacionTipo: '/api/identificacion-tipo/01',
    nacCiudad: '/api/ciudad/149',
    nacimiento: '2012-05-02',
    nacionalidad: '/api/nacionalidad/1',
    nivelAcademico: '/api/nivel-academico/08',
    primerApellido: 'Guerrero',
    primerNombre: 'Jose',
    resiCiudad: '/api/ciudad/149',
    telefono: '2123444',
    estudios: [
        {
            '@id': 0,
            codigo: {
                '@id' : '/api/estudio-codigo/00001',
                'id' : '00001',
                nombre: 'ACTIVIDAD FISICA Y DEPORTE'
            },
            instituto: {
                '@id': '/api/estudio-instituto/000001',
                'id': '000002',
                'nombre': 'CENTRO EDUCACIONAL DE COMPUTOS Y SISTEMAS-CEDESIST'
            },
            nombre: 'asdasdasd'
        }
    ],
    // estudios: [],
    experiencias: [
        {
            '@id': 0,
            area: {
                '@id': '/api/experiencia-duracion/3',
                '@type': 'ExperienciaDuracion',
                id: 3,
                nombre: 'DE 1 A 2 AÑOS'
            },
            cargo: 'Desarrollador',
            descripcion: 'Cosas',
            duracion: {
                '@id': '/api/experiencia-duracion/2',
                '@type': 'ExperienciaDuracion',
                id: 2,
                nombre: 'DE 0 A 1 AÑO'
            },
            empresa: 'PTA',
            fechaIngreso: '2020-01-01',
            jefeInmediato: 'Cuacua',
            salarioBasico: '100000',
            telefonoJefe: '3202123926'
        }
    ],
    referencias: [
        {
            '@id': 0,
            celular: "1221122",
            nombre: 'Alfredo Hernandez',
            ocupacion: 'Abogado',
            parentesco: 'Amigo',
            telefono: 123123123,
            tipo: {
                '@id': '/api/referencia-tipo/1',
                '@type': 'ReferenciaTipo',
                'id': 1,
                'nombre': 'PERSONAL'
            }
        },
        {
            '@id': 1,
            celular: "1221122",
            nombre: 'Carolina guerrero',
            ocupacion: 'Comercial',
            parentesco: 'Hna',
            telefono: 123123123,
            tipo: {
                '@id': '/api/referencia-tipo/2',
                '@type': 'ReferenciaTipo',
                'id': 2,
                'nombre': 'FAMILIAR'
            }
        },
        {
            '@id': 2,
            celular: "1231233",
            nombre: 'Laura Suarez',
            ocupacion: 'Desempleada',
            parentesco: 'Amigovia',
            telefono: 1231233322,
            tipo: {
                '@id': '/api/referencia-tipo/3',
                '@type': 'ReferenciaTipo',
                'id': 3,
                'nombre': 'LABORAL'
            }
        }
    ],
    familiares: []
}