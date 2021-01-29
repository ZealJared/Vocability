<template>
  <div class="card">
    <div class="card-body">
      <h1 class="card-title">Word</h1>
      <div v-if="word" id="form">
        <div class="form-group">
          <label for="name">Spelling</label>
          <input class="form-control" type="text" id="name" v-model="word.Spelling">
        </div>
        <div class="form-group">
          <label for="illustration">Illustration</label>
          <div>
            <img width="200" class="img-thumbnail" :src="word.Illustration">
          </div>
          <input @change="setIllustration" type="file" id="illustration" class="form-control-file">
        </div>
        <div class="form-group">
          <label for="pronunciation">Pronunciation</label>
          <div>
            <audio controls :src="word.Pronunciation"></audio>
          </div>
          <input @change="setPronunciation" type="file" id="pronunciation" class="form-control-file">
        </div>
        <div class="d-flex justify-content-between">
          <a href="#" @click.prevent="updateWord" class="btn btn-primary">Save</a>
          <a href="#" @click.prevent="deleteWord" class="btn btn-danger">Delete</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      word: null
    }
  },
  methods: {
    updateWord () {
      this.$api.updateWord(this.word)
    },
    deleteWord () {
      this.$api.deleteWord(this.word.Id)
    },
    setIllustration (e) {
      let reader = new FileReader()
      reader.addEventListener('load', e => {
        this.word.Illustration = reader.result
      })
      reader.readAsDataURL(e.target.files[0])
    },
    setPronunciation (e) {
      let reader = new FileReader()
      reader.addEventListener('load', e => {
        this.word.Pronunciation = reader.result
      })
      reader.readAsDataURL(e.target.files[0])
    }
  },
  async mounted () {
    let wordId = this.$route.params.id
    this.word = await this.$api.getWord(wordId)
  }
}
</script>
