<template>
  <div>
    <h1>Users</h1>
    <ul class="list-group">
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          New User
        </div>
        <a class="btn btn-success" href="#" @click.prevent="newUser()">Add</a>
      </li>
      <li v-for="(user, index) in users" :key="index" class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          {{ user.Name }}
        </div>
        <router-link :to="`/admin/user/${user.Id}/edit`" class="btn btn-primary">Edit</router-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data () {
    return {
      users: []
    }
  },
  methods: {
    newUser () {
      this.$api.newUser()
    }
  },
  async mounted () {
    this.users = await this.$api.getUsers()
  }
}
</script>
