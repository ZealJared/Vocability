import Vue from 'vue'
import Router from 'vue-router'
import LoginView from './views/login.vue'
import HomeView from './views/index.vue'
import AdminView from './views/admin/index.vue'
import UserListView from './views/admin/users/index.vue'
import UserEditView from './views/admin/users/edit.vue'
import WordListView from './views/admin/words/index.vue'
import WordEditView from './views/admin/words/edit.vue'
import StoryListView from './views/admin/stories/index.vue'
import StoryEditView from './views/admin/stories/edit.vue'
import WordListEditView from './views/admin/word_lists/edit.vue'
import StoryPageEditView from './views/admin/story_pages/edit.vue'
import StoryWordListsView from './views/story-word-lists.vue'
import WordListReviewView from './views/word-list-review.vue'

Vue.use(Router)

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/home',
      name: 'home',
      component: HomeView
    },
    {
      path: '/admin',
      name: 'admin',
      component: AdminView
    },
    {
      path: '/admin/users',
      name: 'user-list',
      component: UserListView
    },
    {
      path: '/admin/user/:id/edit',
      name: 'user-edit',
      component: UserEditView
    },
    {
      path: '/admin/words',
      name: 'word-list',
      component: WordListView
    },
    {
      path: '/admin/word/:id/edit',
      name: 'word-edit',
      component: WordEditView
    },
    {
      path: '/admin/stories',
      name: 'story-list',
      component: StoryListView
    },
    {
      path: '/admin/story/:id/edit',
      name: 'story-edit',
      component: StoryEditView
    },
    {
      path: '/admin/word_list/:id/edit',
      name: 'word-list-edit',
      component: WordListEditView
    },
    {
      path: '/admin/story_page/:id/edit',
      name: 'story-page-edit',
      component: StoryPageEditView
    },
    {
      path: '/story/:id/word_lists',
      name: 'story-word-lists',
      component: StoryWordListsView
    },
    {
      path: '/word_list/:id/review',
      name: 'word-list-review',
      component: WordListReviewView
    },
    {
      path: '/word_list/:id/test',
      name: 'word-list-test',
      component: WordListReviewView
    }
  ]
})
