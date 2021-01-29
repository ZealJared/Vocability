<template>
  <div v-if="story">
    <div class="card">
      <div class="card-body">
        <h1 class="card-title">Story</h1>
        <div id="form">
          <div class="form-group">
            <label for="title">Title</label>
            <input class="form-control" type="text" id="title" v-model="story.Title">
          </div>
          <div class="d-flex justify-content-between">
            <a href="#" @click.prevent="updateStory" class="btn btn-primary">Save</a>
            <a href="#" @click.prevent="deleteStory" class="btn btn-danger">Delete</a>
          </div>
        </div>
      </div>
    </div>
    <WordLists :storyId="story.Id"></WordLists>
    <StoryPages :storyId="story.Id"></StoryPages>
  </div>
</template>

<script>
import WordLists from '@/components/story-word-lists'
import StoryPages from '@/components/story-pages'

export default {
  components: {
    WordLists: WordLists,
    StoryPages: StoryPages
  },
  data () {
    return {
      story: null
    }
  },
  methods: {
    updateStory () {
      this.$api.updateStory(this.story)
    },
    deleteStory () {
      this.$api.deleteStory(this.story.Id)
    }
  },
  async mounted () {
    let storyId = this.$route.params.id
    this.story = await this.$api.getStory(storyId)
  }
}
</script>
