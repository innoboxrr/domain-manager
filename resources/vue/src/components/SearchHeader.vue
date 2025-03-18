<template>
    <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-6 border-b border-gray-200 bg-white px-4 shadow-sm sm:px-6 lg:px-8">
        <button type="button" class="-m-2.5 p-2.5 text-gray-900 xl:hidden" @click="$emit('open-sidebar')">
            <span class="sr-only">Abrir sidebar</span>
            <Bars3Icon class="h-5 w-5" aria-hidden="true" />
        </button>
        <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
            <form class="grid flex-1 grid-cols-1" action="#" method="GET">
                <input type="search"
                       name="search"
                       aria-label="Buscar"
                       class="col-start-1 row-start-1 block w-full bg-transparent pl-8 text-base text-gray-900 outline-none placeholder:text-gray-500 sm:text-sm focus:placeholder-gray-400 focus:border-0 rounded-md border-0 focus:ring-0"
                       placeholder="Buscar" />
                <MagnifyingGlassIcon class="pointer-events-none col-start-1 row-start-1 h-5 w-5 self-center text-gray-500" aria-hidden="true" />
            </form>
        </div>
    </div>
</template>

<script>
import { Bars3Icon, MagnifyingGlassIcon } from '@heroicons/vue/20/solid'
export default {
    name: 'SearchHeader',
    components: {
        Bars3Icon,
        MagnifyingGlassIcon,
    },
}
</script>
