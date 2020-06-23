export default {
  path: '/estudio-codigos',
  name: 'estudio-codigos',
  component: () => import('../components/estudiocodigo/Layout'),
  redirect: { name: 'EstudioCodigoList' },
  children: [
    {
      name: 'EstudioCodigoList',
      path: '',
      component: () => import('../views/estudiocodigo/List')
    },
    {
      name: 'EstudioCodigoCreate',
      path: 'new',
      component: () => import('../views/estudiocodigo/Create')
    },
    {
      name: 'EstudioCodigoUpdate',
      path: ':id/edit',
      component: () => import('../views/estudiocodigo/Update')
    },
    {
      name: 'EstudioCodigoShow',
      path: ':id',
      component: () => import('../views/estudiocodigo/Show')
    }
  ]
};
