<template>
  <div>
    <h1>Stories</h1>
    <ul class="list-group">
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          New Story
        </div>
        <a class="btn btn-success" href="#" @click.prevent="newStory()">Add</a>
      </li>
      <li v-for="(story, index) in stories" :key="index" class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          {{ story.Title }}
        </div>
        <router-link :to="`/admin/story/${story.Id}/edit`" class="btn btn-primary">Edit</router-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data () {
    return {
      stories: []
    }
  },
  methods: {
    newStory () {
      this.$api.newStory()
    }
  },
  async mounted () {
    this.stories = await this.$api.getStories()
  }
}
</script>
