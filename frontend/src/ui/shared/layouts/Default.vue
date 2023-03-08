<script lang="ts">
import { defineComponent } from 'vue';
import { Either } from "@sweet-monads/either";
import Menu from '../components/Menu.vue';
import { authorizationSubject } from '../../../main';
import { AuthorizationUseCase } from '../../../use-cases/authorization';

export default defineComponent({
  name: 'Default',
  components: {
    Menu
  },
  data() {
    return {
      user: null,
      _private: {
        authorizationSubject: authorizationSubject
      }
    }
  },
  created() {
    this._private.authorizationSubject.subscribe((either: Either<Error, AuthorizationUseCase.Dtos.User>) => {
      either.map((user: AuthorizationUseCase.Dtos.User) => {
        this.user = user
      })
    })
  }
})
</script>
<template>
  <Menu :user="user"/>
  <div class="row justify-content-center">
    <div class="col-12 text-center">
      <slot></slot>
    </div>
  </div>
</template>
<style scoped>
</style>
