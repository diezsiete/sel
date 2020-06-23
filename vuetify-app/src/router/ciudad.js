export default {
  path: '/ciudades',
  name: 'ciudades',
  component: () => import('../components/ciudad/Layout'),
  redirect: { name: 'CiudadList' },
  children: [
    {
      name: 'CiudadList',
      path: '',
      component: () => import('../views/ciudad/List')
    },
    {
      name: 'CiudadCreate',
      path: 'new',
      component: () => import('../views/ciudad/Create')
    },
    {
      name: 'CiudadUpdate',
      path: ':id/edit',
      component: () => import('../views/ciudad/Update')
    },
    {
      name: 'CiudadShow',
      path: ':id',
      component: () => import('../views/ciudad/Show')
    }
  ]
};
