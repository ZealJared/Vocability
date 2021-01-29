<template>
  <ul v-if="userWords.length > 0" class="list-group">
    <li v-for="(userWord, index) in userWords" :key="index" class="list-group-item d-flex justify-content-between align-items-center">
      {{ $store.getters.wordById(userWord.WordId) ? $store.getters.wordById(userWord.WordId).Spelling : '' }}
      <div>
        {{ userWord.Score.toFixed(2) }}%
      </div>
    </li>
  </ul>
</template>

<script>
export default {
  data () {
    return {
      userWords: []
    }
  },
  async mounted () {
    this.userWords = await this.$api.getUserTroubleWords(this.$api.user.Id)
  }
}
</script>
