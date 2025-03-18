<template>
    <nav v-if="dataLoaded" class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <li v-for="item in navigation" :key="item.name">
                        <router-link :to="item.route"
                                     :class="[item.current ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex gap-x-3 rounded-md p-2 text-sm font-semibold']">
                            <component :is="item.icon" class="h-6 w-6 flex-shrink-0" aria-hidden="true" />
                            {{ item.name }}
                        </router-link>
                    </li>
                </ul>
            </li>
            <li>
                <div class="text-xs font-semibold text-gray-600">Mis Dominios</div>
                <ul role="list" class="-mx-2 mt-2 space-y-1">
                    <li v-for="domain in domainsList" :key="domain.name">
                        <router-link :to="domain.route"
                                     :class="[domain.current ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex gap-x-3 rounded-md p-2 text-sm font-semibold']">
                            <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white text-xs font-medium text-gray-600">
                                {{ domain.initial }}
                            </span>
                            <span class="truncate">{{ domain.name }}</span>
                        </router-link>
                    </li>
                </ul>
            </li>
            <li class="-mx-6 mt-auto">
                <router-link to="/perfil" class="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold text-gray-900 hover:bg-gray-50">
                    <img class="h-8 w-8 rounded-full bg-gray-100"
                         src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                         alt="" />
                    <span class="sr-only">Tu perfil</span>
                    <span aria-hidden="true">Tom Cook</span>
                </router-link>
            </li>
        </ul>
    </nav>
</template>

<script>
import { useGlobalStore } from '@domainStore/globalStore.js'
export default {
    name: 'SidebarMenu',
    data() {
        return {
            dataLoaded: false,
            globalStore: useGlobalStore(),
        }
    },
    mounted() {
        this.dataLoaded = true;
    },
    computed: {
        navigation() {
            return this.globalStore.navigation || []
        },
        domainsList() {
            return this.globalStore.domainsList || []
        },
    },
}
</script>
