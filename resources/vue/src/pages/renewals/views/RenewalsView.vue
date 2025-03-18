<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="flex items-center justify-between border-b border-gray-200 px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
            <h1 class="text-base font-semibold text-gray-900">
                {{ __domain('Renewals') }}
            </h1>
        </header>

        <!-- Contenido Principal -->
        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Dominios Próximos a Renovar -->
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Dominios Próximos a Renovar</h2>
                <div v-if="renewals.length === 0" class="text-center text-gray-500">
                    No hay dominios próximos a renovar.
                </div>
                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dominio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expira</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Días Restantes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Costo Renovación</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="renewal in renewals" :key="renewal.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ renewal.domain }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ renewal.expiryDate }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ renewal.daysLeft }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ renewal.price }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <button @click="renewDomain(renewal)" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none">
                                        Renovar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Historial de Renovaciones -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Historial de Renovaciones</h2>
                <div v-if="history.length === 0" class="text-center text-gray-500">
                    No se han realizado renovaciones.
                </div>
                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dominio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Renovación</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Costo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método de Pago</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="record in history" :key="record.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ record.domain }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ record.renewalDate }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ record.cost }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ record.paymentMethod }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
export default {
    name: 'RenewalsView',
    data() {
        return {
            renewals: [
                {
                    id: 1,
                    domain: 'example.com',
                    expiryDate: '2025-01-01',
                    daysLeft: 30,
                    price: 12.99,
                },
                {
                    id: 2,
                    domain: 'testdomain.net',
                    expiryDate: '2024-12-15',
                    daysLeft: 15,
                    price: 15.99,
                },
                {
                    id: 3,
                    domain: 'mywebsite.org',
                    expiryDate: '2025-02-01',
                    daysLeft: 45,
                    price: 10.99,
                },
            ],
            history: [
                {
                    id: 1,
                    domain: 'oldexample.com',
                    renewalDate: '2024-06-01',
                    cost: 12.99,
                    paymentMethod: 'Tarjeta de Crédito',
                },
                {
                    id: 2,
                    domain: 'anotherdomain.net',
                    renewalDate: '2024-05-15',
                    cost: 15.99,
                    paymentMethod: 'PayPal',
                },
            ],
        }
    },
    methods: {
        renewDomain(renewal) {
            // Lógica para iniciar la renovación
            alert(`Iniciando renovación para ${renewal.domain} por $${renewal.price}`);
            // Aquí se podría redirigir a un formulario de pago o mostrar una modal de confirmación
        },
    },
}
</script>
