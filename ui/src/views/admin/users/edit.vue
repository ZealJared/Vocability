<template>
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Profile</h5>
      <div v-if="user" id="form">
        <div class="form-group">
          <label for="name">Name</label>
          <input class="form-control" type="text" id="name" v-model="user.Name">
        </div>
        <div class="form-group">
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="active" v-model="user.Active">
            <label for="active" class="form-check-label">Active</label>
          </div>
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="admin" v-model="user.Admin">
            <label for="admin" class="form-check-label">Admin</label>
          </div>
        </div>
        <div class="d-flex justify-content-between">
          <a href="#" @click.prevent="updateUser" class="btn btn-primary">Save</a>
          <a href="#" @click.prevent="deleteUser" class="btn btn-danger">Delete</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      user: null
    }
  },
  methods: {
    updateUser () {
      this.$api.updateUser(this.user)
    },
    deleteUser () {
      this.$api.deleteUser(this.user.Id)
    }
  },
  async mounted () {
    const userId = this.$route.params.id
    this.user = await this.$api.getUser(userId)
  }
}
</script>
