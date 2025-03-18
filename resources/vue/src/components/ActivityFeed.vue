<template>
    <aside v-if="dataLoaded" class="bg-gray-50 lg:fixed lg:bottom-0 lg:right-0 lg:top-28 lg:w-96 lg:overflow-y-auto lg:border-l lg:border-gray-200">
        <header class="flex items-center justify-between border-b border-gray-200 px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
            <h2 class="text-base font-semibold text-gray-900">Historial de Actividades</h2>
            <a href="#" class="text-sm font-semibold text-blue-600">Ver todo</a>
        </header>
        <ul role="list" class="divide-y divide-gray-200">
            <li v-for="item in activityItems" :key="item.dateTime" class="px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-x-3">
                    <img :src="item.user.imageUrl" alt="" class="h-6 w-6 flex-none rounded-full bg-gray-100" />
                    <h3 class="flex-auto truncate text-sm font-semibold text-gray-900">
                        {{ item.user.name }}
                    </h3>
                    <time :datetime="item.dateTime" class="flex-none text-xs text-gray-600">
                        {{ item.date }}
                    </time>
                </div>
                <p class="mt-3 truncate text-sm text-gray-600">
                    {{ item.action }}
                </p>
            </li>
        </ul>
    </aside>
</template>

<script>
    import { useGlobalStore } from '@domainStore/globalStore.js'

    export default {
        name: 'ActivityFeed',
        data() {
            return {
                dataLoaded: false,
                globalStore: undefined,
            }
        },
        async mounted() {
            this.globalStore = await useGlobalStore();
            this.dataLoaded = true;
        },
        computed: {
            activityItems() { 
                return this.globalStore.activityItems || []
            },
        }
    }
</script>
