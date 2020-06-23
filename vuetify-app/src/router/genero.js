export default {
  path: '/generos',
  name: 'generos',
  component: () => import('../components/genero/Layout'),
  redirect: { name: 'GeneroList' },
  children: [
    {
      name: 'GeneroList',
      path: '',
      component: () => import('../views/genero/List')
    },
    {
      name: 'GeneroCreate',
      path: 'new',
      component: () => import('../views/genero/Create')
    },
    {
      name: 'GeneroUpdate',
      path: ':id/edit',
      component: () => import('../views/genero/Update')
    },
    {
      name: 'GeneroShow',
      path: ':id',
      component: () => import('../views/genero/Show')
    }
  ]
};
