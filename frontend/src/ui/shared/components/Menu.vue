<script lang="ts">
import { defineComponent } from 'vue';
import type { PropType } from 'vue';
import { AuthorizationUseCase } from '../../../use-cases/authorization';

export default defineComponent({
  name: 'Menu',
  data() {
    return {
      isOpen: false
    }
  },
  methods: {
    open() {
      this.isOpen = !this.isOpen
    }
  },
  props: {
    user: Object as PropType<AuthorizationUseCase.Dtos.User | null>
  }
})
</script>
<template>
  <nav class="main-nav d-flex justify-content-center">
    <div :class="{ 'w-100': isOpen == true, 'w-50': isOpen == false }" class="menu h-100 text-center">
      <div class="header">
        <a
          :class="{ 'justify-content-start': isOpen == true, 'justify-content-center': isOpen == false }"
          class="p-3 link-light d-flex align-items-center"
        >
          <i class="fa fa-gg" aria-hidden="true"></i>
          <span :class="{ 'd-flex': isOpen == true, 'd-none': isOpen == false }">Some Name</span>
        </a>
      </div>
      <div class="content d-flex flex-column justify-content-between">
        <router-link
          :class="{ 'justify-content-start': isOpen == true, 'justify-content-center': isOpen == false }"
          title="sensors-dashboard"
          class="link-light p-3 d-flex h-100 align-items-center"
          to="/sensors/"
        >
          <i class="fa fa-th-large" aria-hidden="true"></i>
          <span :class="{ 'd-flex': isOpen == true, 'd-none': isOpen == false }">Sensors</span>
        </router-link>
        <router-link
          :class="{ 'justify-content-start': isOpen == true, 'justify-content-center': isOpen == false }"
          title="statistics"
          class="link-light p-3 d-flex h-100 align-items-center"
          to="/statistics/"
        >
          <i class="fa fa-area-chart" aria-hidden="true"></i>
          <span :class="{ 'd-flex': isOpen == true, 'd-none': isOpen == false }">Statistics</span>
        </router-link>
      </div>
      <div
        :class="{ 'justify-content-start': isOpen == true, 'justify-content-center': isOpen == false }"
        class="footer d-flex align-items-end"
      >
        <a
          :class="{ 'justify-content-start': isOpen == true, 'justify-content-center': isOpen == false }"
          class="link-light p-3 d-flex align-items-center"
        >
          <i class="fa fa-user" aria-hidden="true"></i>
          <span :class="{ 'd-flex': isOpen == true, 'd-none': isOpen == false }">{{ user?.name }}</span>
        </a>
      </div>
    </div>
    <div :class="{ 'w-100': isOpen == false, 'w-25': isOpen == true }" class="menu-controll h-100 pt-2 pb-3 ps-3 text-start">
      <a @click="open" class="link-dark" href="#">
        <i class="fa fa-bars" aria-hidden="true"></i>
      </a>
    </div>
  </nav>
</template>
<style scoped>
  nav {
    position: fixed;
    left: 0;
    top: 0;
    height: 100%;
    width: 20rem;
    font-size: 1.5rem;
  }

  .menu {
    background-color: #11101b;
    transition: width .5s;
  }

  .menu-controll {
    transition: width .5s;
  }

  .menu .header {
    height: 10%;
  }

  .menu .content {
    height: 10%;
  }

  .menu .content a:hover {
    background-color: #233140;
  }

  .menu .footer {
    height: 80%;
  }

  span {
    font-size: .9rem;
    width: 100%;
  }

  a {
    text-decoration: none;
  }

  a i {
    width: 50%;
  }
</style>
