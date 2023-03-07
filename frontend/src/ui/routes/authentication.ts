const routes = [
  {
    name: 'authentication-page',
    path: '/users/auth',
    component: () => import('@/ui/pages/Authentication.vue'),
  }
]

export {
  routes
}
