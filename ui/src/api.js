export default class Api {
  constructor (store, router) {
    this.store = store
    this.router = router
    this._user = null
    this.getUserFromStorage()
    this.logInWithToken()
  }
  getUserFromStorage () {
    this.savedUser = JSON.parse(localStorage.getItem('user'))
  }
  request (method, url, data, noUser) {
    url = 'https://api.vocab.zealmayfield.com/' + url
    method = method.toUpperCase()
    let options = {
      method: method
    }
    let user = this.user || this.savedUser || null
    if (user !== null) {
      options.headers = {
        'Authorization': 'basic ' + btoa(user.Name + ':' + user.Token)
      }
    } else if (!noUser) {
      return
    }
    if (method === 'POST') {
      options.body = JSON.stringify(data)
    }
    return fetch(url, options).then(response => {
      return response.text()
    }).then(text => {
      try {
        return JSON.parse(text)
      } catch (e) {
        return { error: text }
      }
    }).then(data => {
      if (data.error) {
        if (data.error === 'Invalid authorization credentials.') {
          this.loginRedirect()
          return
        }
        console.log(method, url, data)
        throw new Error(`Server error: ${data.error}`)
      }
      return data
    })
  }
  set user (user) {
    this._user = user
    localStorage.setItem('user', JSON.stringify(user))
    this.store.commit('user', user)
    this.getWords()
  }
  loginRedirect () {
    localStorage.removeItem('user')
    if (this.router.currentRoute.path !== '/login') {
      this.router.push('/login')
    }
  }
  get user () {
    return this._user || this.savedUser || null
  }
  logInWithToken () {
    if (this.savedUser === null) {
      this.loginRedirect()
      return
    }
    this.request('GET', `user/${this.savedUser.Id}`).then(response => {
      if (response.error) {
        console.log(`Logging in with local token got: ${response.error}`)
        this.loginRedirect()
        return
      }
      this.user = response
    })
  }
  logInWithUsernamePassword (username, password) {
    this.request('POST', 'login', {
      name: username,
      password: password
    }, true).then(response => {
      if (response.error) {
        this.store.commit('loginError', response.error)
        return
      }
      this.user = response.user
      if (this.user.Admin) {
        this.router.push('/admin')
        return
      }
      this.router.push('/home')
    }).catch(error => {
      this.store.commit('loginError', error)
      this.loginRedirect()
    })
  }
  logOut () {
    localStorage.clear()
    this.store.commit('user', null)
    this.loginRedirect()
  }
  newUser (name) {
    name = name || 'New User ' + Date.now()
    this.request('POST', 'user', { name: name, password: name }).then(response => {
      this.router.push(`/admin/user/${response.user.Id}/edit`)
    })
  }
  async getUsers () {
    let { users } = await this.request('GET', `users`)
    return users
  }
  async getUser (id) {
    return this.request('GET', `user/${id}`)
  }
  async deleteUser (id) {
    let user = await this.request('GET', `user/${id}/delete`)
    if (user.Deleted) {
      this.router.push('/admin')
    }
  }
  updateUser (user) {
    user.Password = user.Name
    this.request('POST', `user/${user.Id}`, user)
  }
  newWord (spelling) {
    spelling = spelling || 'New Word ' + Date.now()
    this.request('POST', 'word', {
      spelling: spelling,
      illustration: '',
      pronunciation: ''
    }).then(response => {
      this.store.commit('word', response.word)
      this.router.push(`/admin/word/${response.word.Id}/edit`)
    })
  }
  async getWords () {
    if (this.store.getters.words.length === 0) {
      let { words } = await this.request('GET', 'words')
      this.store.commit('words', words)
    }
  }
  async getWord (id) {
    return this.request('GET', `word/${id}`)
  }
  async deleteWord (id) {
    let word = await this.request('GET', `word/${id}/delete`)
    if (word.Deleted) {
      this.store.commit('deleteWord', word.Id)
      this.router.push('/admin')
    }
  }
  updateWord (word) {
    this.request('POST', `word/${word.Id}`, word)
    this.store.commit('word', word)
  }
  newStory (title) {
    title = title || 'New Story ' + Date.now()
    this.request('POST', 'story', {
      title: title
    }).then(response => {
      this.router.push(`/admin/story/${response.story.Id}/edit`)
    })
  }
  async getStories () {
    let { stories } = await this.request('GET', `stories`)
    return stories
  }
  async getStory (id) {
    return this.request('GET', `story/${id}`)
  }
  async deleteStory (id) {
    let story = await this.request('GET', `story/${id}/delete`)
    if (story.Deleted) {
      this.router.push('/admin')
    }
  }
  updateStory (story) {
    this.request('POST', `story/${story.Id}`, story)
  }
  async getWordLists (storyId) {
    let { wordLists } = await this.request('GET', `story/${storyId}/word_lists`)
    return wordLists
  }
  newWordList (storyId, name) {
    name = name || 'New Word List ' + Date.now()
    this.request('POST', `story/${storyId}/word_list`, {
      name: name
    }).then(response => {
      this.router.push(`/admin/word_list/${response.wordList.Id}/edit`)
    })
  }
  getWordList (id) {
    return this.request('GET', `word_list/${id}`)
  }
  updateWordList (wordList) {
    this.request('POST', `word_list/${wordList.Id}`, wordList)
  }
  async deleteWordList (id) {
    let wordList = await this.request('GET', `word_list/${id}/delete`)
    if (wordList.Deleted) {
      this.router.push('/admin')
    }
  }
  async getWordListWords (wordListId) {
    let { words } = await this.request('GET', `word_list/${wordListId}/words`)
    return words
  }
  async getWordListWordsIncludeStoryPageId (wordListId) {
    let { words } = await this.request('GET', `word_list/${wordListId}/words/include_story_page_id`)
    return words
  }
  addWordListWord (wordListId, wordId) {
    return this.request('GET', `word_list/${wordListId}/add_word/${wordId}`)
  }
  removeWordListWord (wordListId, wordId) {
    return this.request('GET', `word_list/${wordListId}/remove_word/${wordId}`)
  }
  async getStoryPages (storyId) {
    let { storyPages } = await this.request('GET', `story/${storyId}/story_pages`)
    return storyPages
  }
  newStoryPage (storyId, text) {
    text = text || 'New Story Page ' + Date.now()
    this.request('POST', `story/${storyId}/story_page`, {
      text: text
    }).then(response => {
      this.router.push(`/admin/story_page/${response.storyPage.Id}/edit`)
    })
  }
  getStoryPage (id) {
    return this.request('GET', `story_page/${id}`)
  }
  updateStoryPage (storyPage) {
    this.request('POST', `story_page/${storyPage.Id}`, storyPage)
  }
  async deleteStoryPage (id) {
    let storyPage = await this.request('GET', `story_page/${id}/delete`)
    if (storyPage.Deleted) {
      this.router.push('/admin')
    }
  }
  async getStoryPageWords (storyPageId) {
    let { words } = await this.request('GET', `story_page/${storyPageId}/words`)
    return words
  }
  addStoryPageWord (storyPageId, wordId) {
    return this.request('GET', `story_page/${storyPageId}/add_word/${wordId}`)
  }
  removeStoryPageWord (storyPageId, wordId) {
    return this.request('GET', `story_page/${storyPageId}/remove_word/${wordId}`)
  }
  async getUserTroubleWords (userId) {
    let { userWords } = await this.request('GET', `user/${userId}/trouble_words`)
    return userWords
  }
  recordWordScore (wordId, score) {
    this.request('POST', `user/${this.user.Id}/word/${wordId}/score`, { 'score': score })
  }
}
