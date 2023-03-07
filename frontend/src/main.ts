import { Either, left } from "@sweet-monads/either";
import { Subject } from 'rxjs';
import { createRouter, createWebHistory } from 'vue-router';
import { createApp } from 'vue';
import './style.css';
import App from './App.vue';

import { routes as RegistrationRouter } from './ui/routes/registration';
import { routes as AuthenticationRouter } from './ui/routes/authentication';
import { routes as SensorsRouter } from './ui/routes/sensors';
import { AuthorizationAdapters } from './adapters/authorization';
import { AuthorizationUseCase } from './use-cases/authorization';

const subject = new Subject<Either<Error, AuthorizationUseCase.Dtos.User>>()

const authorizationEmiter = new AuthorizationAdapters.Emiter(subject)
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

let either: Either<Error, AuthorizationUseCase.Dtos.User> = left({message: ''})

router.beforeEach((to, from) => {
  authorizationUseCase.auth()
  console.log(either)
  // if (either.isLeft()) {
  //   if (to.name != 'authentication-page' && to.name != 'registration-page') {
  //     return { name: 'authentication-page' }
  //   }
  // }

  // if (either.isRight()) {
  //   if (to.name == 'authentication-page') {
  //     return { name: 'sensors' }
  //   }

  //   if (to.name == 'registration-page') {
  //     return { name: 'sensors' }
  //   }
  // }
})

createApp(App).use(router).mount('#app')

subject.subscribe((e: Either<Error, AuthorizationUseCase.Dtos.User>) => {
  either = e;
  either.mapLeft((error: Error) => {
  })

  either.map((user: AuthorizationUseCase.Dtos.User) => {
  })
})
