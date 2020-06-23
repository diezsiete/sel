export default {
  path: '/nacionalidades',
  name: 'nacionalidades',
  component: () => import('../components/nacionalidad/Layout'),
  redirect: { name: 'NacionalidadList' },
  children: [
    {
      name: 'NacionalidadList',
      path: '',
      component: () => import('../views/nacionalidad/List')
    },
    {
      name: 'NacionalidadCreate',
      path: 'new',
      component: () => import('../views/nacionalidad/Create')
    },
    {
      name: 'NacionalidadUpdate',
      path: ':id/edit',
      component: () => import('../views/nacionalidad/Update')
    },
    {
      name: 'NacionalidadShow',
      path: ':id',
      component: () => import('../views/nacionalidad/Show')
    }
  ]
};
