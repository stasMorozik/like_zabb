<script lang="ts">
  import { ref, defineComponent, onMounted } from 'vue';
  import { Either } from "@sweet-monads/either";
  import { map, catchError, of, Subject, Observable } from 'rxjs';

  import { Registration as RegistrationAdapters } from '../adapters/registration';
  import { Registration as RegistrationUseCase } from '../../../use-cases/user/registration';

  const subject = new Subject<Either<Error[], RegistrationUseCase.Success>>();

  const emiter = new RegistrationAdapters.Emiter(subject);
  const api = new RegistrationAdapters.Api();

  const useCase = new RegistrationUseCase.UseCase(
    api,
    emiter
  );

  export default defineComponent({
    name: 'Registration',
    methods:{
      registry() {
        useCase.registry({
          name: 'test',
          email: 'test@gmail.com',
          password: '12345',
          confirmPassword: '12345'
        });
      }
    },
    setup() {

    },
    beforeCreate() {
      subject.subscribe((x: Either<Error[], RegistrationUseCase.Success>) => {
        console.log(x.isLeft())
      });
    },
    beforeUnmount() {
      subject.unsubscribe();
    }
  });
</script>

<template>
  <div class="row justify-content-center">
    <div class="col-xxl-4 col-xl-4 col-lg-4 col-12">
      <form @submit.prevent="registry" class="my-5">
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" aria-describedby="nameHelp">
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" aria-describedby="emailHelp">
          <div id="emailHelp" class="form-text">We recommend a work email address.</div>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" aria-describedby="passwordHelp">
          <div id="passwordHelp" class="form-text">Minimum length is 5 characters, maximum is 8.</div>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Confirm password</label>
          <input type="password" class="form-control" id="password" aria-describedby="passwordHelp">
          <div id="passwordHelp" class="form-text">Re-enter password.</div>
        </div>
        <button class="btn btn-danger">Create account</button>
      </form>
      <div class="fw-light">Already have login and password? <router-link to="/user/auth/">Sign in</router-link></div>
    </div>
  </div>
</template>

<style scoped>
  .btn-danger {
    background-color: #dc2c36;
    padding: 8px 40px;
  }
</style>
