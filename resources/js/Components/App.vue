<template>
  <div
    :class="darkMode ? 'bg-gray-900 text-white' : 'bg-white text-gray-900'"
    class="min-h-screen p-6"
  >

    <h1>Posts from PostgreSQL</h1>

    <button
  @click="darkMode = !darkMode"
  class="mb-4 rounded bg-gray-800 px-4 py-2 text-white"
>
  {{ darkMode ? 'Light Mode' : 'Dark Mode' }}
</button>

    <p v-if="error" style="color:red; font-weight:bold;">
      {{ error }}
    </p>

    <p
  v-if="toast"
  class="mb-4 rounded bg-green-100 p-3 text-green-700"
>
  {{ toast }}
</p>

    <PostForm
      :title="title"
      :body="body"
      :editing-id="editingId"
      @submit="savePost"
      @cancel="cancelEdit"
    />

    <hr>

    <p v-if="loading">
      Loading posts...
    </p>

    <PostList
      :posts="posts"
      @edit="editPost"
      @delete="deletePost"
      @comment="addComment"
      @delete-comment="deleteComment"
    />
    
    <input
  v-model="search"
  @input="loadPosts"
  placeholder="Search posts..."
  class="mb-4 w-full rounded border border-gray-300 p-3"
/>
<div class="mt-4 flex gap-2">

  <button
    v-if="prevPage"
    @click="changePage(prevPage)"
    class="rounded bg-gray-500 px-4 py-2 text-white"
  >
    Previous
  </button>

  <button
    v-if="nextPage"
    @click="changePage(nextPage)"
    class="rounded bg-blue-500 px-4 py-2 text-white"
  >
    Next
  </button>

</div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'

import PostForm from './PostForm.vue'
import PostList from './PostList.vue'

const posts = ref([])
const title = ref('')
const body = ref('')
const editingId = ref(null)

const error = ref('')
const loading = ref(false)
const search = ref('')

const nextPage = ref(null)
const prevPage = ref(null)
const toast = ref('')
const darkMode = ref(false)

const savedMode = localStorage.getItem('darkMode')

if (savedMode === 'true') {
  darkMode.value = true
}

function showToast(message) {
  toast.value = message

  setTimeout(() => {
    toast.value = ''
  }, 3000)
}

watch(darkMode, (value) => {
  localStorage.setItem('darkMode', value)
})

async function loadPosts() {
  loading.value = true

  try {
    const response = await fetch(
      `/api/posts?search=${search.value}`
    )

    const data = await response.json()

   posts.value = data.data || []

   nextPage.value = data.next_page_url
   prevPage.value = data.prev_page_url
  } catch (e) {
    error.value = 'Failed to load posts.'
  }

  loading.value = false
}

async function savePost(formData) {
  error.value = ''

  const isEditing = editingId.value

  if (!formData.title.trim()) {
    error.value = 'Title is required.'
    return
  }

  try {
    if (editingId.value) {
      await fetch(`/api/posts/${editingId.value}`, {
        method: 'PUT',
        headers: {
  'Content-Type': 'application/json',
  'Accept': 'application/json',
  'X-CSRF-TOKEN': document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute('content'),
},
        body: JSON.stringify(formData),
      })

      editingId.value = null
    } else {
     const response = await fetch('/api/posts', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute('content'),
  },
  body: JSON.stringify(formData),
})

      if (!response.ok) {
        const data = await response.json()

        if (data.errors && data.errors.title) {
          error.value = data.errors.title[0]
        } else {
          error.value = data.message || 'Something went wrong.'
        }

        return
      }
    }

    title.value = ''
    body.value = ''

    showToast(
  isEditing
    ? 'Post updated successfully!'
    : 'Post created successfully!'
)


    await loadPosts()
 } catch (e) {
  console.log(e)
  error.value = e.message
}
}

function editPost(post) {
  editingId.value = post.id
  title.value = post.title
  body.value = post.body
}

function cancelEdit() {
  editingId.value = null
  title.value = ''
  body.value = ''
}

async function deletePost(id) {

  const confirmed = confirm(
    'Are you sure you want to delete this post?'
  )

  if (!confirmed) {
    return
  }

  await fetch(`/api/posts/${id}`, {
    method: 'DELETE',
    headers: {
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content'),
    },
  })

 showToast('Post deleted successfully!')

await loadPosts()
}

async function changePage(url) {

  loading.value = true

  const response = await fetch(url)

  const data = await response.json()

  posts.value = data.data || []

  nextPage.value = data.next_page_url
  prevPage.value = data.prev_page_url

  loading.value = false
}
async function addComment(postId, body) {
  console.log('Post ID:', postId)
  console.log('Comment body:', body)

  error.value = ''

  if (!body || !body.trim()) {
    error.value = 'Comment is required.'
    return
  }

  const response = await fetch(`/api/posts/${postId}/comments`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content'),
    },
    body: JSON.stringify({
      body: body,
    }),
  })

  console.log('Response status:', response.status)

  if (!response.ok) {
    const data = await response.json()
    console.log(data)
    error.value = data.message || 'Failed to add comment.'
    return
  }

  showToast('Comment added successfully!')

  await loadPosts()
}
async function deleteComment(commentId) {
  await fetch(`/api/comments/${commentId}`, {
    method: 'DELETE',
    headers: {
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content'),
    },
  })

  showToast('Comment deleted successfully!')

  await loadPosts()
}
onMounted(loadPosts)
</script>