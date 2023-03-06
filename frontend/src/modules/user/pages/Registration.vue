<script lang="ts">

import { defineComponent } from 'vue';
import { Either } from "@sweet-monads/either";
import { Subject } from 'rxjs';
import { Adapters as RegistrationAdapters } from '../adapters/registration';
import { Adapters as CreatingCodeAdapters } from '../adapters/creating-code';
import { UseCase as RegistrationUseCase } from '../uses-cases/registration/use-case';
import { UseCase as CreatingCodeUseCase } from '../uses-cases/creating-code/use-case';
import { Dtos } from '../../common/dtos';
import { Errors as CommonErrors } from '../../common/errors';

const creatingCodeSubject = new Subject<Either<CommonErrors.ErrorI, boolean>>();
const creatingCodeEmiter = new CreatingCodeAdapters.Emiter(creatingCodeSubject);
const creatingCodeApi = new CreatingCodeAdapters.Api();
const creatingCodeUseCase = new CreatingCodeUseCase.UseCase(
  creatingCodeApi,
  creatingCodeEmiter
);

const regSubject = new Subject<Either<CommonErrors.ErrorI, Dtos.Message>>();
const regEmiter = new RegistrationAdapters.Emiter(regSubject);
const regApi = new RegistrationAdapters.Api();
const regCase = new RegistrationUseCase.UseCase(
  regApi,
  regEmiter
);

export default defineComponent({
  name: 'Registration',
  methods:{
    sumbitRegistrationForm() {
      regCase.registry({
        name: this.name,
        email: this.email,
        password: this.password,
        confirmPassword: this.confirmPassword
      });
    },
    sumbitCreatingCodeForm() {
      creatingCodeUseCase.create({email: this.email});
    }
  },
  data() {
    return {
      error: null,
      message: '',
      name: '',
      email: '',
      confirmPassword: '',
      password: '',
      state: 'creating-code'
    }
  },
  beforeCreate() {
    regSubject.subscribe((x: Either<CommonErrors.ErrorI, Dtos.Message>) => {
      x.mapLeft((e: CommonErrors.ErrorI) => {
        this.error = true;
        this.message = e.message
      });

      x.map((d: Dtos.Message) => {
        this.error = false;
        this.message = d.message
      });
    });

    creatingCodeSubject.subscribe((x: Either<CommonErrors.ErrorI, Dtos.Message>) => {
      x.mapLeft((e: CommonErrors.ErrorI) => {
        this.error = true;
        this.message = e.message
      });

      x.map((d: Dtos.Message) => {
        this.error = false;
        this.state = 'registration'
        this.message = d.message
      });
    });
  },
  beforeUnmount() {
    //subject.unsubscribe();
  }
});

</script>

<template>
  <div class="row justify-content-center">
    <div class="col-xxl-4 col-xl-4 col-lg-4 col-12">
      <form v-if="state == 'creating-code'" @submit.prevent="sumbitCreatingCodeForm" class="my-5">
        <div v-if="error === true" class="alert alert-danger fw-light" role="alert">
          {{ message }}
        </div>
        <div v-if="error === false" class="alert alert-success fw-light" role="alert">
          {{ message }}
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input v-model="email" type="email" class="form-control" id="email" aria-describedby="emailHelp">
          <div id="emailHelp" class="form-text">We will send confirmation code.</div>
        </div>
        <button class="btn btn-danger mb-3">Ok</button>
      </form>
      <form v-if="state == 'registration'" @submit.prevent="sumbitRegistrationForm" class="my-5">
        <div v-if="error === true" class="alert alert-danger fw-light" role="alert">
          {{ message }}
        </div>
        <div v-if="error === false" class="alert alert-success fw-light" role="alert">
          {{ message }}
        </div>
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input v-model="name" type="text" class="form-control" id="name" aria-describedby="nameHelp">
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input v-model="email" type="email" class="form-control" id="email" aria-describedby="emailHelp">
          <div id="emailHelp" class="form-text">We recommend a work email address.</div>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input v-model="password" type="password" class="form-control" id="password" aria-describedby="passwordHelp">
          <div id="passwordHelp" class="form-text">Minimum length is 5 characters, maximum is 8.</div>
        </div>
        <div class="mb-3">
          <label for="confirmPassword" class="form-label">Confirm password</label>
          <input v-model="confirmPassword" type="password" class="form-control" id="confirmPassword" aria-describedby="confirmPasswordHelp">
          <div id="confirmPasswordHelp" class="form-text">Re-enter password.</div>
        </div>
        <button class="btn btn-danger mb-3">Create account</button>
        <div class="fw-light">Already have login and password? <router-link to="/user/auth/">Sign in</router-link></div>
      </form>
    </div>
  </div>
</template>

<style scoped>
  .btn-danger {
    background-color: #dc2c36;
    padding: 8px 40px;
  }

  .alert-danger {
    border: none;
    background-color: #e6e2e2;
  }
</style>
