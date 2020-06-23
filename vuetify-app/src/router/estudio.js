export default {
  path: '/estudios',
  name: 'estudios',
  component: () => import('../components/estudio/Layout'),
  redirect: { name: 'EstudioList' },
  children: [
    {
      name: 'EstudioList',
      path: '',
      component: () => import('../views/estudio/List')
    },
    {
      name: 'EstudioCreate',
      path: 'new',
      component: () => import('../views/estudio/Create')
    },
    {
      name: 'EstudioUpdate',
      path: ':id/edit',
      component: () => import('../views/estudio/Update')
    },
    {
      name: 'EstudioShow',
      path: ':id',
      component: () => import('../views/estudio/Show')
    }
  ]
};
