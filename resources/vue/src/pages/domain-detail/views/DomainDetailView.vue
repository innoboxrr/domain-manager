<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="flex items-center justify-between border-b border-gray-200 px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
            <h1 class="text-base font-semibold text-gray-900">
                {{ __domain('Domain Detail') }}
            </h1>
        </header>

        <!-- Contenido Principal -->
        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Resumen del Dominio -->
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ domain.name }}</h2>
                        <p class="text-sm text-gray-500">Registrado el: {{ domain.registrationDate }}</p>
                        <p class="text-sm text-gray-500">Vigente hasta: {{ domain.expiryDate }}</p>
                    </div>
                    <div class="text-right">
                        <span :class="domainStatusClass" class="text-sm font-semibold">
                            {{ domain.status }}
                        </span>
                    </div>
                </div>
                <!-- Indicador de Vigencia -->
                <div class="mt-4">
                    <div class="h-2 w-full bg-gray-200 rounded-full">
                        <div :style="{ width: validityPercentage + '%' }" class="h-2 bg-green-500 rounded-full"></div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Vigencia: {{ validityPercentage }}%</p>
                </div>
            </div>

            <!-- Acciones del Dominio -->
            <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Renovación</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Renueva tu dominio para seguir disfrutando de nuestros servicios.
                    </p>
                    <div class="flex items-center justify-between">
                        <p class="text-xl font-semibold text-green-600">${{ renewalPrice }}</p>
                        <button @click="renewDomain" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none">
                            Renovar Ahora
                        </button>
                    </div>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Transferencia</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        ¿Quieres transferir tu dominio? Gestiona la transferencia aquí.
                    </p>
                    <button @click="transferDomain" class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none">
                        Iniciar Transferencia
                    </button>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información Adicional</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Registrador</p>
                        <p class="mt-1 text-sm text-gray-500">{{ domain.registrar }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Estado DNS</p>
                        <p class="mt-1 text-sm text-gray-500">{{ domain.dnsStatus }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Fecha de Creación</p>
                        <p class="mt-1 text-sm text-gray-500">{{ domain.createdAt }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Última Actualización</p>
                        <p class="mt-1 text-sm text-gray-500">{{ domain.updatedAt }}</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
export default {
    name: 'DomainDetailView',
    data() {
        return {
            domain: {
                name: 'example.com',
                registrationDate: '2023-01-01',
                expiryDate: '2025-01-01',
                status: 'Activo',
                registrar: 'GoDaddy',
                dnsStatus: 'Configurado',
                createdAt: '2023-01-01',
                updatedAt: '2023-06-15',
            },
            renewalPrice: 12.99,
        }
    },
    computed: {
        validityPercentage() {
            // Calcula el porcentaje de vigencia
            const regDate = new Date(this.domain.registrationDate);
            const expiryDate = new Date(this.domain.expiryDate);
            const now = new Date();
            const total = expiryDate - regDate;
            const elapsed = now - regDate;
            return Math.min(100, Math.max(0, ((elapsed / total) * 100).toFixed(0)));
        },
        domainStatusClass() {
            if (this.validityPercentage < 25) return "text-red-600";
            if (this.validityPercentage < 75) return "text-yellow-600";
            return "text-green-600";
        },
    },
    methods: {
        renewDomain() {
            // Lógica para iniciar el proceso de renovación
            alert("Iniciando proceso de renovación");
        },
        transferDomain() {
            // Lógica para iniciar la transferencia
            alert("Iniciando proceso de transferencia");
        },
    },
}
</script>
