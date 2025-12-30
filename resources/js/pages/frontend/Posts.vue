<script setup lang="ts">
import HMenu from '@/components/HMenu.vue';
import LanguageMenu from '@/components/LanguageMenu.vue';
import { Post } from '@/types/flexhibition';
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const page = usePage();
const props = defineProps<{
    posts: Post[];
}>();
const { t, locale } = useI18n();
const currentUrl = ref('');

function getCurrentUrlWithoutLanguage() {
    const splitUrl = page.url.split('/');
    if (splitUrl[splitUrl.length - 1] === locale.value) {
        splitUrl.pop();
    }
    currentUrl.value = splitUrl.join('/');
    return currentUrl.value;
}
</script>
<template>
    <div class="grid h-dvh grid-rows-[15%_85%]">
        <div class="flex flex-col items-center justify-center bg-white">
            <LanguageMenu />
            <h1 class="text-3xl font-bold">{{ t('collection.title') }}</h1>
        </div>
        <div class="h-full overflow-y-scroll bg-[#eccdc3] p-8">
            <div v-for="post in posts" :key="post.id" class="mb-4 min-h-50 min-w-full cursor-pointer rounded-[40%] bg-white">
                <img
                    class="h-64 w-full rounded-[40%] object-cover"
                    :key="post.id"
                    :src="post.images?.[0][locale] || post.images?.[0]['it']"
                    alt=""
                    @click="router.visit(`${getCurrentUrlWithoutLanguage()}/posts/${post.id}/${locale}`)"
                />
            </div>

            <div class="fixed bottom-4 left-0 flex w-full justify-center">
                <HMenu />
            </div>
        </div>
    </div>
</template>
