<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import Button from '@/components/hibou/Button.vue';
import sectionsRoutes from '@/routes/sections/index';
import { type Language, SectionData } from '@/types/flexhibition';
import { type BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/vue3';
import Card from '@/components/hibou/Card.vue';

const page = usePage();
const languages = page.props.languages as Language[];
const primaryLanguage = page.props.primaryLanguage as Language | null;

const primaryLanguageCode = primaryLanguage?.code || 'it';
const props = defineProps<{ sections: SectionData[] }>();
console.log(props.sections);


const breadcrumbs: BreadcrumbItem[] =  [
    { title: 'Sections', href: sectionsRoutes.index().url },
    {
        title: 'Create',
        href: sectionsRoutes.create().url,
    }
];
</script>

<template>
    <Head title="Sections" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Sections</h2>
                <Button @click="router.visit(sectionsRoutes.create().url)" colorScheme="create" class="ml-6 h-8" :href="sectionsRoutes.create().url">
                    Create Section
                </Button>
            </div>
            <div class="gap-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                <Card
                    v-for="section in sections"
                    :key="section.id"
                    :title="section.title[primaryLanguageCode]"
                    :excerpt="section.description[primaryLanguageCode]"
                    :href="sectionsRoutes.edit(section.id).url"
                    :thumbnail="section.image?.[0]?.url ? `${section.image[0].url}` : '/storage/sample-data/images/placeholder.jpg'"></Card>
                <div v-if="props.sections.length === 0" class="col-span-full py-8 text-muted-foreground text-center">
                    No sections found.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
