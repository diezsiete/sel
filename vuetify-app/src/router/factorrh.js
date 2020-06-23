export default {
  path: '/factores-rh',
  name: 'factores-rh',
  component: () => import('../components/factorrh/Layout'),
  redirect: { name: 'FactorRhList' },
  children: [
    {
      name: 'FactorRhList',
      path: '',
      component: () => import('../views/factorrh/List')
    },
    {
      name: 'FactorRhCreate',
      path: 'new',
      component: () => import('../views/factorrh/Create')
    },
    {
      name: 'FactorRhUpdate',
      path: ':id/edit',
      component: () => import('../views/factorrh/Update')
    },
    {
      name: 'FactorRhShow',
      path: ':id',
      component: () => import('../views/factorrh/Show')
    }
  ]
};
