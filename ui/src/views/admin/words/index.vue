<template>
  <div>
    <h1>Words</h1>
    <ul class="list-group">
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          New Word
        </div>
        <a class="btn btn-success" href="#" @click.prevent="newWord()">Add</a>
      </li>
      <li v-for="(word, index) in words" :key="index" class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          {{ word.Spelling }}
        </div>
        <router-link :to="`/admin/word/${word.Id}/edit`" class="btn btn-primary">Edit</router-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  methods: {
    newWord () {
      this.$api.newWord()
    }
  },
  computed: {
    words () {
      this.$api.getWords()
      return this.$store.getters.words
    }
  }
}
</script>
