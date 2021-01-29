<template>
  <div class="position-relative mb-3">
    <div class="input-group">
      <div class="input-group-prepend"><span class="input-group-text">Search</span></div>
      <input type="search" name="term" id="term" v-model="term" class="form-control">
    </div>
    <ul class="list-group position-absolute" style="z-index:1;">
      <li v-for="(word, index) in words" :key="index" class="list-group-item d-flex justify-content-between align-items-center">
        {{ word.Spelling }}
        <div class="ml-3">
          <button class="btn btn-success" @click="selectWord(word.Id)">Add</button>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data () {
    return {
      term: ''
    }
  },
  computed: {
    words () {
      if (this.term.length === 0) {
        return []
      }
      this.$api.getWords()
      return this.$store.getters.words.filter(word => word.Spelling.toLowerCase().includes(this.term.toLowerCase()))
    }
  },
  methods: {
    selectWord (wordId) {
      this.term = ''
      this.$emit('selectWord', wordId)
    }
  }
}
</script>
