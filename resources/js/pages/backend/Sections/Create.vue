<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import Button from '@/components/hibou/Button.vue';
import { Label } from '@/components/ui/label';
import sectionsRoutes from '@/routes/sections';
import { type BreadcrumbItem } from '@/types';
import { type Language, MediaData } from '@/types/flexhibition';
import { Input } from '@/components/ui/input';
import TipTap from '@/components/hibou/TipTap.vue';
import SingleMediaUpload from '@/components/hibou/SingleMediaUpload.vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { ref } from 'vue';


const page = usePage();
const languages = page.props.languages as Language[];
const primaryLanguage = page.props.primaryLanguage as Language | null;
const primaryLanguageCode = primaryLanguage?.code || 'it';
const currentLang = ref<string>(primaryLanguageCode);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Sections', href: '/admin/sections' },
    { title: 'Create', href: sectionsRoutes.create().url },
];

const emptyByLanguage = Object.fromEntries(languages.map((l) => [l.code, '']));

const form = useForm({
    title: { ...emptyByLanguage },
    subtitle: { ...emptyByLanguage },
    description: { ...emptyByLanguage },
    video: null as MediaData | null,
    audio: null as MediaData | null,
    image: null as MediaData | null,
});


function submit() {
    form.processing = true;
    form.post(sectionsRoutes.store().url, {
        onFinish: () => {
            form.processing = false;
        },
    });
}
</script>

<template>

    <Head title="Create Section" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h2 class="text-xl font-semibold mb-4">Nuova sezione</h2>

            <form @submit.prevent="submit">
                <div class="grid grid-cols-[1fr_4fr] grid-rows-[auto_auto] gap-4">
                    <div class="col-start-1 col-end-2 rounded-lg border p-4 shadow">
                        <Label class="block text-lg font-semibold"> Carica video </Label>
                        <SingleMediaUpload v-model="form.video" :is-readonly="false" label="Video" :accept="'video/*'"
                            :max-file-size="20 * 1024 * 1024" />
                    </div>
                    <div class="col-start-1 col-end-2 rounded-lg border p-4 shadow">
                        <Label class="block text-lg font-semibold"> Carica Immagine </Label>
                        <SingleMediaUpload v-model="form.image" :is-readonly="false" label="Immagine"
                            :accept="'image/*'" :max-file-size="4 * 1024 * 1024" />
                    </div>
                    <div class="col-start-2 col-end-3 row-start-1 row-end-3 rounded-lg border p-4 shadow">
                        <Tabs :default-value="languages[0]?.code" class="w-full">
                            <TabsList>
                                <TabsTrigger v-for="lang in languages" :key="lang.id" :value="lang.code">
                                    {{ lang.name }}
                                </TabsTrigger>
                            </TabsList>
                            <TabsContent v-for="lang in languages" :key="lang.id" :value="lang.code"
                                class="space-y-4 border p-4 rounded-md mt-2">
                                <div class="space-y-4">
                                    <Label :for="`title-${lang.code}`">Titolo ({{ lang.code }})</Label>
                                    <Input :id="`title-${lang.code}`" v-model="form.title[lang.code]" required />
                                </div>
                                <div class="space-y-4">
                                    <Label :for="`description-${lang.code}`">Descrizione ({{ lang.code }})</Label>
                                    <TipTap :id="`description-${lang.code}`" v-model="form.description[lang.code]">
                                    </TipTap>
                                </div>
                                <div>
                                    <SingleMediaUpload v-model="form.audio" label="Audio" accept="audio/*" />
                                </div>
                            </TabsContent>
                        </Tabs>
                    </div>
                </div>
                    <div class="mt-4 text-right">
                        <Button type="submit" :disabled="form.processing" color-scheme="create">Crea Museo</Button>
                    </div>
            </form>
        </div>
    </AppLayout>
</template>
