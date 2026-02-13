<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { dashboard } from '@/routes/index';
// import languagesRoutes from '@/routes/languages';
// import mediaRoutes from '@/routes/media';
import sectionsRoutes from '@/routes/sections/index';
import termsRoutes from '@/routes/terms/index';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Folder, Landmark, Book, LayoutGrid, Image } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Sezioni',
        href: sectionsRoutes.index(),
        icon: Landmark,
    },
    {
        title: 'Glossary',
        href: termsRoutes.index(),
        icon: Book,
    }
];

const settingNavItems: NavItem[] = [
    // {
    //     title: 'Gestisci lingue',
    //     href: languagesRoutes.index(),
    //     icon: Folder,
    // },
    // {
    //     title: 'Gestisci media',
    //     href: mediaRoutes.index(),
    //     icon: Folder,
    // },
];

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent class="justify-between">
            <NavMain label="Contenuti" :items="mainNavItems" />
            <NavMain label="Impostazioni" :items="settingNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
