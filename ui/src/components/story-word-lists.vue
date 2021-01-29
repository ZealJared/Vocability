<template>
  <div>
    <h2 class="mt-3">Word Lists</h2>
    <ul class="list-group">
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          New Word List
        </div>
        <a class="btn btn-success" href="#" @click.prevent="newWordList()">Add</a>
      </li>
      <li v-for="(wordList, index) in wordLists" :key="index" class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          {{ wordList.Name }}
        </div>
        <router-link :to="`/admin/word_list/${wordList.Id}/edit`" class="btn btn-primary">Edit</router-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: [
    'storyId'
  ],
  data () {
    return {
      wordLists: []
    }
  },
  methods: {
    newWordList () {
      this.$api.newWordList(this.storyId)
    }
  },
  async mounted () {
    this.wordLists = await this.$api.getWordLists(this.storyId)
  }
}
</script>
