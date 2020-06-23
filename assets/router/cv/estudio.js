export default {
    path: '/sel/cv/estudio',
    name: 'estudio',
    component: () => import('@components/entity/cv/estudio/Layout'),
    redirect: { name: 'EstudioList' },
    children: [
        {
            name: 'EstudioList',
            path: '',
            component: () => import('@views/entity/cv/estudio/List')
        },
        {
            name: 'EstudioCreate',
            path: 'new',
            component: () => import('@views/entity/cv/estudio/Create')
        },
        {
            name: 'EstudioUpdate',
            path: ':id/edit',
            component: () => import('@views/entity/cv/estudio/Update')
        },
        {
            name: 'EstudioShow',
            path: ':id',
            component: () => import('@views/entity/cv/estudio/Show')
        }
    ]
};