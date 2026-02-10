<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Button from '@/components/hibou/Button.vue';
import sectionsRoutes from '@/routes/sections';
import { router } from '@inertiajs/vue3';

defineProps<{
    sections: any[];
}>();


const breadcrumbs = [
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

            <div class="relative overflow-x-auto">
                <p>No sections found.</p>
            </div>
        </div>
    </AppLayout>
</template>
