export default {
  path: '/estados-civiles',
  name: 'estados-civiles',
  component: () => import('../components/estadocivil/Layout'),
  redirect: { name: 'EstadoCivilList' },
  children: [
    {
      name: 'EstadoCivilList',
      path: '',
      component: () => import('../views/estadocivil/List')
    },
    {
      name: 'EstadoCivilCreate',
      path: 'new',
      component: () => import('../views/estadocivil/Create')
    },
    {
      name: 'EstadoCivilUpdate',
      path: ':id/edit',
      component: () => import('../views/estadocivil/Update')
    },
    {
      name: 'EstadoCivilShow',
      path: ':id',
      component: () => import('../views/estadocivil/Show')
    }
  ]
};
