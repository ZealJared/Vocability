<template>
  <div v-if="story !== null">
    <h1>{{ story.Title }}: Word Lists</h1>
    <ul class="list-group">
      <li v-for="(wordList, index) in wordLists" :key="index" class="list-group-item d-flex justify-content-between align-items-center">
        {{ wordList.Name }}
        <div>
          <router-link tag='button' :to="`/word_list/${wordList.Id}/review`" class="btn btn-success mr-3">Review</router-link>
          <router-link tag='button' :to="`/word_list/${wordList.Id}/test`" class="btn btn-primary">Test</router-link>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data () {
    return {
      story: null,
      wordLists: []
    }
  },
  async mounted () {
    this.story = await this.$api.getStory(this.$route.params.id)
    this.wordLists = await this.$api.getWordLists(this.$route.params.id)
  }
}
</script>
