export default {
  path: '/paises',
  name: 'paises',
  component: () => import('../components/pais/Layout'),
  redirect: { name: 'PaisList' },
  children: [
    {
      name: 'PaisList',
      path: '',
      component: () => import('../views/pais/List')
    },
    {
      name: 'PaisCreate',
      path: 'new',
      component: () => import('../views/pais/Create')
    },
    {
      name: 'PaisUpdate',
      path: ':id/edit',
      component: () => import('../views/pais/Update')
    },
    {
      name: 'PaisShow',
      path: ':id',
      component: () => import('../views/pais/Show')
    }
  ]
};
