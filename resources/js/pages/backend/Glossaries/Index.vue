<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    glossaries: any[];
}>();

const breadcrumbs = [
    { title: 'Glossaries', href: '/backend/glossaries' },
];
</script>

<template>
    <Head title="Glossary" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Glossary</h2>
                <Link href="/backend/glossaries/create" class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
                    Add term
                </Link>
            </div>

            <div class="relative overflow-x-auto">
                <p>No terms found.</p>
            </div>
        </div>
    </AppLayout>
</template>
