<template>
    <div>
        <h1>Hello dari Inertia + Vue 3!</h1>
        <p>{{ message }}</p>
    </div>
</template>

<script setup>
defineProps({
    message: String
})
</script>