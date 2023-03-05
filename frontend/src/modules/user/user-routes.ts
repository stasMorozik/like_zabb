const routes = [
  {
    path: '/users/new',
    component: () => import('@/modules/user/pages/Registration.vue'),
  },
]

export {
  routes
};
