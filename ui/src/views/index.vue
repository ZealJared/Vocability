<template>
  <div>
    <h1>Home</h1>
    <h2>Stories</h2>
    <ListStories></ListStories>
    <h2>Trouble Words</h2>
    <TroubleWords></TroubleWords>
  </div>
</template>

<script>
import TroubleWords from '../components/trouble-words.vue'
import ListStories from '../components/list-stories.vue'

export default {
  components: {
    TroubleWords: TroubleWords,
    ListStories: ListStories
  }
}
</script>
