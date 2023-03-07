<script lang="ts">

import { defineComponent } from 'vue';
import { Either } from "@sweet-monads/either";
import { Subject } from 'rxjs';
import { CreatingCodeAdapters } from '../../adapters/creating-code';
import { ConfirmingEmailAdapters } from '../../adapters/confirming-email';
import { RegistrationAdapters } from '../../adapters/registration';
import { CreatingCodeUseCase } from '../../use-cases/creating-code';
import { ConfirmingEmailUseCase } from '../../use-cases/confirming-email';
import { RegistrationUseCase } from '../../use-cases/registration';

export default defineComponent({
  name: 'Registration',
  methods:{
    sumbitRegistrationForm() {
      this._private.regUseCase.registry({
        name: this.name,
        email: this.email,
        password: this.password,
        confirmingPassword: this.confirmingPassword
      });
    },
    sumbitCreatingCodeForm() {
      this._private.creatingCodeUseCase.create({email: this.email});
    },
    sumbitConfirmingEmailForm() {
      this._private.confirmingEmailUseCase.confirm({
        code: this.code,
        email: this.email
      })
    }
  },
  data() {
    return {
      error: null,
      message: '',
      name: '',
      email: '',
      code: '',
      confirmingPassword: '',
      password: '',
      currentForm: 'creating-code',
      _private: {
        creatingCodeSubject: new Subject<Either<Error, boolean>>(),
        confirmingEmailSubject: new Subject<Either<Error, boolean>>(),
        regSubject: new Subject<Either<Error, boolean>>(),

        creatingCodeEmiter: null,
        creatingCodeApi: null,
        creatingCodeUseCase: null,

        confirmingEmailEmiter: null,
        confirmingEmailApi: null,
        confirmingEmailUseCase: null,

        regEmiter: null,
        regApi: null,
        regUseCase: null
      }
    }
  },
  beforeMount() {
    this._private.creatingCodeSubject.subscribe((either: Either<Error, boolean>) => {
      either.mapLeft((e: Error) => {
        this.error = true
        this.message = e.message
      })

      either.map((d: boolean) => {
        this.error = false
        this.currentForm = 'confirming-email'
        this.message = 'You have successfully created confirmation code'
      })
    })

    this._private.confirmingEmailSubject.subscribe((either: Either<Error, boolean>) => {
      either.mapLeft((e: Error) => {
        this.error = true
        this.message = e.message
      })

      either.map((d: boolean) => {
        this.error = false
        this.currentForm = 'registration'
        this.message = 'You have successfully confirmed email address'
      })

    })

    this._private.regSubject.subscribe((either: Either<Error, boolean>) => {
      either.mapLeft((e: Error) => {
        this.error = true
        this.message = e.message
      })

      either.map((d: boolean) => {
        this.error = false
        this.message = 'You have successfully created account'
      })
    })

    this._private.creatingCodeEmiter = new CreatingCodeAdapters.Emiter(this._private.creatingCodeSubject)
    this._private.creatingCodeApi = new CreatingCodeAdapters.Api()
    this._private.creatingCodeUseCase = new CreatingCodeUseCase.UseCase(
      this._private.creatingCodeApi,
      this._private.creatingCodeEmiter
    )

    this._private.confirmingEmailEmiter = new ConfirmingEmailAdapters.Emiter(this._private.confirmingEmailSubject)
    this._private.confirmingEmailApi = new ConfirmingEmailAdapters.Api()
    this._private.confirmingEmailUseCase = new ConfirmingEmailUseCase.UseCase(
      this._private.confirmingEmailApi,
      this._private.confirmingEmailEmiter
    )

    this._private.regEmiter = new RegistrationAdapters.Emiter(this._private.regSubject)
    this._private.regApi = new RegistrationAdapters.Api()
    this._private.regUseCase = new RegistrationUseCase.UseCase(
      this._private.regApi,
      this._private.regEmiter
    )
  },
  beforeUnmount() {
    this._private.regSubject.unsubscribe()
    this._private.creatingCodeSubject.unsubscribe()
    this._private.confirmingEmailSubject.unsubscribe()
  }
})

