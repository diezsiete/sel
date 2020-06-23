export default {
  path: '/niveles-academicos',
  name: 'niveles-academicos',
  component: () => import('../components/nivelacademico/Layout'),
  redirect: { name: 'NivelAcademicoList' },
  children: [
    {
      name: 'NivelAcademicoList',
      path: '',
      component: () => import('../views/nivelacademico/List')
    },
    {
      name: 'NivelAcademicoCreate',
      path: 'new',
      component: () => import('../views/nivelacademico/Create')
    },
    {
      name: 'NivelAcademicoUpdate',
      path: ':id/edit',
      component: () => import('../views/nivelacademico/Update')
    },
    {
      name: 'NivelAcademicoShow',
      path: ':id',
      component: () => import('../views/nivelacademico/Show')
    }
  ]
};
