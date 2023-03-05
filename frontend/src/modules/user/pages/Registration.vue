<script lang="ts">

import { defineComponent } from 'vue';
import { Either } from "@sweet-monads/either";
import { Subject } from 'rxjs';
import { Adapters } from '../adapters/registration';
import { UseCase } from '../uses-cases/registration/use-case';
import { Dtos } from '../uses-cases/registration/dtos';
import { Errors as CommonErrors } from '../../common/errors';
import { Errors as RegistrationErrors } from '../uses-cases/registration/errors';

const subject = new Subject<Either<Error, Dtos.SuccessRegistration>>();

const emiter = new Adapters.Emiter(subject);
const api = new Adapters.Api();

const useCase = new UseCase.Registration(
  api,
  emiter
);

export default defineComponent({
  name: 'Registration',
  methods:{
    registry() {
      useCase.registry({
        name: 'name',
        email: 'email',
        password: '12345',
        confirmPassword: ''
      });
    }
  },
  data() {
    return {
      emailValidation: false,
      nameValidation: false,
      passwordValidation: false,
      passwordNotEqualValidation: false,
      apiError: false,
      message: ''
    }
  },
  beforeCreate() {
    subject.subscribe((x: Either<Error, Dtos.SuccessRegistration>) => {
      x.mapLeft((e: Error) => {
        if (e instanceof RegistrationErrors.PasswordNotEqual) {
          this.passwordNotEqualValidation = true;
          this.message = e.message;
        }

        if (e instanceof CommonErrors.Domain.InvalidEmail) {
          this.emailValidation = true;
          this.message = e.message;
        }

        if (e instanceof CommonErrors.Domain.InvalidPassword) {
          this.passwordValidation = true;
          this.message = e.message;
        }

        if (e instanceof CommonErrors.Domain.InvalidName) {
          this.nameValidation = true;
          this.message = e.message;
        }

        if (e instanceof CommonErrors.Infrastructure.BadRequest) {
          this.apiError = true;
          this.message = e.message;
        }
      });

      x.map((s: Dtos.SuccessRegistration) => {

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
      <form @submit.prevent="registry" class="my-5">
        <div v-if="apiError" class="alert alert-danger fw-light" role="alert">
          {{ message }}
        </div>
        <div class="mb-3">
          <div v-if="nameValidation" class="alert alert-danger fw-light" role="alert">
            {{ message }}
          </div>
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" aria-describedby="nameHelp">
        </div>
        <div class="mb-3">
          <div v-if="emailValidation" class="alert alert-danger fw-light" role="alert">
            {{ message }}
          </div>
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" aria-describedby="emailHelp">
          <div id="emailHelp" class="form-text">We recommend a work email address.</div>
        </div>
        <div class="mb-3">
          <div v-if="passwordValidation" class="alert alert-danger fw-light" role="alert">
            {{ message }}
          </div>
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" aria-describedby="passwordHelp">
          <div id="passwordHelp" class="form-text">Minimum length is 5 characters, maximum is 8.</div>
        </div>
        <div class="mb-3">
          <div v-if="passwordNotEqualValidation" class="alert alert-danger fw-light" role="alert">
            {{ message }}
          </div>
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

  .alert-danger {
    border: none;
    background-color: #e6e2e2;
  }
</style>
