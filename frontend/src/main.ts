import { Either, left } from "@sweet-monads/either";
import { Subject } from 'rxjs';
import { createRouter, createWebHistory, Route } from 'vue-router';
import { createApp } from 'vue';
import './style.css';
import App from './App.vue';

import { routes as RegistrationRouter } from './ui/routes/registration';
import { routes as AuthenticationRouter } from './ui/routes/authentication';
import { routes as SensorsRouter } from './ui/routes/sensors';
import { AuthorizationAdapters } from './adapters/authorization';
import { AuthorizationUseCase } from './use-cases/authorization';

export const authorizationSubject = new Subject<Either<Error, AuthorizationUseCase.Dtos.User>>()

const authorizationEmiter = new AuthorizationAdapters.Emiter(authorizationSubject)
const authorizationApi = new AuthorizationAdapters.Api()
const authorizationUseCase = new AuthorizationUseCase.UseCase(
  authorizationApi,
  authorizationEmiter
)

const router = createRouter({
  history: createWebHistory(),
  routes: [
    ...RegistrationRouter,
    ...AuthenticationRouter,
    ...SensorsRouter
  ]
})

let currentRoute: Route | null = null

router.beforeEach((to, from) => {
  currentRoute = to
  authorizationUseCase.auth()
})

createApp(App).use(router).mount('#app')

authorizationSubject.subscribe((either: Either<Error, AuthorizationUseCase.Dtos.User>) => {
  either.mapLeft((_: Error) => {
    if (currentRoute.name != 'authentication-page' && currentRoute.name != 'registration-page') {
      router.push({name: 'authentication-page'})
    }
  })

  either.map((user: AuthorizationUseCase.Dtos.User) => {
    if (currentRoute.name == 'authentication-page') {
      router.push({name: 'sensors'})
    }

    if (currentRoute.name == 'registration-page') {
      router.push({name: 'sensors'})
    }
  })
})
