export default {
    path: '/sel/cv/cv',
    name: 'cv',
    component: () => import('@components/entity/cv/cv/Layout'),
    redirect: { name: 'CvList' },
    children: [
        {
            name: 'CvUpdate',
            path: '',
            component: () => import('@views/entity/cv/cv/Update')
        },
        {
            name: 'CvCreate',
            path: 'new',
            component: () => import('@views/entity/cv/cv/Create')
        },
        {
            name: 'CvShow',
            path: ':id',
            component: () => import('@views/entity/cv/cv/Show')
        },
        {
            name: 'CvList',
            path: 'pendiente-por-definir', //TODO
            component: () => import('@views/entity/cv/cv/List')
        }
    ]
};