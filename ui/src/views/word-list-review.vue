<template>
  <div>
    <div v-if="complete" class="position-fixed container" style="top: 0; z-index: 1;">
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">🎉 Good job!</h5>
          <div class="card-text mb-3">You completed the word list!</div>
          <router-link class="btn btn-primary" to="/home">Return to Home</router-link>
        </div>
      </div>
    </div>
    <WordListQuestion v-if="word && !isStoryPageQuestion" @nextQuestion="nextQuestion" :key="word.Id" :word="word" :words="words" :review="reviewMode"></WordListQuestion>
    <StoryPageQuestion v-if="word && isStoryPageQuestion" @nextQuestion="storyPageNextQuestion" :key="word.Id" :word="word" :storyPageId="storyPageId" :words="words" :review="reviewMode"></StoryPageQuestion>
  </div>
</template>

<script>
import WordListQuestion from '../components/word-list-question.vue'
import StoryPageQuestion from '../components/story-page-question.vue'

export default {
  components: {
    WordListQuestion: WordListQuestion,
    StoryPageQuestion: StoryPageQuestion
  },
  data () {
    return {
      words: [],
      word: null,
      storyPagesComplete: [],
      index: 0,
      complete: false,
      storyPageId: null
    }
  },
  computed: {
    reviewMode () {
      return this.$route.path.match(/review$/) !== null
    },
    isStoryPageQuestion () {
      let wordExists = !!this.word
      if (!wordExists) {
        return false
      }
      let wordHasStoryPage = (this.word.extra.$storyPageId !== null)
      let wordStoryPageIsComplete = true
      if (wordHasStoryPage) {
        this.word.extra.$storyPageId.split(',').forEach(storyPageId => {
          if (!this.storyPagesComplete.includes(storyPageId)) {
            this.setStoryPageId(storyPageId)
            wordStoryPageIsComplete = false
          }
        })
      }
      let treatAsWordListQuestion = (!wordHasStoryPage || wordStoryPageIsComplete)
      let reviewIsComplete = this.complete
      return !treatAsWordListQuestion && !reviewIsComplete
    }
  },
  async mounted () {
    this.words = await this.$api.getWordListWordsIncludeStoryPageId(this.$route.params.id)
    this.words.sort(() => Math.random() - 0.5)
    this.word = this.words[0]
  },
  methods: {
    setStoryPageId (storyPageId) {
      this.storyPageId = storyPageId
    },
    nextQuestion () {
      let index = this.words.findIndex(word => word.Id === this.word.Id)
      this.word = this.words[index + 1]
      if (!this.word) {
        this.complete = true
      }
    },
    storyPageNextQuestion () {
      let index = this.words.findIndex(word => word.Id === this.word.Id)
      this.words.splice(index, 1)
      this.words.push(this.word) // add used word to end of list so it will come around to a word-list style question as well
      this.storyPagesComplete.push(this.storyPageId)
      this.word = this.words[index]
      if (!this.word) {
        this.complete = true
      }
    }
  }
}
</script>
