<template>
    <div>
        <header class="flex items-center justify-between border-b border-gray-200 px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
            <h1 class="text-base font-semibold text-gray-900">Administración de Dominios</h1>

            <!-- Sort dropdown -->
            <Menu as="div" class="relative">
                <MenuButton class="flex items-center gap-x-1 text-sm font-medium text-gray-900">
                    Ordenar por
                    <ChevronUpDownIcon class="h-5 w-5 text-gray-600" aria-hidden="true" />
                </MenuButton>
                <transition enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95">
                    <MenuItems class="absolute right-0 z-10 mt-2.5 w-40 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-200 focus:outline-none">
                        <MenuItem v-slot="{ active }">
                            <a href="#"
                                :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm text-gray-900']">Nombre</a>
                        </MenuItem>
                        <MenuItem v-slot="{ active }">
                            <a href="#"
                                :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm text-gray-900']">Fecha de renovación</a>
                        </MenuItem>
                        <MenuItem v-slot="{ active }">
                            <a href="#"
                                :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm text-gray-900']">Estado</a>
                        </MenuItem>
                    </MenuItems>
                </transition>
            </Menu>
        </header>

        <!-- Domain list -->
        <ul role="list" class="divide-y divide-gray-200">
            <li v-for="domain in domains" :key="domain.id" class="relative flex items-center space-x-4 px-4 py-4 sm:px-6 lg:px-8">
                <div class="min-w-0 flex-auto">
                    <div class="flex items-center gap-x-3">
                        <div :class="[statuses[domain.status], 'flex-none rounded-full p-1']">
                            <div class="h-2 w-2 rounded-full bg-current"></div>
                        </div>
                        <h2 class="min-w-0 text-sm font-semibold text-gray-900">
                            <a :href="domain.href" class="flex gap-x-2">
                                <span class="truncate">{{ domain.domainName }}</span>
                                <span class="text-gray-400">|</span>
                                <span class="whitespace-nowrap">{{ domain.registrar }}</span>
                            </a>
                        </h2>
                    </div>
                    <div class="mt-3 flex items-center gap-x-2.5 text-xs text-gray-600">
                        <p class="truncate">{{ domain.description }}</p>
                        <svg viewBox="0 0 2 2" class="h-0.5 w-0.5 flex-none fill-gray-300">
                            <circle cx="1" cy="1" r="1" />
                        </svg>
                        <p class="whitespace-nowrap">{{ domain.statusText }}</p>
                    </div>
                </div>
                <div class="flex-none rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset text-gray-600 bg-gray-100">
                    {{ domain.expiry }}
                </div>
                <ChevronRightIcon class="h-5 w-5 flex-none text-gray-600" aria-hidden="true" />
            </li>
        </ul>
    </div>
</template>

<script>
    import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { ChevronUpDownIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'

    export default {
        name: 'DomainDashboardSection',
        components: {
            Menu,
            MenuButton,
            MenuItem,
            MenuItems,
            ChevronUpDownIcon,
            ChevronRightIcon,
        },
        data() {
            return {
                domains: [
                    {
                        id: 1,
                        href: '#',
                        domainName: 'example.com',
                        registrar: 'GoDaddy',
                        status: 'active',
                        statusText: 'Expira en 10 días',
                        description: 'DNS gestionado',
                        expiry: '10 días',
                    },
                    // Puedes agregar más dominios aquí...
                ],
                statuses: {
                    active: 'text-green-600 bg-green-100',
                    pending: 'text-yellow-600 bg-yellow-100',
                    expired: 'text-red-600 bg-red-100',
                },
            }
        }
    }
</script>
