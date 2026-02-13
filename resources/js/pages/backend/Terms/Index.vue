<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import Button from '@/components/hibou/Button.vue';
import termsRoutes from '@/routes/terms/index';
import { type Language, TermData } from '@/types/flexhibition';
import { type BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/vue3';
import Card from '@/components/hibou/Card.vue';

const page = usePage();
const languages = page.props.languages as Language[];
const primaryLanguage = page.props.primaryLanguage as Language | null;

const primaryLanguageCode = primaryLanguage?.code || 'it';
const props = defineProps<{ terms: TermData[] }>();
console.log(props.terms);


const breadcrumbs: BreadcrumbItem[] =  [
    { title: 'Glossario', href: termsRoutes.index().url }
];
</script>

<template>
    <Head title="Glossario" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Glossario</h2>
                <Button @click="router.visit(termsRoutes.create().url)" colorScheme="create" class="ml-6 h-8" :href="termsRoutes.create().url">
                    Aggiungi parola
                </Button>
            </div>
            <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
                <table v-if="props.terms.length > 0" class="w-full text-sm text-left rtl:text-right text-body">
                    <thead class="bg-neutral-secondary-soft border-b border-default">
                        <tr>
                            <th class="px-6 py-3 font-medium">Termine</th>
                            <th class="px-6 py-3 font-medium">Definizione</th>
                            <th class="px-6 py-3 font-medium text-right">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="term in terms" :key="term.id" class="odd:bg-neutral-primary even:bg-neutral-secondary-soft border-b border-default">
                            <td class="px-6 py-4">{{ term.term[primaryLanguageCode] }}</td>
                            <td class="px-6 py-4" v-html="term.definition[primaryLanguageCode]"></td>
                            <td class="px-6 py-4 text-right">
                                <Button @click="router.visit(termsRoutes.show(term.id).url)" colorScheme="show" class="ml-2 h-8" >
                                    Dettagli
                                </Button>
                                <Button @click="router.visit(termsRoutes.edit(term.id).url)" colorScheme="edit" class="ml-2 h-8">
                                    Modifica
                                </Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