</script>

<template>
  <div class="row justify-content-center">
    <div class="col-xxl-4 col-xl-4 col-lg-4 col-12">
      <!-- creating confirmation code form -->
      <form autocomplete="off" v-if="currentForm == 'creating-code'" @submit.prevent="sumbitCreatingCodeForm" class="my-5">
        <div v-if="error === true" class="alert alert-danger fw-light" role="alert">
          {{ message }}
        </div>
        <div v-if="error === false" class="alert alert-success fw-light" role="alert">
          {{ message }}
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input v-model="email" type="email" class="form-control" id="email" aria-describedby="emailHelp">
          <div id="emailHelp" class="form-text">We will send confirmation code to your email.</div>
        </div>
        <button class="btn btn-danger mb-3">Send</button>
        <div  class="fw-light mb-2">
          Have you received confirmation code? <a href="#" @click="currentForm='confirming-email'" class="link-primary">Confirm</a>
        </div>
        <div  class="fw-light">
          Already have confirmed email address? <a href="#" @click="currentForm='registration'" class="link-primary">Registry</a>
        </div>
      </form>
      <!--  -->

      <!-- confirming email form -->
      <form autocomplete="off" v-if="currentForm == 'confirming-email'" @submit.prevent="sumbitConfirmingEmailForm" class="my-5">
        <div v-if="error === true" class="alert alert-danger fw-light" role="alert">
          {{ message }}
        </div>
        <div v-if="error === false" class="alert alert-success fw-light" role="alert">
          {{ message }}
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input v-model="email" type="email" class="form-control" id="email" aria-describedby="emailHelp">
          <div id="emailHelp" class="form-text">We will send confirmation code to your email.</div>
        </div>
        <div class="mb-3">
          <label for="text" class="form-label">Confirmation code</label>
          <input v-model="code" type="text" class="form-control" id="code" aria-describedby="codeHelp">
          <div id="codeHelp" class="form-text">We have sent a confirmation code to your email.</div>
        </div>
        <button class="btn btn-danger mb-3">Confirm</button>
        <div  class="fw-light mb-2">
          Dont have confirmation code? <a href="#" @click="currentForm='creating-code'" class="link-primary">Send</a>
        </div>
        <div  class="fw-light">
          Already have confirmed aemail address? <a @click="currentForm='registration'" href="#" class="link-primary">Registration</a>
        </div>
      </form>
      <!--  -->

      <!-- registration form -->
      <form autocomplete="off" v-if="currentForm == 'registration'" @submit.prevent="sumbitRegistrationForm" class="my-5">
        <div v-if="error === true" class="alert alert-danger fw-light" role="alert">
          {{ message }}
        </div>
        <div v-if="error === false" class="alert alert-success fw-light" role="alert">
          {{ message }}
        </div>
        <div class="fw-lighter fs-5 mb-3 mt-3">
          Before registering you have to confirm your email address
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
          <label for="confirmingPassword" class="form-label">Confirm password</label>
          <input v-model="confirmingPassword" type="password" class="form-control" id="confirmingPassword" aria-describedby="confirmingPasswordHelp">
          <div id="confirmingPasswordHelp" class="form-text">Re-enter password.</div>
        </div>
        <button class="btn btn-danger mb-3">Create account</button>
        <div  class="fw-light mb-2">
          Dont have confirmation code? <a href="#" @click="currentForm='creating-code'" class="link-primary">Send</a>
        </div>
        <div  class="fw-light mb-2">
          Have you received confirmation code? <a @click="currentForm='confirming-email'" href="#" class="link-primary">Confirm</a>
        </div>
        <div class="fw-light">
          Already have login and password? <router-link class="link-primary" to="/user/auth/">Login</router-link>
        </div>
      </form>
      <!--  -->
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
