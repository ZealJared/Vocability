<template>
  <ul v-if="stories.length > 0" class="list-group">
    <li v-for="(story, index) in stories" :key="index" class="list-group-item d-flex justify-content-between align-items-center">
      <router-link :to="`/story/${story.Id}/word_lists`">{{ story.Title }}</router-link>
    </li>
  </ul>
</template>

<script>
export default {
  data () {
    return {
      stories: []
    }
  },
  async mounted () {
    this.stories = await this.$api.getStories()
  }
}
</script>
