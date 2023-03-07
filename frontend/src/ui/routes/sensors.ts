const routes = [
  {
    name: 'sensors',
    path: '/sensors/',
    component: () => import('@/ui/pages/Sensors.vue'),
  }
]

export {
  routes
}
