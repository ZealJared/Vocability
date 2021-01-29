<template>
  <div id="app">
    <NavBar v-if='loggedIn'></NavBar>
    <router-view class="container mt-5 mb-5"/>
  </div>
</template>

<script>
import NavBar from '@/components/nav-bar.vue'

export default {
  components: {
    NavBar
  },
  computed: {
    loggedIn () {
      return this.$store.getters.loggedIn
    }
  },
  mounted () {
    if (this.$router.currentRoute.path === '/') {
      this.$router.replace('/home')
    }
  }
}
</script>

<style lang="scss">
@import "bootstrap";
$material-icons-font-path: '~material-icons/iconfont/';
@import '~material-icons/iconfont/material-icons.scss';
</style>
