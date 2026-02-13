<script setup lang="ts">
import Button from '@/components/hibou/Button.vue';
import SingleMediaUpload from '@/components/hibou/SingleMediaUpload.vue';
import TipTap from '@/components/hibou/TipTap.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import sectionsRoutes from '@/routes/sections/index';
import { type BreadcrumbItem } from '@/types';
import { MediaData, SectionData, type Language } from '@/types/flexhibition';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{ section: SectionData }>();
console.log(props.section);

const page = usePage();
const languages = page.props.languages as Language[];
const primaryLanguage = page.props.primaryLanguage as Language | null;

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Sections', href: '/admin/sections' },
    { title: 'Edit', href: '' },
];

const emptyByLanguage = Object.fromEntries(languages.map((l) => [l.code, '']));

// Helper to transform array of media to MediaData structure
const transformMedia = (mediaArray: any[]): MediaData => {
    const urls: Record<string, string> = {};
    const titles: Record<string, string> = {}; // Assuming title is stored somewhere or just empty

    mediaArray.forEach(m => {
        // If media has language property, use it, otherwise use 'default' or primary lang??
        // Based on Show.vue, custom_properties.language holds the code.
        const lang = m.custom_properties?.language || 'it'; // Default fallback?
        urls[lang] = m.url;
    });

    return {
        id: null,
        url: urls,
        title: titles,
        file: {},
    };
};

const form = useForm({
    _method: 'PUT',
    title: props.section.title || { ...emptyByLanguage },
    subtitle: props.section.subtitle || { ...emptyByLanguage },
    description: props.section.description || { ...emptyByLanguage },
    video: props.section.video ? transformMedia(props.section.video) : { id: null, url: {}, title: {}, file: {} } as MediaData,
    audio: props.section.audio ? transformMedia(props.section.audio) : { id: null, url: {}, title: {}, file: {} } as MediaData,
    image: props.section.image ? transformMedia(props.section.image) : { id: null, url: {}, title: {}, file: {} } as MediaData,
});


function submit() {
    form.processing = true;
    form.post(sectionsRoutes.update.url(props.section.id), {
        onFinish: () => {
            form.processing = false;
        },
    });
}
</script>

<template>
    <Head title="Modifica Sezione" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h2 class="text-xl font-semibold mb-4">Modifica sezione</h2>

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
                                    <Label :for="`subtitle-${lang.code}`">Sottotitolo ({{ lang.code }})</Label>
                                    <Input :id="`subtitle-${lang.code}`" v-model="form.subtitle[lang.code]" required />
                                </div>
                                <div class="space-y-4">
                                    <Label :for="`description-${lang.code}`">Descrizione ({{ lang.code }})</Label>
                                    <TipTap :id="`description-${lang.code}`" v-model="form.description[lang.code]">
                                    </TipTap>
                                </div>
                                <div class="w-1/2">
                                    <SingleMediaUpload v-model="form.audio" label="Audio" accept="audio/*" :current-lang="lang.code" :multi-language="true" />
                                </div>
                            </TabsContent>
                        </Tabs>
                    </div>
                </div>
                <div class="mt-4 text-right">
                    <Button type="submit" :disabled="form.processing" color-scheme="edit">Salva modifiche</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
