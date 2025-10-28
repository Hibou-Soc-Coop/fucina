<script setup lang="ts">

const props = defineProps<{ title?: string }>()
</script>

<template>
    <div v-if="props.title" class="m-4 mb-2 flex gap-6">
        <h1 class="font-bold text-2xl">{{ props.title }}</h1>
        <slot name="button" />
    </div>
    <div class="m-4 mt-2">
        <slot />
    </div>
</template>
