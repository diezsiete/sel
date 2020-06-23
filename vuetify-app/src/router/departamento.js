export default {
  path: '/departamentos',
  name: 'departamentos',
  component: () => import('../components/departamento/Layout'),
  redirect: { name: 'DepartamentoList' },
  children: [
    {
      name: 'DepartamentoList',
      path: '',
      component: () => import('../views/departamento/List')
    },
    {
      name: 'DepartamentoCreate',
      path: 'new',
      component: () => import('../views/departamento/Create')
    },
    {
      name: 'DepartamentoUpdate',
      path: ':id/edit',
      component: () => import('../views/departamento/Update')
    },
    {
      name: 'DepartamentoShow',
      path: ':id',
      component: () => import('../views/departamento/Show')
    }
  ]
};
