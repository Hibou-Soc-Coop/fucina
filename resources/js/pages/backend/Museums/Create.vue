<script setup lang="ts">
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import PageLayout from '@/layouts/PageLayout.vue';
import museumsRoutes from '@/routes/museums';
import { type BreadcrumbItem } from '@/types';
import { type Language, MuseumInfo } from '@/types/flexhibition';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ElementTiptap } from 'element-tiptap';
import {
  Document,
  Text,
  Paragraph,
  Heading,
  Bold,
  Underline,
  Italic,
  Strike,
  BulletList,
  OrderedList,
} from 'element-tiptap';

const page = usePage();
const languages = page.props.languages as Language[];
const primaryLanguage = page.props.primaryLanguage as Language | null;
const primaryLanguageCode = primaryLanguage?.code || 'it';

const props = defineProps<{ museums: MuseumInfo[]; maxMuseum: string }>();

const extensions = [
  Document,
  Text,
  Paragraph,
  Heading.configure({ level: 5 }),
  Bold.configure({ bubble: true }), // render command-button in bubble menu.
  Underline.configure({ bubble: true, menubar: false }), // render command-button in bubble menu but not in menubar.
  Italic,
  Strike,
  BulletList,
  OrderedList,
];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Museums',
        href: '#',
    },
    {
        title: 'Create',
        href: museumsRoutes.create().url,
    },
];

const form = useForm({
    name: Object.fromEntries(Object.keys(languages).map((code) => [code, ''])),
    caption: Object.fromEntries(Object.keys(languages).map((code) => [code, ''])),
    description: Object.fromEntries(Object.keys(languages).map((code) => [code, ''])),
    processing: false,
});

function submit() {
    form.processing = true;
    form.post(museumsRoutes.store().url, {
        onFinish: () => {
            form.processing = false;
        },
    });
}
</script>

<template>
    <Head title="Museums" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <PageLayout title="Aggiungi Museo">
            <form @submit.prevent="submit">
                <Tabs default-value="it" :unmount-on-hide="false" class="grid w-full grid-cols-[15%_auto] gap-8" orientation="vertical">
                    <TabsList class="grid h-fit w-full grid-cols-1 gap-2">
                        <TabsTrigger v-for="[code, label] in Object.entries(languages)" :key="code" :value="code">
                            {{ label }}
                        </TabsTrigger>
                    </TabsList>
                    <TabsContent class="mt-1" v-for="[code, label] in Object.entries(languages)" :key="code" :value="code">
                        <Label class="mb-1 font-semibold">Nome</Label>
                        <Input class="mb-4" v-model="form.name[code]" />
                        <div v-if="form.errors[`name.${code}`]" class="mb-4 rounded bg-red-100 p-2 text-sm text-red-700">
                            {{ form.errors[`name.${code}`] }}
                        </div>
                        <Label class="mb-1 font-semibold">Descrizione {{ label }}</Label>
                        <div class="mb-4">
                            <ElementTiptap v-model="form.description[code]" :options="{ placeholder: 'Scrivi la descrizione qui...' }" />
                        </div>
                    </TabsContent>
                </Tabs>
            </form>
        </PageLayout>
    </AppLayout>
</template>
