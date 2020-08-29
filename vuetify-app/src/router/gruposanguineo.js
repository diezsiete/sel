export default {
  path: '/grupos-sanguineos',
  name: 'grupos-sanguineos',
  component: () => import('../components/gruposanguineo/Layout'),
  redirect: { name: 'GrupoSanguineoList' },
  children: [
    {
      name: 'GrupoSanguineoList',
      path: '',
      component: () => import('../views/gruposanguineo/List')
    },
    {
      name: 'GrupoSanguineoCreate',
      path: 'new',
      component: () => import('../views/gruposanguineo/Create')
    },
    {
      name: 'GrupoSanguineoUpdate',
      path: ':id/edit',
      component: () => import('../views/gruposanguineo/Update')
    },
    {
      name: 'GrupoSanguineoShow',
      path: ':id',
      component: () => import('../views/gruposanguineo/Show')
    }
  ]
};
