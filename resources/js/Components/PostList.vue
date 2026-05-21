<template>
  <div class="space-y-4">
    <div
      v-for="post in posts"
      :key="post.id"
      class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm"
    >
      <h3 class="text-lg font-bold text-gray-800">
        {{ post.title }}
      </h3>

      <p class="mt-2 text-gray-600">
        {{ post.body }}
      </p>

      <p class="mt-2 text-sm text-gray-500">
        Created by: {{ post.user ? post.user.name : 'No user loaded' }}
      </p>

      <p class="text-xs text-gray-400">
        {{ new Date(post.created_at).toLocaleString() }}
      </p>

      <div class="mt-4 border-t pt-3">
        <h4 class="text-sm font-bold text-gray-700">
          Comments
        </h4>

        <div
          v-for="comment in post.comments"
          :key="comment.id"
          class="mt-2 rounded bg-gray-100 p-2"
        >
          <p class="text-sm text-gray-700">
            {{ comment.body }}
          </p>

          <p class="text-xs text-gray-500">
            By: {{ comment.user ? comment.user.name : 'Unknown' }}
          </p>

          <button
            @click="$emit('delete-comment', comment.id)"
            class="mt-1 text-xs text-red-500 hover:underline"
          >
            Delete comment
          </button>
        </div>

        <p
          v-if="!post.comments || post.comments.length === 0"
          class="mt-2 text-sm text-gray-400"
        >
          No comments yet.
        </p>

        <div class="mt-3 flex gap-2">
          <input
            v-model="commentBodies[post.id]"
            placeholder="Write a comment..."
            class="w-full rounded border border-gray-300 p-2 text-sm text-gray-900"
          />

          <button
            @click="$emit('comment', post.id, commentBodies[post.id])"
            class="rounded bg-blue-500 px-3 py-1 text-white hover:bg-blue-600"
          >
            Comment
          </button>
        </div>
      </div>

      <div class="mt-4 flex gap-2">
        <button
          @click="$emit('edit', post)"
          class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600"
        >
          Edit
        </button>

        <button
          @click="$emit('delete', post.id)"
          class="rounded bg-red-500 px-3 py-1 text-white hover:bg-red-600"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const commentBodies = ref({})

defineProps({
  posts: Array,
})

defineEmits([
  'edit',
  'delete',
  'comment',
  'delete-comment',
])
</script>