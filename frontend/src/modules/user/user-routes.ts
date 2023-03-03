const routes = [
  {
    path: '/user/new',
    component: () => import('@/modules/user/pages/Registration.vue'),
  },
]

export {
  routes
};
