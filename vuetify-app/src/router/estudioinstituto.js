export default {
  path: '/estudio-institutos',
  name: 'estudio-institutos',
  component: () => import('../components/estudioinstituto/Layout'),
  redirect: { name: 'EstudioInstitutoList' },
  children: [
    {
      name: 'EstudioInstitutoList',
      path: '',
      component: () => import('../views/estudioinstituto/List')
    },
    {
      name: 'EstudioInstitutoCreate',
      path: 'new',
      component: () => import('../views/estudioinstituto/Create')
    },
    {
      name: 'EstudioInstitutoUpdate',
      path: ':id/edit',
      component: () => import('../views/estudioinstituto/Update')
    },
    {
      name: 'EstudioInstitutoShow',
      path: ':id',
      component: () => import('../views/estudioinstituto/Show')
    }
  ]
};
