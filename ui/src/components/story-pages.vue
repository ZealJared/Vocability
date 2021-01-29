<template>
  <div>
    <h2 class="mt-3">Story Pages</h2>
    <ul class="list-group">
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          New Story Page
        </div>
        <a class="btn btn-success" href="#" @click.prevent="newStoryPage()">Add</a>
      </li>
      <li v-for="(storyPage, index) in storyPages" :key="index" class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          {{ truncate(storyPage.Text, 30) }}
        </div>
        <router-link :to="`/admin/story_page/${storyPage.Id}/edit`" class="btn btn-primary">Edit</router-link>
      </li>
    </ul>
  </div>
</template>

<script>
import Utility from '../utility.js'

export default {
  props: [
    'storyId'
  ],
  data () {
    return {
      storyPages: []
    }
  },
  methods: {
    newStoryPage () {
      this.$api.newStoryPage(this.storyId)
    },
    truncate (string, length) {
      return Utility.truncate(string, length)
    }
  },
  async mounted () {
    this.storyPages = await this.$api.getStoryPages(this.storyId)
  }
}
</script>
