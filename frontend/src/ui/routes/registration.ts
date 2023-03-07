const routes = [
  {
    name: 'registration-page',
    path: '/users/new',
    component: () => import('@/ui/pages/Registration.vue'),
  }
]

export {
  routes
}
