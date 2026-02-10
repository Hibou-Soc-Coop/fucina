<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea'; // Assuming it exists, otherwise Input
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

// Create a Textarea component or use standard textarea if not available
// I'll assume standard textarea for safety or check if ui/textarea exists.
// Checking ui folder previously: no textarea folder visible in list_dir output (input, label, etc were there).
// So I will use standard textarea with tailwind classes.

const props = defineProps<{
    languages: any[]; // Adjust type if needed
}>();

const breadcrumbs = [
    { title: 'Sections', href: '/backend/sections' },
    { title: 'Create', href: '#' },
];

const form = useForm({
    title: {} as Record<string, string>,
    description: {} as Record<string, string>,
    video: null as File | null,
    audio: null as File | null,
    images: [] as File[],
});

// Initialize translation objects with empty strings for each language
props.languages.forEach(lang => {
    form.title[lang.code] = '';
    form.description[lang.code] = '';
});

function submit() {
    form.post('/backend/sections', {
        forceFormData: true,
    });
}
</script>

<template>
    <Head title="Create Section" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h2 class="text-xl font-semibold mb-4">Nuova sezione</h2>

            <form @submit.prevent="submit" class="space-y-6 max-w-4xl">
                <!-- Translations -->
                <Tabs :default-value="languages[0]?.code" class="w-full">
                    <TabsList>
                        <TabsTrigger v-for="lang in languages" :key="lang.id" :value="lang.code">
                            {{ lang.name }}
                        </TabsTrigger>
                    </TabsList>
                    <TabsContent v-for="lang in languages" :key="lang.id" :value="lang.code" class="space-y-4 border p-4 rounded-md mt-2">
                        <div class="space-y-4">
                            <Label :for="`title-${lang.code}`">Titolo ({{ lang.code }})</Label>
                            <Input :id="`title-${lang.code}`" v-model="form.title[lang.code]" required />
                        </div>
                        <div class="space-y-4">
                            <Label :for="`description-${lang.code}`">Descrizione ({{ lang.code }})</Label>
                             <textarea
                                :id="`description-${lang.code}`"
                                v-model="form.description[lang.code]"
                                class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 min-h-[100px]"
                            ></textarea>
                        </div>
                    </TabsContent>
                </Tabs>
            </form>
        </div>
    </AppLayout>
</template>
