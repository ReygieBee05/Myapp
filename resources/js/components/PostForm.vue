<template>

  <form
    @submit.prevent="submitForm"
    class="space-y-4"
  >

    <div>
      <input
        v-model="localTitle"
        placeholder="Post title"
        class="w-full rounded border border-gray-300 p-3"
      />
    </div>

    <div>
      <textarea
        v-model="localBody"
        placeholder="Post body"
        class="w-full rounded border border-gray-300 p-3"
      ></textarea>
    </div>

    <div class="flex gap-2">

      <button
        type="submit"
       class="bg-red-600 text-white text-2xl p-6 rounded-full"
      >
        {{ editingId ? 'Update Post' : 'Add Post' }}
      </button>

      <button
        v-if="editingId"
        type="button"
        @click="$emit('cancel')"
        class="rounded bg-gray-500 px-4 py-2 text-white hover:bg-gray-600"
      >
        Cancel
      </button>

    </div>

  </form>

</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  title: String,
  body: String,
  editingId: {
    type: Number,
    default: null,
  },
})

const emit = defineEmits([
  'submit',
  'cancel',
])

const localTitle = ref(props.title || '')
const localBody = ref(props.body || '')

watch(() => props.title, (value) => {
  localTitle.value = value
})

watch(() => props.body, (value) => {
  localBody.value = value
})

function submitForm() {

  emit('submit', {
    title: localTitle.value,
    body: localBody.value,
  })
}
</script>