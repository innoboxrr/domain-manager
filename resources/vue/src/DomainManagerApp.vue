<template>
    <div v-if="dataLoaded">
        <!-- Mobile Sidebar -->
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog class="relative z-50 xl:hidden" @close="sidebarOpen = false">
                <TransitionChild
                    as="template"
                    enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100"
                    leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-50/80"></div>
                </TransitionChild>
                <div class="fixed inset-0 flex">
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-300 transform"
                        enter-from="-translate-x-full"
                        enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leave-from="translate-x-0"
                        leave-to="-translate-x-full">
                        <DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
                            <TransitionChild
                                as="template"
                                enter="ease-in-out duration-300"
                                enter-from="opacity-0"
                                enter-to="opacity-100"
                                leave="ease-in-out duration-300"
                                leave-from="opacity-100"
                                leave-to="opacity-0">
                                <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                                    <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                                        <span class="sr-only">Cerrar sidebar</span>
                                        <XMarkIcon class="h-6 w-6 text-gray-900" aria-hidden="true" />
                                    </button>
                                </div>
                            </TransitionChild>
                            <div class="flex grow flex-col gap-y-5 ml-14 overflow-y-auto bg-white px-6 ring-1 ring-gray-200">
                                <div class="flex h-16 shrink-0 items-center">
                                    <img
                                        class="h-8 w-auto"
                                        src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500"
                                        alt="Your Company" />
                                </div>
                                <sidebar-menu />
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Desktop Sidebar -->
        <div class="hidden xl:fixed xl:inset-y-0 xl:z-50 xl:flex xl:w-72 xl:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-50 px-6 ring-1 ring-gray-200">
                <div class="flex h-16 shrink-0 items-center">
                    <img
                        class="h-8 w-auto"
                        src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500"
                        alt="Your Company" />
                </div>
                <sidebar-menu />
            </div>
        </div>
        <div class="xl:pl-72">
            <SearchHeader @open-sidebar="sidebarOpen = true" />
            <main class="lg:pr-96">
                <router-view />
            </main>
            <activity-feed />
        </div>
    </div>
</template>

<script>
    import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
    import { XMarkIcon, HomeIcon, GlobeAltIcon, ServerIcon, ClockIcon, CreditCardIcon, Cog6ToothIcon } from '@heroicons/vue/24/outline'
    import SidebarMenu from './components/SidebarMenu.vue'
    import SearchHeader from './components/SearchHeader.vue'
    import ActivityFeed from './components/ActivityFeed.vue'
    import { useGlobalStore } from '@domainStore/globalStore.js'

    export default {
        name: 'DomainDashboard',
        components: {
            Dialog,
            DialogPanel,
            TransitionChild,
            TransitionRoot,
            XMarkIcon,
            SidebarMenu,
            SearchHeader,
            ActivityFeed,
        },
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
            sidebarOpen: {
                get() {
                    return this.globalStore.sidebarOpen || false
                },
                set(value) {
                    this.globalStore.sidebarOpen = value
                },
            }
        },
    }
</script>
