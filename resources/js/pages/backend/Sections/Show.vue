<script setup lang="ts">
import Button from '@/components/hibou/Button.vue';
import Label from '@/components/ui/label/Label.vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import PageLayout from '@/layouts/PageLayout.vue';
import sectionsRoutes from '@/routes/sections/index';
import { type BreadcrumbItem } from '@/types';
import { SectionData, type Language } from '@/types/flexhibition';
import { Head, router, usePage } from '@inertiajs/vue3';

const props = defineProps<{ section: SectionData }>();
console.log("section Props:", props.section);
const page = usePage();
const languages = page.props.languages as Language[];
const primaryLanguage = page.props.primaryLanguage as Language;
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Sezione', href: sectionsRoutes.index().url },
    { title: 'Dettaglio', href: '#' },
];

const deletesection = () => {
    if (confirm('Sei sicuro di voler eliminare questa Sezione?')) {
        router.delete(sectionsRoutes.destroy.url(props.section.id));
    }
};
</script>

<template>

    <Head :title="props.section.title[primaryLanguage.code] + ' - Dettaglio'" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <PageLayout title="Dettaglio Sezione">
            <template #button>
                <Button @click="router.visit(sectionsRoutes.edit.url(props.section.id))" color-scheme="edit"
                    class="mr-2">
                    Modifica Sezione
                </Button>
                <Button @click="deletesection" color-scheme="delete">
                    Elimina Sezione
                </Button>
            </template>
            <div class="grid grid-cols-[1fr_4fr] grid-rows-[auto_auto] gap-4">
                <div class="col-start-1 col-end-2 rounded-lg border p-4 shadow">
                    <Label class="block text-lg font-semibold"> Video </Label>
                    <video v-if="props.section.video[0]?.url" :src="`${props.section.video[0].url}`" controls
                        class="mt-2 w-full" />
                    <div v-else class="mt-2 w-full rounded-md border border-gray-300 bg-gray-100">
                        <p class="p-4 text-sm text-gray-500">{{ props.section.video }}</p>
                    </div>
                    <Label class="block text-lg font-semibold mb-4"> Immagine </Label>
                    <img v-if="props.section.image[0]?.url" :src="`${props.section.image[0].url}`" alt="Immagine Sezione"
                        class="mt-2 w-full" />
                    <div v-else class="mt-2 w-full rounded-md border border-gray-300 bg-gray-100">
                        <p class="p-4 text-sm text-gray-500">{{ props.section.image }}</p>
                    </div>
                </div>
                <div class="col-start-2 col-end-3 row-start-1 row-end-3 rounded-lg border p-4 shadow">
                    <h2 class="mb-4 text-lg font-semibold">Informazioni</h2>
                    <Tabs default-value="it" :unmount-on-hide="false" class="grid w-full grid-cols-[15%_auto] gap-8"
                        orientation="vertical">
                        <TabsList class="grid h-fit w-full grid-cols-1 gap-2">
                            <template v-for="language in languages" :key="language.code">
                                <TabsTrigger
                                    v-if="props.section.title[language.code] || props.section.description[language.code]"
                                    :value="language.code">
                                    {{ language.name }}
                                </TabsTrigger>
                            </template>
                        </TabsList>
                        <TabsContent v-for="language in languages" :key="language.code" :value="language.code">
                            <Label class="mb-4 text-base font-semibold"> Nome - {{ language.name }} </Label>
                            <p
                                class="mb-6 flex min-h-9 w-full items-center rounded-md border border-input px-3 py-1 text-sm shadow-xs shadow-input">
                                {{ props.section.title[language.code] }}
                            </p>
                            <Label class="mb-4 text-base font-semibold"> Descrizione - {{ language.name }}
                            </Label>
                            <div class="mb-2 flex min-h-15 w-full items-center rounded-md border border-input px-3 py-1 text-sm shadow-xs shadow-input"
                                v-html="props.section.description[language.code] || '-'">
                            </div>
                            <Label class="mb-4 text-base font-semibold"> Audio - {{ language.name }}</Label>
                            <div class="mb-2 gap-5 flex items-center rounded-md border border-input px-3 py-1 text-sm shadow-xs shadow-input">
                                <div class="w-1/2 items-center rounded-md border border-input px-3 py-1 text-sm shadow-xs shadow-input">
                                    <audio v-if="props.section.audio[language.code]"
                                        :src="props.section.audio[language.code].url"
                                        controls class="w-full mt-4" />
                                    </div>
                                <div class="w-1/2 items-center rounded-md border border-input px-3 py-1 text-sm shadow-xs shadow-input">
                                     <img v-if="props.section.qrcode?.find(q => q.custom_properties?.lang === language.code)"
                                    :src="props.section.qrcode.find(q => q.custom_properties?.lang === language.code)?.url"
                                    alt="QR Code" class="w-[50%] h-auto">
                                </div>
                            </div>
                        </TabsContent>
                    </Tabs>
                </div>
            </div>
        </PageLayout>
    </AppLayout>
</template>
