import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import './style.css';
import App from './App.vue';
import { routes as RegistrationRouter } from './ui/routes/registration';

const NotFoundComponent = { template: '<p>Страница не найдена</p>' }

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      component: NotFoundComponent,
    },
    ...RegistrationRouter
  ]
});

createApp(App).use(router).mount('#app');
