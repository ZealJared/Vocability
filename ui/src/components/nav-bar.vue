<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">
      <img src="@/assets/logo.svg" width="30" height="30" class="d-inline-block align-top" alt="">
      Vocability
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <router-link tag="li" active-class="active" class="nav-item" to="/home">
          <a class="nav-link">Home</a>
        </router-link>
        <router-link v-if="admin" tag="li" active-class="active" class="nav-item" to="/admin">
          <a class="nav-link">Admin</a>
        </router-link>
        <li class="nav-item">
          <a href="#" @click.prevent="logOut" class="nav-link">Log Out</a>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'nav-bar',
  computed: {
    admin () {
      return this.$store.getters.user.Admin
    }
  },
  methods: {
    logOut () {
      this.$api.logOut()
    }
  }
}
</script>
