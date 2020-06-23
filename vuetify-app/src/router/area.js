export default {
  path: '/areas',
  name: 'areas',
  component: () => import('../components/area/Layout'),
  redirect: { name: 'AreaList' },
  children: [
    {
      name: 'AreaList',
      path: '',
      component: () => import('../views/area/List')
    },
    {
      name: 'AreaCreate',
      path: 'new',
      component: () => import('../views/area/Create')
    },
    {
      name: 'AreaUpdate',
      path: ':id/edit',
      component: () => import('../views/area/Update')
    },
    {
      name: 'AreaShow',
      path: ':id',
      component: () => import('../views/area/Show')
    }
  ]
};
