<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import Button from '@/components/hibou/Button.vue';
import { Label } from '@/components/ui/label';
import termsRoutes from '@/routes/terms/index';
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
    { title: 'Glossario', href: '/admin/terms' },
    { title: 'Crea Parola', href: termsRoutes.create().url },
];

const emptyByLanguage = Object.fromEntries(languages.map((l) => [l.code, '']));

const form = useForm({
    term: { ...emptyByLanguage },
    definition: { ...emptyByLanguage },
});


function submit() {
    form.processing = true;
    form.post(termsRoutes.store().url, {
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
            <h2 class="text-xl font-semibold mb-4">Crea parola</h2>

            <form @submit.prevent="submit">
                <div class="grid gap-4 w-2/3">
                   <div class="rounded-lg border p-4 shadow">
                        <Tabs :default-value="languages[0]?.code" class="w-full">
                            <TabsList>
                                <TabsTrigger v-for="lang in languages" :key="lang.id" :value="lang.code">
                                    {{ lang.name }}
                                </TabsTrigger>
                            </TabsList>
                            <TabsContent v-for="lang in languages" :key="lang.id" :value="lang.code"
                                class="space-y-4 border p-4 rounded-md mt-2">
                                <div class="space-y-4">
                                    <Label :for="`term-${lang.code}`">Termine ({{ lang.code }})</Label>
                                    <Input :id="`term-${lang.code}`" v-model="form.term[lang.code]" required />
                                </div>
                                 <div class="space-y-4">
                                    <Label :for="`definition-${lang.code}`">Definizione ({{ lang.code }})</Label>
                                    <TipTap :id="`definition-${lang.code}`" v-model="form.definition[lang.code]">
                                    </TipTap>
                                </div>
                            </TabsContent>
                        </Tabs>
                    </div>
                    <div class="mt-4 text-right">
                        <Button type="submit" :disabled="form.processing" color-scheme="create">Aggiungi parola</Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
