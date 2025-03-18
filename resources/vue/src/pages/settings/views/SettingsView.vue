<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="flex items-center justify-between border-b border-gray-200 px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
            <h1 class="text-base font-semibold text-gray-900">{{ __domain('Settings') }}</h1>
        </header>

        <!-- Contenido Principal -->
        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">
            <!-- Información de Contacto General -->
            <section class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Información de Contacto General</h2>
                <form @submit.prevent="saveGeneralSettings">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input v-model="general.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input v-model="general.email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input v-model="general.phone" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dirección</label>
                            <input v-model="general.address" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none" />
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none">
                            Guardar
                        </button>
                    </div>
                </form>
            </section>

            <!-- Contacto Técnico -->
            <section class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Contacto Técnico</h2>
                <form @submit.prevent="saveTechnicalSettings">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input v-model="technical.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input v-model="technical.email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input v-model="technical.phone" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Horario de Atención</label>
                            <input v-model="technical.hours" type="text" placeholder="Ej: 9am - 6pm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none" />
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none">
                            Guardar
                        </button>
                    </div>
                </form>
            </section>

            <!-- Contacto de Soporte -->
            <section class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Contacto de Soporte</h2>
                <form @submit.prevent="saveSupportSettings">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input v-model="support.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input v-model="support.email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input v-model="support.phone" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sitio Web</label>
                            <input v-model="support.website" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none" />
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none">
                            Guardar
                        </button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</template>

<script>
export default {
    name: 'SettingsView',
    data() {
        return {
            general: {
                name: '',
                email: '',
                phone: '',
                address: '',
            },
            technical: {
                name: '',
                email: '',
                phone: '',
                hours: '',
            },
            support: {
                name: '',
                email: '',
                phone: '',
                website: '',
            },
        }
    },
    methods: {
        saveGeneralSettings() {
            // Aquí se podría enviar la información a una API o guardarla en un store
            alert('Información de contacto general guardada');
        },
        saveTechnicalSettings() {
            alert('Información de contacto técnico guardada');
        },
        saveSupportSettings() {
            alert('Información de soporte guardada');
        },
    },
}
</script>
