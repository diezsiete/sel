export default {
  path: '/identificacion-tipos',
  name: 'identificacion-tipos',
  component: () => import('../components/identificaciontipo/Layout'),
  redirect: { name: 'IdentificacionTipoList' },
  children: [
    {
      name: 'IdentificacionTipoList',
      path: '',
      component: () => import('../views/identificaciontipo/List')
    },
    {
      name: 'IdentificacionTipoCreate',
      path: 'new',
      component: () => import('../views/identificaciontipo/Create')
    },
    {
      name: 'IdentificacionTipoUpdate',
      path: ':id/edit',
      component: () => import('../views/identificaciontipo/Update')
    },
    {
      name: 'IdentificacionTipoShow',
      path: ':id',
      component: () => import('../views/identificaciontipo/Show')
    }
  ]
};
