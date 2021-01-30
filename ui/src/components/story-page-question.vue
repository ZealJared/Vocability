<template>
  <div>
    <div v-if="showComplete" class="position-fixed container" style="top: 0; z-index: 1;">
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">✔️ Complete!</h5>
          <div class="card-text mb-3">Move on to the next word!</div>
          <button class="btn btn-primary" @click="nextQuestion">Okay</button>
        </div>
      </div>
    </div>
    <div class="row">
      <div v-if="storyPage" class="col-lg-6">
        <img :src="storyPage.Illustration" class="mb-3 mr-3 img-thumbnail">
        <div>
          <audio :src="storyPage.Audio" autoplay controls></audio>
        </div>
      </div>
      <div class="col-lg-6 row">
        <div class="col-lg-4 position-relative text-center" v-for="(theWord, index) in wordOptions" :key="index">
          <div v-if="isIncorrect(theWord.Id)" class="alert alert-danger position-absolute d-flex align-items center">
              <i class="material-icons mr-1">cancel</i>
              Incorrect.
          </div>
          <div v-if="isCorrect(theWord.Id)" class="alert alert-success position-absolute d-flex align-items center">
              <i class="material-icons mr-1">check_circle</i>
              Correct!
          </div>
          <img @click="answer(theWord.Id)" class="mb-3 mr-3 img-thumbnail" :src="theWord.Illustration">
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    'word',
    'words',
    'storyPageId',
    'review'
  ],
  data () {
    return {
      score: 100,
      storyPage: null,
      correctAnswers: [],
      incorrectAnswers: [],
      showComplete: false
    }
  },
  computed: {
    correctOptionCount () {
      return this.words.filter(theWord => theWord.extra.$storyPageId && theWord.extra.$storyPageId.split(',').includes(this.storyPageId)).length
    },
    incorrectOptionCount () {
      return this.review ? 0 : 5
    },
    wordOptions () {
      const correctOptions = this.words.filter(theWord => theWord.extra.$storyPageId && theWord.extra.$storyPageId.split(',').includes(this.storyPageId))
      const incorrectOptions = this.words.filter(theWord => !theWord.extra.$storyPageId || !theWord.extra.$storyPageId.split(',').includes(this.storyPageId)).sort(() => Math.random() - 0.5).slice(0, this.incorrectOptionCount)
      const allOptions = correctOptions.concat(incorrectOptions).sort(() => Math.random() - 0.5)
      return allOptions
    },
    isCorrect () {
      return wordId => this.correctAnswers.includes(wordId)
    },
    isIncorrect () {
      return wordId => this.incorrectAnswers.includes(wordId)
    }
  },
  methods: {
    answer (wordId) {
      if (this.correctAnswers.includes(wordId) || this.incorrectAnswers.includes(wordId)) {
        return
      }
      const word = this.words.find(word => word.Id === wordId)
      if (word.extra.$storyPageId.split(',').includes(this.storyPageId)) {
        this.correctAnswers.push(wordId)
      } else {
        this.incorrectAnswers.push(wordId)
      }
      if (this.correctAnswers.length === this.correctOptionCount) {
        this.showComplete = true
      }
    },
    nextQuestion () {
      this.$emit('nextQuestion')
    }
  },
  async mounted () {
    this.storyPage = await this.$api.getStoryPage(this.storyPageId)
  }
}
</script>
