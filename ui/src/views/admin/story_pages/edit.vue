<template>
  <div v-if="storyPage">
    <div class="card">
      <div class="card-body">
        <h1 class="card-title">Story Page</h1>
        <div id="form">
          <div class="form-group">
            <label for="illustration">Illustration</label>
            <div>
              <img width="200" class="img-thumbnail" :src="storyPage.Illustration">
            </div>
            <input @change="setIllustration" type="file" id="illustration" class="form-control-file">
          </div>
          <div class="form-group">
            <label for="text">Text</label>
            <textarea class="form-control" id="text" v-model="storyPage.Text"></textarea>
          </div>
          <div class="form-group">
            <label for="audio">Audio</label>
            <div>
              <audio controls :src="storyPage.Audio"></audio>
            </div>
            <input @change="setAudio" type="file" id="audio" class="form-control-file">
          </div>
          <div class="d-flex justify-content-between">
            <a href="#" @click.prevent="updateStoryPage" class="btn btn-primary">Save</a>
            <a href="#" @click.prevent="deleteStoryPage" class="btn btn-danger">Delete</a>
          </div>
        </div>
      </div>
    </div>
    <h2 class="mt-3">Words</h2>
    <h5>Add a word:</h5>
    <WordSearch @selectWord="addStoryPageWord"></WordSearch>
    <ListWords :words="words" @removeWord="removeStoryPageWord"></ListWords>
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
      storyPage: null,
      words: []
    }
  },
  methods: {
    updateStoryPage () {
      this.$api.updateStoryPage(this.storyPage)
    },
    deleteStoryPage () {
      this.$api.deleteStoryPage(this.storyPage.Id)
    },
    setIllustration (e) {
      let reader = new FileReader()
      reader.addEventListener('load', e => {
        this.storyPage.Illustration = reader.result
      })
      reader.readAsDataURL(e.target.files[0])
    },
    setAudio (e) {
      let reader = new FileReader()
      reader.addEventListener('load', e => {
        this.storyPage.Audio = reader.result
      })
      reader.readAsDataURL(e.target.files[0])
    },
    async addStoryPageWord (wordId) {
      let { storyPageWord } = await this.$api.addStoryPageWord(this.storyPage.Id, wordId)
      this.words.push(this.$store.getters.wordById(storyPageWord.WordId))
    },
    async removeStoryPageWord (wordId) {
      let storyPageWord = await this.$api.removeStoryPageWord(this.storyPage.Id, wordId)
      this.words.splice(this.words.findIndex(word => word.Id === storyPageWord.WordId), 1)
    }
  },
  async mounted () {
    let storyPageId = this.$route.params.id
    this.storyPage = await this.$api.getStoryPage(storyPageId)
    this.words = await this.$api.getStoryPageWords(storyPageId)
  }
}
</script>
