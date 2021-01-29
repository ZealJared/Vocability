<template>
<div>
  <h1>Log In</h1>
  <div class="form-group">
    <label for="username">User Name</label>
    <input v-model="username" type="text" name="username" id="username" class="form-control">
  </div>
  <div v-if="loginError" class="alert alert-danger">{{ loginError }}</div>
  <button @click="login" class="btn btn-primary">Go</button>
</div>
</template>

<script>
export default {
  data () {
    return {
      username: '',
      error: null
    }
  },
  name: 'log-in-view',
  computed: {
    loginError () {
      return this.$store.getters.loginError
    }
  },
  methods: {
    login () {
      if (this.username.length < 2) {
        this.$store.commit('loginError', 'Username is too short.')
        return
      }
      this.$api.logInWithUsernamePassword(this.username, this.username)
    }
  }
}
</script>
