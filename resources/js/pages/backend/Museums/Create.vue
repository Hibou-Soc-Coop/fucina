<script setup lang="ts">
import Button from '@/components/hibou/Button.vue';
import SingleMediaUploader from '@/components/hibou/SingleMediaUpload.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import PageLayout from '@/layouts/PageLayout.vue';
import museumRoutes from '@/routes/museums';
import { type BreadcrumbItem } from '@/types';
import { MuseumUploadData, type Language } from '@/types/flexhibition';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();
const languages = page.props.languages as Language[];
const primaryLanguage = page.props.primaryLanguage as Language;
const submitting = ref(false);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Museo', href: museumRoutes.index().url },
    { title: 'Dettaglio', href: '#' },
];

const form = useForm<MuseumUploadData>({
    id: '',
    name: {},
    description: {},
    logo: {
        id: null,
        url: {},
        title: {},
    },
    audio: {
        id: null,
        url: {},
        title: {},
    },
    images: {},
});

function submit() {
    console.log('Submitting form: ', form);
    console.log('Route: ', museumRoutes.store().url);

    submitting.value = true;
    form.post(museumRoutes.store().url, {
        onSuccess: () => {
            submitting.value = false;
            console.log('Museo creato con successo');
        },
    });
}
</script>

<template>
    <Head title="Crea nuovo museo" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <PageLayout title="Nuovo Museo">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-[1fr_4fr] grid-rows-[auto_auto] gap-4">
                    <div class="rounded-lg border p-4 shadow">
                        <Label class="mb-4 text-lg font-semibold"> Logo Museo </Label>
                        <SingleMediaUploader v-model="form.logo" accept="image/*" />
                    </div>
                    <div class="col-start-1 col-end-2 rounded-lg border p-4 shadow">
                        <Label class="block text-lg font-semibold"> Audio Museo </Label>
                        <SingleMediaUploader v-model="form.audio" accept="audio/*" />
                    </div>
                    <div class="col-start-2 col-end-3 row-start-1 row-end-3 rounded-lg border p-4 shadow">
                        <h2 class="mb-4 text-lg font-semibold">Informazioni Museo</h2>
                        <Tabs default-value="it" :unmount-on-hide="false" class="grid w-full grid-cols-[15%_auto] gap-8" orientation="vertical">
                            <TabsList class="grid h-fit w-full grid-cols-1 gap-2">
                                <template v-for="language in languages" :key="language.code">
                                    <TabsTrigger :value="language.code">
                                        {{ language.name }}
                                    </TabsTrigger>
                                </template>
                            </TabsList>
                            <TabsContent v-for="language in languages" :key="language.code" :value="language.code">
                                <Label class="mb-4 text-base font-semibold"> Nome Museo - {{ language.name }} </Label>
                                <Input class="mb-6" v-model="form.name[language.code]" />
                                <Label class="mb-4 text-base font-semibold"> Descrizione Museo - {{ language.name }} </Label>
                                <Input class="mb-6" v-model="form.description[language.code]" />
                            </TabsContent>
                        </Tabs>
                    </div>
                    <div class="mb-4 col-span-2 rounded-lg border p-4 shadow">
                        <Label class="mb-4 text-lg font-semibold"> Immagini del Museo </Label>
                        <!-- <div class="grid grid-cols-4 gap-4">
                            <div
                                v-for="(image, index) in Object.values(props.museum.images)"
                                :key="index"
                                class="aspect-square w-full overflow-hidden rounded-md border border-gray-300"
                            >
                                <img :src="image.url[primaryLanguage.code]" :alt="image.title[primaryLanguage.code]" class="h-full w-full object-cover" />
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="flex justify-end">
                    <Button type="submit" :disabled="submitting">Salva</Button>
                </div>
            </form>
        </PageLayout>
    </AppLayout>
</template>
