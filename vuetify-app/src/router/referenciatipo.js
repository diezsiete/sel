export default {
  path: '/referencia-tipos',
  name: 'referencia-tipos',
  component: () => import('../components/referenciatipo/Layout'),
  redirect: { name: 'ReferenciaTipoList' },
  children: [
    {
      name: 'ReferenciaTipoList',
      path: '',
      component: () => import('../views/referenciatipo/List')
    },
    {
      name: 'ReferenciaTipoCreate',
      path: 'new',
      component: () => import('../views/referenciatipo/Create')
    },
    {
      name: 'ReferenciaTipoUpdate',
      path: ':id/edit',
      component: () => import('../views/referenciatipo/Update')
    },
    {
      name: 'ReferenciaTipoShow',
      path: ':id',
      component: () => import('../views/referenciatipo/Show')
    }
  ]
};
