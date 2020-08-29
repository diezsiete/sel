export default {
  path: '/hvs',
  name: 'hvs',
  component: () => import('../components/hv/Layout'),
  redirect: { name: 'HvList' },
  children: [
    {
      name: 'HvList',
      path: '',
      component: () => import('../views/hv/List')
    },
    {
      name: 'HvCreate',
      path: 'new',
      component: () => import('../views/hv/Create')
    },
    {
      name: 'HvUpdate',
      path: ':id/edit',
      component: () => import('../views/hv/Update')
    },
    {
      name: 'HvShow',
      path: ':id',
      component: () => import('../views/hv/Show')
    }
  ]
};
