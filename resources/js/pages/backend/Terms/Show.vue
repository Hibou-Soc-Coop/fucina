<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import termsRoutes from '@/routes/terms/index';
import { type BreadcrumbItem } from '@/types';
import { TermData, type Language } from '@/types/flexhibition';
import Button from '@/components/hibou/Button.vue';

const props = defineProps<{ term: TermData }>();

const page = usePage();
const languages = page.props.languages as Language[];

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Glossario', href: termsRoutes.index().url },
    { title: 'Dettagli Parola', href: '' },
];
</script>

<template>
    <Head title="Dettagli Parola" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Dettagli Parola</h2>
                <Link :href="termsRoutes.edit(props.term.id).url">
                    <Button color-scheme="edit">Modifica</Button>
                </Link>
            </div>

            <div class="w-2/3 rounded-lg border p-4 shadow">
                 <Tabs :default-value="languages[0]?.code" class="w-full">
                    <TabsList>
                        <TabsTrigger v-for="lang in languages" :key="lang.id" :value="lang.code">
                            {{ lang.name }}
                        </TabsTrigger>
                    </TabsList>
                    <TabsContent v-for="lang in languages" :key="lang.id" :value="lang.code"
                        class="space-y-4 border p-4 rounded-md mt-2">
                        <div>
                            <h3 class="font-bold text-gray-700">Parola</h3>
                            <p class="text-lg">{{ props.term.term[lang.code] }}</p>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-700">Definizione</h3>
                            <div class="prose max-w-none" v-html="props.term.definition[lang.code]"></div>
                        </div>
                    </TabsContent>
                </Tabs>
            </div>
        </div>
    </AppLayout>
</template>
