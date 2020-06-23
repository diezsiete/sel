export default {
  path: '/experiencia-duraciones',
  name: 'experiencia-duraciones',
  component: () => import('../components/experienciaduracion/Layout'),
  redirect: { name: 'ExperienciaDuracionList' },
  children: [
    {
      name: 'ExperienciaDuracionList',
      path: '',
      component: () => import('../views/experienciaduracion/List')
    },
    {
      name: 'ExperienciaDuracionCreate',
      path: 'new',
      component: () => import('../views/experienciaduracion/Create')
    },
    {
      name: 'ExperienciaDuracionUpdate',
      path: ':id/edit',
      component: () => import('../views/experienciaduracion/Update')
    },
    {
      name: 'ExperienciaDuracionShow',
      path: ':id',
      component: () => import('../views/experienciaduracion/Show')
    }
  ]
};
