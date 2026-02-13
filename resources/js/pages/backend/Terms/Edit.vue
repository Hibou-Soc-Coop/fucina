<script setup lang="ts">
import Button from '@/components/hibou/Button.vue';
import TipTap from '@/components/hibou/TipTap.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import termsRoutes from '@/routes/terms/index';
import { type BreadcrumbItem } from '@/types';
import { TermData, type Language } from '@/types/flexhibition';
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';

const props = defineProps<{ term: TermData }>();

const page = usePage();
const languages = page.props.languages as Language[];

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Glossario', href: termsRoutes.index().url },
    { title: 'Modifica Parola', href: '' },
];

const emptyByLanguage = Object.fromEntries(languages.map((l) => [l.code, '']));

const form = useForm({
    term: props.term.term || { ...emptyByLanguage },
    definition: props.term.definition || { ...emptyByLanguage },
});

function submit() {
    form.processing = true;
    form.put(termsRoutes.update(props.term.id).url, {
        onFinish: () => {
            form.processing = false;
        },
    });
}
function confirmDelete() {
    if (confirm('Sei sicuro di voler eliminare questa parola?')) {
        form.delete(termsRoutes.destroy(props.term.id).url);
    }
}
</script>

<template>
    <Head title="Modifica Parola" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h2 class="text-xl font-semibold mb-4">Modifica Parola</h2>

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
                                    <Label :for="`term-${lang.code}`">Parola ({{ lang.code }})</Label>
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
                    <div class="mt-4 flex justify-between items-center">
                         <Link
                            :href="termsRoutes.destroy(props.term.id).url"
                            method="delete"
                            as="button"
                            type="button"
                            class="text-red-600 hover:text-red-800 font-medium"
                            @click.prevent="confirmDelete"
                        >
                            Elimina
                        </Link>
                        <Button type="submit" :disabled="form.processing" color-scheme="edit">Salva modifiche</Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
