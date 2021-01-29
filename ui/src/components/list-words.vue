<template>
  <ul v-if="words.length > 0" class="list-group">
    <li v-for="(word, index) in words" :key="index" class="list-group-item d-flex justify-content-between align-items-center">
      {{ word.Spelling }}
      <div>
        <button class="btn btn-danger" @click="$emit('removeWord', word.Id)">Remove</button>
      </div>
    </li>
  </ul>
</template>

<script>
export default {
  props: [
    'words'
  ]
}
</script>
