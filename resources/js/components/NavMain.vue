<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem, SidebarMenuSub, SidebarMenuSubButton, SidebarMenuSubItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { ChevronRight } from 'lucide-vue-next';

const props = defineProps<{ items: NavItem[] }>();

const page = usePage<SharedData>();

// Helper to determine if a link is active or if any child link is active
const isLinkActive = (href?: string, children?: NavItem[]): boolean => {
    if (href && (page.url === href || page.url.startsWith(href + '/'))) {
        return true;
    }
    if (children) {
        // Check if any child or grandchild is active recursively
        return children.some(child => isLinkActive(child.href, child.children));
    }
    return false;
};

// Determine which collapsible item should be open initially based on the current route
const defaultOpenCollapsibleItem = computed(() => {
    // Find the parent item whose children or nested children contain the active link
    const openItem = props.items.find((item: NavItem) => item.children && isLinkActive(undefined, item.children));
    return openItem ? openItem.title : undefined;
});

</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu>
            <template v-for="item in props.items" :key="item.title">
                <!-- Render item with children as Collapsible -->
                <Collapsible
                    v-if="item.children"
                    as-child
                    :default-open="item.title === defaultOpenCollapsibleItem"
                    class="group/collapsible"
                >
                    <SidebarMenuItem>
                        <CollapsibleTrigger as-child>
                            <SidebarMenuButton 
                                :tooltip="item.title"
                                class="w-full justify-start"
                                :is-active="isLinkActive(item.href, item.children)"
                            >
                                <component :is="item.icon" v-if="item.icon" class="h-4 w-4 mr-2" />
                                <span>{{ item.title }}</span>
                                <ChevronRight v-if="item.children" class="ml-auto h-4 w-4 transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                            </SidebarMenuButton>
                        </CollapsibleTrigger>
                        <CollapsibleContent class="pt-0 pb-1 ml-4 border-l border-border">
                            <SidebarMenuSub>
                                <!-- Recursively render children -->
                                <template v-for="child in item.children" :key="child.title">
                                    <SidebarMenuSubItem v-if="!child.children">
                                        <SidebarMenuSubButton 
                                            as-child 
                                            :is-active="isLinkActive(child.href)"
                                            :tooltip="child.title"
                                        >
                                            <Link :href="child.href || '#'">
                                                <component :is="child.icon" v-if="child.icon" class="h-4 w-4 mr-2" />
                                                <span>{{ child.title }}</span>
                                            </Link>
                                        </SidebarMenuSubButton>
                                    </SidebarMenuSubItem>
                                </template>
                            </SidebarMenuSub>
                        </CollapsibleContent>
                    </SidebarMenuItem>
                </Collapsible>

                <!-- Render single item without children as SidebarMenuItem -->
                <SidebarMenuItem v-else>
                    <SidebarMenuButton 
                        as-child 
                        :is-active="isLinkActive(item.href)"
                        :tooltip="item.title"
                    >
                        <Link :href="item.href || '#'">
                            <component :is="item.icon" v-if="item.icon" class="h-4 w-4 mr-2" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>
