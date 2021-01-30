<template>
  <div v-if="wordList">
    <div class="card mb-3">
      <div class="card-body">
        <h1 class="card-title">Word List</h1>
        <div id="form">
          <div class="form-group">
            <label for="name">Name</label>
            <input class="form-control" type="text" id="name" v-model="wordList.Name">
          </div>
          <div class="d-flex justify-content-between">
            <a href="#" @click.prevent="updateWordList" class="btn btn-primary">Save</a>
            <a href="#" @click.prevent="deleteWordList" class="btn btn-danger">Delete</a>
          </div>
        </div>
      </div>
    </div>
    <h2>Words</h2>
    <h5>Add a word:</h5>
    <WordSearch @selectWord="addWordListWord"></WordSearch>
    <ListWords :words="words" @removeWord="removeWordListWord"></ListWords>
  </div>
</template>

<script>
import ListWords from '@/components/list-words.vue'
import WordSearch from '@/components/word-search.vue'

export default {
  components: {
    ListWords: ListWords,
    WordSearch: WordSearch
  },
  data () {
    return {
      wordList: null,
      words: []
    }
  },
  methods: {
    updateWordList () {
      this.$api.updateWordList(this.wordList)
    },
    deleteWordList () {
      this.$api.deleteWordList(this.wordList.Id)
    },
    async addWordListWord (wordId) {
      const { wordListWord } = await this.$api.addWordListWord(this.wordList.Id, wordId)
      this.words.push(this.$store.getters.wordById(wordListWord.WordId))
    },
    async removeWordListWord (wordId) {
      const wordListWord = await this.$api.removeWordListWord(this.wordList.Id, wordId)
      this.words.splice(this.words.findIndex(word => word.Id === wordListWord.WordId), 1)
    }
  },
  async mounted () {
    const wordListId = this.$route.params.id
    this.wordList = await this.$api.getWordList(wordListId)
    this.words = await this.$api.getWordListWords(wordListId)
  }
}
</script>
