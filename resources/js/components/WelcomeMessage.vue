<template>
  <div>
    <h2>Hello {{ name }}!</h2>
    <p>Welcome to Vue props 🚀</p>
  </div>
</template>

<script setup>
defineProps({
  name: String
})
</script>