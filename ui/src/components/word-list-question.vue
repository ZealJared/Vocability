<template>
  <div>
    <div>
      <audio :src="word.Pronunciation" controls autoplay></audio>
    </div>
    <div class="row">
      <div class="col-sm-4" v-for="(theWord, index) in wordOptions" :key="index">
        <img @click="answer(theWord.Id)" width="200" class="mb-3 mr-3 img-thumbnail" :src="theWord.Illustration">
      </div>
    </div>
    <div v-if="showIncorrect" class="position-fixed container" style="top: 0; z-index: 1;">
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">👎 Incorrect...</h5>
          <div class="card-text mb-3">Try Again!</div>
          <button class="btn btn-primary" @click="hideIncorrect">Okay</button>
        </div>
      </div>
    </div>
    <div v-if="showRight" class="position-fixed container" style="top: 0; z-index: 1;">
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">👍 Correct!</h5>
          <div class="card-text mb-3">Move on to the next word!</div>
          <button class="btn btn-primary" @click="nextQuestion">Okay</button>
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
    'review'
  ],
  data () {
    return {
      score: 100,
      showRight: false,
      showIncorrect: false
    }
  },
  computed: {
    reviewMode () {
      return !!this.review
    },
    incorrectOptionCount () {
      return this.reviewMode ? 0 : 5
    },
    wordOptions () {
      const incorrectOptions = this.words.filter(word => word.Id !== this.word.Id).sort(() => Math.random() - 0.5)
      const wordOptions = incorrectOptions.slice(0, this.incorrectOptionCount)
      wordOptions.push(this.word)
      wordOptions.sort(() => Math.random() - 0.5)
      return wordOptions
    }
  },
  methods: {
    hideIncorrect () {
      this.showIncorrect = false
    },
    nextQuestion () {
      this.showRight = false
      this.$emit('nextQuestion')
    },
    answer (wordId) {
      if (wordId === this.word.Id) {
        if (!this.reviewMode) {
          this.$api.recordWordScore(this.word.Id, this.score)
        }
        this.showRight = true
        return
      }
      const incorrectAnswerCost = 100 / (this.wordOptions.length - 1)
      this.score -= incorrectAnswerCost
      if (this.score <= 0) {
        this.score = 0
      }
      this.showIncorrect = true
    }
  }
}
</script>
