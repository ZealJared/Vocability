import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: null,
    loginError: null,
    words: []
  },
  getters: {
    loggedIn: state => {
      return (!!state.user)
    },
    loginError: state => {
      return state.loginError
    },
    user: state => {
      return state.user
    },
    words: state => {
      return state.words
    },
    wordById: state => wordId => {
      return state.words.find(word => word.Id === wordId)
    }
  },
  mutations: {
    user: (state, payload) => {
      state.user = payload
    },
    loginError: (state, payload) => {
      state.loginError = payload
    },
    words: (state, payload) => {
      state.words = payload
    },
    word: (state, payload) => {
      let theWord = payload
      let wordIndex = state.words.findIndex(word => word.Id === theWord.Id)
      if (wordIndex < 0) {
        state.words.push(theWord)
      } else {
        state.words.splice(wordIndex, 1, theWord)
      }
    },
    deleteWord: (state, payload) => {
      let index = state.words.findIndex(word => word.Id === payload)
      state.words.splice(index, 1)
    }
  }
})
