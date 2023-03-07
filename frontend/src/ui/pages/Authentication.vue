<script lang="ts">
import { defineComponent } from 'vue';
import { Either } from "@sweet-monads/either";
import { Subject } from 'rxjs';
import { AuthenticationAdapters } from '../../adapters/authentication';
import { AuthenticationUseCase } from '../../use-cases/authentication';

export default defineComponent({
  name: 'Authentication',
  methods: {
    sumbit() {
      this._private.authenticationUseCase.auth({
        email: this.email,
        password: this.password
      })
    }
  },
  data() {
    return {
      error: null,
      message: '',
      email: '',
      password: '',
      _private: {
        authenticationSubject: new Subject<Either<Error, boolean>>(),
        authenticationEmiter: null,
        authenticationApi: null,
        authenticationUseCase: null
      }
    }
  },
  beforeMount() {
    this._private.authenticationSubject.subscribe((either: Either<Error, boolean>) => {
      either.mapLeft((e: Error) => {
        this.error = true
        this.message = e.message
      })

      either.map((_: boolean) => {
        this.error = null
        this.$router.push({name: 'sensors'})
      })
    })

    this._private.authenticationEmiter = new AuthenticationAdapters.Emiter(this._private.authenticationSubject)
    this._private.authenticationApi = new AuthenticationAdapters.Api()
    this._private.authenticationUseCase = new AuthenticationUseCase.UseCase(
      this._private.authenticationApi,
      this._private.authenticationEmiter
    )
  },
  beforeUnmount() {
    this._private.authenticationSubject.unsubscribe()
  }
})

</script>
<template>
  <div class="row justify-content-center">
    <div class="col-xxl-4 col-xl-4 col-lg-4 col-12">
      <form autocomplete="off" @submit.prevent="sumbit" class="my-5">
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
          <label for="password" class="form-label">Password</label>
          <input v-model="password" type="password" class="form-control" id="password" aria-describedby="passwordHelp">
          <div id="passwordHelp" class="form-text">Minimum length is 5 characters, maximum is 8.</div>
        </div>
        <button class="btn btn-danger mb-3">Sign in</button>
        <div class="fw-light">
          Have not account? <router-link class="link-primary" to="/users/new/">Create</router-link>
        </div>
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
